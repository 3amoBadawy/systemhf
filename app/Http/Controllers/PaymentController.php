<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index(\Illuminate\Http\Request $request): View
    {
        $perPage = (int) (config('app.pagination_per_page') ?? 20);
        $query = Payment::with(['customer', 'paymentMethod'])
            ->orderBy('created_at', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($customerId = $request->get('customer_id')) {
            $query->where('customer_id', (int) $customerId);
        }

        if ($paymentMethodId = $request->get('payment_method_id')) {
            $query->where('payment_method_id', (int) $paymentMethodId);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('payment_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('payment_date', '<=', $dateTo);
        }

        $payments = $query->paginate($perPage);

        return view('payments.index', compact('payments'));
    }

    /**
     * عرض نموذج إنشاء دفعة جديدة
     */
    public function create(): View
    {
        $customers = Customer::where('status', 'active')->get();
        $invoices = Invoice::where('payment_status', '!=', 'paid')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();

        return view('payments.create', compact('customers', 'invoices', 'paymentMethods'));
    }

    /**
     * حفظ دفعة جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'status' => 'required|in:pending,confirmed,cancelled,refunded',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $paymentData = [
                'customer_id' => (int) $request->input('customer_id'),
                'amount' => (float) $request->input('amount'),
                'payment_method_id' => (int) $request->input('payment_method_id'),
                'status' => $request->input('status'),
                'payment_date' => $request->input('payment_date'),
                'reference_number' => $request->input('reference_number'),
                'notes' => $request->input('notes'),
                'user_id' => Auth::id(),
            ];

            $payment = Payment::create($paymentData);

            // رفع صورة الإيصال
            if ($request->hasFile('receipt_image')) {
                $receiptImagePath = $request->file('receipt_image')->store('receipts', 'public');
                $payment->update(['receipt_image' => $receiptImagePath]);
            }

            // تخصيص الدفعة لفاتورة إذا تم اختيارها
            if ($invoiceId = $request->input('invoice_id')) {
                PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'invoice_id' => (int) $invoiceId,
                    'allocated_amount' => (float) $payment->amount,
                    'remaining_balance' => 0,
                    'allocation_status' => ($payment->status === 'confirmed') ? 'full' : 'partial',
                ]);
                $invoice = Invoice::find((int) $invoiceId);
                if ($invoice) {
                    $this->updateInvoicePaymentStatus($invoice);
                }
            }

            return redirect()->route('payments.index')
                ->with('success', 'تم إنشاء الدفعة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الدفعة: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الدفعة
     */
    public function show(Payment $payment): View
    {
        $payment->load(['customer', 'invoice', 'paymentMethod']);

        return view('payments.show', compact('payment'));
    }

    /**
     * عرض نموذج تعديل الدفعة
     */
    public function edit(Payment $payment): View
    {
        $customers = Customer::where('status', 'active')->get();
        $invoices = Invoice::where('payment_status', '!=', 'paid')->get();

        return view('payments.edit', compact('payment', 'customers', 'invoices'));
    }

    /**
     * تحديث دفعة
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'status' => 'required|in:pending,confirmed,cancelled,refunded',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // رفع صورة الإيصال الجديدة
        if ($request->hasFile('receipt_image')) {
            $receiptImageFile = $request->file('receipt_image');

            // حذف الصورة القديمة
            if ($payment->receipt_image) {
                Storage::disk('public')->delete($payment->receipt_image);
            }

            if ($receiptImageFile instanceof \Illuminate\Http\UploadedFile) {
                $receiptImagePath = $receiptImageFile->store('receipts', 'public');
                if ($receiptImagePath !== false) {
                    $receiptImage = $receiptImagePath;
                }
            }
        }

        $updateData = [
            'customer_id' => (int) $request->input('customer_id'),
            'amount' => (float) $request->input('amount'),
            'payment_method_id' => (int) $request->input('payment_method_id'),
            'status' => $request->input('status'),
            'payment_date' => $request->input('payment_date'),
            'reference_number' => $request->input('reference_number'),
            'notes' => $request->input('notes'),
        ];

        if (isset($receiptImage)) {
            $updateData['receipt_image'] = $receiptImage;
        }

        $payment->update($updateData);

        return redirect()->route('payments.index')
            ->with('success', 'تم تحديث الدفعة بنجاح!');
    }

    /**
     * حذف الدفعة
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        try {
            $payment->delete();

            return redirect()->route('payments.index')
                ->with('success', 'تم حذف الدفعة بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الدفعة: '.$e->getMessage()]);
        }
    }

    /**
     * البحث في المدفوعات
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $perPage = (int) (config('app.pagination_per_page') ?? 20);
        $payments = Payment::where('reference_number', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with(['customer', 'invoice', 'paymentMethod'])
            ->paginate($perPage);

        return view('payments.index', compact('payments', 'query'));
    }

    /**
     * الحصول على معلومات الفاتورة
     */
    public function getInvoiceInfo(int $invoiceId): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'items'])->find($invoiceId);

        if (! $invoice) {
            return response()->json(['error' => 'الفاتورة غير موجودة'], 404);
        }

        return response()->json([
            'invoice' => $invoice,
            'total_paid' => $invoice->payments()->sum('amount'),
            'remaining_amount' => $invoice->total - $invoice->payments()->sum('amount'),
        ]);
    }

    /**
     * الحصول على إحصائيات المدفوعات
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_payments' => (float) Payment::sum('amount'),
            'total_confirmed' => (float) Payment::where('status', 'confirmed')->sum('amount'),
            'total_pending' => (float) Payment::where('status', 'pending')->sum('amount'),
            'total_cancelled' => (float) Payment::where('status', 'cancelled')->sum('amount'),
            'total_refunded' => (float) Payment::where('status', 'refunded')->sum('amount'),
            'monthly_payments' => (float) Payment::where('created_at', '>=', now()->startOfMonth())->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * تحديث حالة الدفع في الفاتورة
     */
    private function updateInvoicePaymentStatus(Invoice $invoice): void
    {
        $totalPaid = (float) PaymentAllocation::where('invoice_id', $invoice->id)->sum('allocated_amount');
        $total = (float) $invoice->total;

        if ($totalPaid >= $total) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        $invoice->update(['payment_status' => $status]);
    }
}
