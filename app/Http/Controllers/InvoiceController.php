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
            // حذف الصورة القديمة
            if ($invoice->contract_image) {
                Storage::disk('public')->delete($invoice->contract_image);
            }
            $contractImagePath = $request->file('contract_image')->store('contracts', 'public');
            $invoice->contract_image = $contractImagePath;
        }

        $invoice->update([
            'customer_id' => $request->customer_id,
            'sale_date' => $request->sale_date,
            'contract_number' => $request->contract_number,
            'notes' => $request->notes,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح!');
    }
}
