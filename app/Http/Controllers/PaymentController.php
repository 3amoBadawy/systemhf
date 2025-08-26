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
}
