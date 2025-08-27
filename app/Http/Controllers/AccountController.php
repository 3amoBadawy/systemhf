<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * عرض قائمة الحسابات
     */
    public function index(): View
    {
        $accounts = Account::with(['branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('accounts.index', compact('accounts'));
    }

    /**
     * عرض نموذج إنشاء حساب جديد
     */
    public function create(): View
    {
        $branches = Branch::where('status', 'active')->get();
        
        return view('accounts.create', compact('branches'));
    }

    /**
     * حفظ حساب جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'type' => 'required|in:income,expense,asset,liability',
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
        ]);

        try {
            $account = Account::create([
                'name' => $request->input('name'),
                'name_ar' => $request->input('name_ar'),
                'type' => $request->input('type'),
                'balance' => $request->input('balance'),
                'description' => $request->input('description'),
                'branch_id' => $request->input('branch_id'),
                'status' => 'active',
            ]);

            return redirect()->route('accounts.show', $account)
                ->with('success', 'تم إنشاء الحساب بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحساب: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الحساب
     */
    public function show(Account $account): View
    {
        $account->load(['branch', 'transactions']);
        
        return view('accounts.show', compact('account'));
    }

    /**
     * عرض نموذج تعديل الحساب
     */
    public function edit(Account $account): View
    {
        $branches = Branch::where('status', 'active')->get();
        
        return view('accounts.edit', compact('account', 'branches'));
    }

    /**
     * تحديث حساب
     */
    public function update(Request $request, Account $account): RedirectResponse
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
     * حذف الحساب
     */
    public function destroy(Account $account): RedirectResponse
    {
        try {
            // التحقق من عدم وجود معاملات مرتبطة
            if ($account->transactions()->count() > 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن حذف الحساب لوجود معاملات مرتبطة به!']);
            }

            $account->delete();

            return redirect()->route('accounts.index')
                ->with('success', 'تم حذف الحساب بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الحساب: '.$e->getMessage()]);
        }
    }

    /**
     * تبديل حالة الحساب
     */
    public function toggleStatus(Account $account): RedirectResponse
    {
        try {
            $newStatus = $account->status === 'active' ? 'inactive' : 'active';
            $account->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
            return redirect()->back()
                ->with('success', "تم {$statusText} الحساب بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة الحساب: '.$e->getMessage()]);
        }
    }

    /**
     * تحديث رصيد الحساب
     */
    public function updateBalance(Account $account): RedirectResponse
    {
        $account->updateBalance();

        return redirect()->route('accounts.show', $account)
            ->with('success', 'تم تحديث رصيد الحساب بنجاح!');
    }

    /**
     * عرض معاملات الحساب حسب الفرع
     */
    public function showTransactionsByBranch(Account $account, int $branchId): View
    {
        $branch = Branch::findOrFail($branchId);
        $transactions = $account->transactions()
            ->where('branch_id', $branchId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('accounts.transactions-by-branch', compact('account', 'branch', 'transactions'));
    }

    /**
     * عرض جميع معاملات الحساب
     */
    public function showAllTransactions(Account $account): View
    {
        $transactions = $account->transactions()
            ->with(['branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('accounts.all-transactions', compact('account', 'transactions'));
    }

    /**
     * تقرير الحساب
     */
    public function report(Account $account): View
    {
        $account->load(['branch', 'transactions.branch']);

        $stats = [
            'total_transactions' => $account->transactions()->count(),
            'total_credits' => $account->transactions()->sum('credits'),
            'total_debits' => $account->transactions()->sum('debits'),
            'current_balance' => $account->balance,
            'monthly_transactions' => $account->transactions()
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return view('accounts.report', compact('account', 'stats'));
    }
}
