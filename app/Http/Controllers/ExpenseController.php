<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * تحديث مصروف
     */
    public function update(Request $request, Expense $expense): \Illuminate\Http\RedirectResponse
    {
        if ($expense->is_approved) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'لا يمكن تعديل مصروف معتمد!');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'تم تحديث المصروف بنجاح!');
    }

    /**
     * اعتماد مصروف
     */
    public function approve(Expense $expense): \Illuminate\Http\RedirectResponse
    {
        if ($expense->is_approved) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'المصروف معتمد بالفعل!');
        }

        $userId = Auth::id();
        if ($userId === null) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'يجب تسجيل الدخول أولاً!');
        }
        $expense->approve((int) $userId);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'تم اعتماد المصروف بنجاح!');
    }
}
