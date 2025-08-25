<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * تحديث حساب
     */
    public function update(Request $request, Account $account): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'type' => 'required|in:income,expense,asset,liability',
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.show', $account)
            ->with('success', 'تم تحديث الحساب بنجاح!');
    }

    /**
     * تحديث رصيد الحساب
     */
    public function updateBalance(Account $account): \Illuminate\Http\RedirectResponse
    {
        $account->updateBalance();

        return redirect()->route('accounts.show', $account)
            ->with('success', 'تم تحديث رصيد الحساب بنجاح!');
    }
}
