<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index(): View
    {
        $payments = Payment::with(['customer', 'invoice', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /**
     * عرض نموذج إنشاء دفعة جديدة
     */
    public function create(): View
    {
        $customers = Customer::where('status', 'active')->get();
        $invoices = Invoice::where('payment_status', '!=', 'paid')->get();

        return view('payments.create', compact('customers', 'invoices'));
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
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,other',
            'payment_status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $paymentData = [
                'customer_id' => $request->input('customer_id'),
                'invoice_id' => $request->input('invoice_id'),
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_status' => $request->input('payment_status'),
                'payment_date' => $request->input('payment_date'),
                'reference_number' => $request->input('reference_number'),
                'notes' => $request->input('notes'),
            ];

            $payment = Payment::create($paymentData);

            // رفع صورة الإيصال
            if ($request->hasFile('receipt_image')) {
                $receiptImagePath = $request->file('receipt_image')->store('receipts', 'public');
                $payment->update(['receipt_image' => $receiptImagePath]);
            }

            // تحديث حالة الدفع في الفاتورة إذا كانت موجودة
            if ($payment->invoice_id) {
                $this->updateInvoicePaymentStatus($payment->invoice);
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
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,other',
            'payment_status' => 'required|in:pending,completed,failed,refunded',
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
            'customer_id' => $request->input('customer_id'),
            'amount' => $request->input('amount'),
            'payment_method' => $request->input('payment_method'),
            'payment_status' => $request->input('payment_status'),
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
        $payments = Payment::where('reference_number', 'like', "%{$query}%")
            ->orWhereHas('customer', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with(['customer', 'invoice', 'paymentMethod'])
            ->paginate(20);

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
            'total_payments' => Payment::sum('amount'),
            'total_completed' => Payment::where('payment_status', 'completed')->sum('amount'),
            'total_pending' => Payment::where('payment_status', 'pending')->sum('amount'),
            'total_failed' => Payment::where('payment_status', 'failed')->sum('amount'),
            'monthly_payments' => Payment::where('created_at', '>=', now()->startOfMonth())->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * تحديث حالة الدفع في الفاتورة
     */
    private function updateInvoicePaymentStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $total = $invoice->total;

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
