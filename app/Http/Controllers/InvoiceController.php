<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * تحديث فاتورة
     */
    public function update(Request $request, Invoice $invoice): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'contract_number' => 'required|string|max:100',
            'contract_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ]);

        // رفع صورة العقد الجديدة
        if ($request->hasFile('contract_image')) {
            $contractImageFile = $request->file('contract_image');

            // حذف الصورة القديمة
            if ($invoice->contract_image) {
                Storage::disk('public')->delete($invoice->contract_image);
            }

            if ($contractImageFile instanceof \Illuminate\Http\UploadedFile) {
                $contractImagePath = $contractImageFile->store('contracts', 'public');
                if ($contractImagePath !== false) {
                    $invoice->contract_image = $contractImagePath;
                }
            }
        }

        $invoice->update([
            'customer_id' => $request->input('customer_id'),
            'sale_date' => $request->input('sale_date'),
            'contract_number' => $request->input('contract_number'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح!');
    }
}
