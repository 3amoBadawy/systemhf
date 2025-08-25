<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * تحديث دفعة
     */
    public function update(Request $request, Payment $payment): \Illuminate\Http\RedirectResponse
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
            // حذف الصورة القديمة
            if ($payment->receipt_image) {
                Storage::disk('public')->delete($payment->receipt_image);
            }
            $receiptImagePath = $request->file('receipt_image')->store('receipts', 'public');
            $payment->receipt_image = $receiptImagePath;
        }

        $payment->update([
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_date' => $request->payment_date,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'تم تحديث الدفعة بنجاح!');
    }
}
