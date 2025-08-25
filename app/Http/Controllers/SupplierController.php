<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * تحديث مورد
     */
    public function update(Request $request, Supplier $supplier): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'governorate' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'تم تحديث المورد بنجاح!');
    }
}
