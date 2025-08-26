<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\Product;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessLogicService
{
    public function __construct(
        private ConfigurationService $configurationService
    ) {}

    /**
     * إنشاء فاتورة جديدة مع معالجة كاملة للأعمال
     */
    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            // التحقق من صحة البيانات
            $this->validateInvoiceData($data);

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $data['customer_id'],
                'user_id' => Auth::id(),
                'employee_id' => Auth::user()->employee?->id,
                'branch_id' => $this->getCurrentBranchId(),
                'sale_date' => $data['sale_date'] ?? now(),
                'delivery_date' => $data['delivery_date'] ?? null,
                'contract_number' => $data['contract_number'] ?? null,
                'contract_image' => $data['contract_image'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'draft',
                'payment_status' => 'unpaid',
            ]);

            // إضافة عناصر الفاتورة
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($data['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                // التحقق من توفر المخزون
                if ($this->configurationService->get('auto_adjust_stock', true)) {
                    if ($product->stock_quantity < $itemData['quantity']) {
                        throw new Exception("المنتج {$product->display_name} غير متوفر بالكمية المطلوبة");
                    }
                }

                $unitPrice = $itemData['unit_price'] ?? $product->price;
                $quantity = $itemData['quantity'];
                $discount = $itemData['discount'] ?? 0;
                $itemTotal = ($unitPrice * $quantity) - $discount;

                // إنشاء عنصر الفاتورة
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'total' => $itemTotal,
                ]);

                $subtotal += $itemTotal;

                // تحديث المخزون
                if ($this->configurationService->get('auto_adjust_stock', true)) {
                    $product->decrement('stock_quantity', $quantity);

                    // تحقق من المخزون المنخفض
                    if ($product->stock_quantity <= $product->min_stock_level) {
                        $this->notifyLowStock($product);
                    }
                }
            }

            // حساب الضريبة
            if ($this->configurationService->get('enable_tax_calculation', true)) {
                $taxRate = $data['tax_rate'] ?? $this->configurationService->get('default_tax_rate', 14);
                $taxAmount = ($subtotal * $taxRate) / 100;
            }

            $discount = $data['discount'] ?? 0;
            $total = $subtotal + $taxAmount - $discount;

            // تحديث إجماليات الفاتورة
            $invoice->update([
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate ?? 0,
                'tax_amount' => $taxAmount,
                'discount' => $discount,
                'total' => $total,
                'status' => 'confirmed',
            ]);

            // تسجيل المعاملة المالية
            $this->recordSaleTransaction($invoice);

            // تسجيل النشاط
            ActivityLog::logCreated($invoice, "إنشاء فاتورة جديدة رقم {$invoice->invoice_number}");

            return $invoice->load(['customer', 'items.product', 'user']);
        });
    }

    /**
     * معالجة دفعة جديدة
     */
    public function processPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            // إنشاء الدفعة
            $payment = Payment::create([
                'customer_id' => $data['customer_id'],
                'employee_id' => Auth::user()->employee?->id,
                'branch_id' => $this->getCurrentBranchId(),
                'account_id' => $data['account_id'],
                'payment_method_id' => $data['payment_method_id'],
                'amount' => $data['amount'],
                'payment_date' => $data['payment_date'] ?? now(),
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'confirmed',
            ]);

            // توزيع الدفعة على الفواتير
            if (isset($data['allocations']) && is_array($data['allocations'])) {
                $remainingAmount = $payment->amount;

                foreach ($data['allocations'] as $allocation) {
                    $invoice = Invoice::findOrFail($allocation['invoice_id']);
                    $allocatedAmount = min($allocation['amount'], $remainingAmount, $invoice->remaining_balance);

                    if ($allocatedAmount > 0) {
                        PaymentAllocation::create([
                            'payment_id' => $payment->id,
                            'invoice_id' => $invoice->id,
                            'customer_id' => $payment->customer_id,
                            'allocated_amount' => $allocatedAmount,
                        ]);

                        $remainingAmount -= $allocatedAmount;

                        // تحديث حالة دفع الفاتورة
                        $this->updateInvoicePaymentStatus($invoice);
                    }
                }
            }

            // تسجيل المعاملة المالية
            $this->recordPaymentTransaction($payment);

            // تحديث رصيد الحساب
            if ($payment->account) {
                $payment->account->increment('balance', $payment->amount);
            }

            // تسجيل النشاط
            ActivityLog::logCreated($payment, "تسجيل دفعة جديدة بقيمة {$payment->amount}");

            return $payment->load(['customer', 'account', 'paymentMethod', 'allocations.invoice']);
        });
    }

    /**
     * حساب العمولة للموظف
     *
     * @return (\Illuminate\Support\Carbon|mixed)[]|int
     *
     * @psalm-return 0|array{employee_id: mixed, period_start: \Illuminate\Support\Carbon|mixed, period_end: \Illuminate\Support\Carbon|mixed, total_sales: mixed, commission_rate: mixed, commission_amount: mixed}
     */
    public function calculateCommission(int $employeeId, string $period): float
    {
        $employee = \App\Models\Employee::findOrFail($employeeId);

        if ($employee->commission_rate <= 0) {
            return 0;
        }

        // فترة الحساب (الشهر الحالي إذا لم تُحدد)
        $startDate = $period['start'] ?? now()->startOfMonth();
        $endDate = $period['end'] ?? now()->endOfMonth();

        // حساب إجمالي المبيعات للموظف
        $totalSales = Invoice::where('employee_id', $employeeId)
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // حساب العمولة
        $commission = ($totalSales * $employee->commission_rate) / 100;

        return round($commission, 2);
    }

    /**
     * تحديث حالة المخزون
     */
    public function updateInventoryStatus(int $productId, int $quantity, string $type): bool
    {
        $product = Product::findOrFail($productId);

        switch ($type) {
            case 'sale':
                $product->decrement('stock_quantity', $quantity);
                break;
            case 'return':
                $product->increment('stock_quantity', $quantity);
                break;
            case 'adjustment':
                $product->update(['stock_quantity' => $quantity]);
                break;
        }

        // تحقق من المخزون المنخفض
        if ($product->stock_quantity <= $product->min_stock_level) {
            $this->notifyLowStock($product);
        }

        ActivityLog::logCustom('inventory_update', "تحديث مخزون المنتج {$product->display_name}", $product, [
            'old_quantity' => $product->getOriginal('stock_quantity'),
            'new_quantity' => $product->stock_quantity,
            'change_type' => $type,
        ]);

        return true;
    }

    /**
     * التحقق من صحة بيانات الفاتورة
     *
     * @return void
     */
    protected function validateInvoiceData(array $data)
    {
        if (empty($data['customer_id'])) {
            throw new Exception('يجب تحديد العميل');
        }

        if (empty($data['items']) || ! is_array($data['items'])) {
            throw new Exception('يجب إضافة منتجات للفاتورة');
        }

        $customer = Customer::find($data['customer_id']);
        if (! $customer || ! $customer->is_active) {
            throw new Exception('العميل غير موجود أو غير نشط');
        }
    }

    /**
     * توليد رقم فاتورة جديد
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = ConfigurationService::get('invoice_number_prefix', 'INV');
        $branchCode = $this->getCurrentBranchCode();
        $year = date('Y');
        $month = date('m');

        $lastInvoice = Invoice::where('branch_id', $this->getCurrentBranchId())
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastInvoice ? (intval(substr($lastInvoice->invoice_number, -6)) + 1) : 1;

        return sprintf('%s-%s-%s%s-%06d', $prefix, $branchCode, $year, $month, $sequence);
    }

    /**
     * تسجيل معاملة البيع
     */
    protected function recordSaleTransaction(Invoice $invoice): void
    {
        // حساب دخل المبيعات
        $salesAccount = Account::firstOrCreate([
            'type' => 'income',
            'name' => 'مبيعات',
            'branch_id' => $invoice->branch_id,
        ]);

        Transaction::create([
            'account_id' => $salesAccount->id,
            'branch_id' => $invoice->branch_id,
            'type' => 'credit',
            'amount' => $invoice->total,
            'description' => "مبيعات - فاتورة رقم {$invoice->invoice_number}",
            'reference_type' => Invoice::class,
            'reference_id' => $invoice->id,
            'transaction_date' => $invoice->sale_date,
        ]);
    }

    /**
     * تسجيل معاملة الدفع
     *
     * @return void
     */
    protected function recordPaymentTransaction(Payment $payment)
    {
        if (! $payment->account) {
            return;
        }

        Transaction::create([
            'account_id' => $payment->account_id,
            'branch_id' => $payment->branch_id,
            'type' => 'debit',
            'amount' => $payment->amount,
            'description' => "دفعة من العميل {$payment->customer->name}",
            'reference_type' => Payment::class,
            'reference_id' => $payment->id,
            'transaction_date' => $payment->payment_date,
        ]);
    }

    /**
     * تحديث حالة دفع الفاتورة
     */
    protected function updateInvoicePaymentStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->total_paid;
        $total = $invoice->total;

        $status = $this->determinePaymentStatus($totalPaid, $total);

        $invoice->update(['payment_status' => $status]);
    }

    /**
     * تحديد حالة الدفع
     */
    private function determinePaymentStatus(float $totalPaid, float $total): string
    {
        if ($totalPaid >= $total) {
            return 'paid';
        }

        if ($totalPaid > 0) {
            return 'partial';
        }

        return 'unpaid';
    }

    /**
     * إشعار المخزون المنخفض
     */
    protected function notifyLowStock(Product $product): void
    {
        // إرسال إشعار للمديرين
        // يمكن تطبيق نظام الإشعارات هنا
        ActivityLog::logCustom('low_stock_alert', "تنبيه: مخزون منخفض للمنتج {$product->display_name}", $product, [
            'current_stock' => $product->stock_quantity,
            'min_level' => $product->min_stock_level,
        ]);
    }

    /**
     * الحصول على معرف الفرع الحالي
     */
    protected function getCurrentBranchId(): int
    {
        return session('current_branch_id') ?? Auth::user()->branch_id ?? 1;
    }

    /**
     * الحصول على كود الفرع الحالي
     */
    protected function getCurrentBranchCode(): string
    {
        return session('current_branch_code') ?? 'BR001';
    }

    /**
     * تقرير ملخص المبيعات
     *
     * @return (\Illuminate\Support\Carbon|mixed)[]
     *
     * @psalm-return array{total_invoices: mixed, total_amount: mixed, total_paid: mixed, total_tax: mixed, total_discount: mixed, period_start: \Illuminate\Support\Carbon|mixed, period_end: \Illuminate\Support\Carbon|mixed}
     */
    public function getSalesSummary(string $period): array
    {
        $startDate = $period['start'] ?? now()->startOfMonth();
        $endDate = $period['end'] ?? now()->endOfMonth();
        $branchId = $this->getCurrentBranchId();

        $invoices = Invoice::where('branch_id', $branchId)
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        return [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total'),
            'total_paid' => Payment::where('branch_id', $branchId)
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('amount'),
            'total_tax' => $invoices->sum('tax_amount'),
            'total_discount' => $invoices->sum('discount'),
            'period_start' => $startDate,
            'period_end' => $endDate,
        ];
    }
}
