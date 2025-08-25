<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * تحديث طريقة دفع
     */
    public function update(Request $request, PaymentMethod $paymentMethod): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,'.$paymentMethod->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'branch_id' => 'required|exists:branches,id',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        $paymentMethod->update($request->all());

        // تحديث الحساب المالي المرتبط
        $paymentMethod->updateLinkedAccount();

        return redirect()->route('payment-methods.index')
            ->with('success', 'تم تحديث طريقة الدفع والحساب المالي المرتبط بنجاح!');
    }
}
