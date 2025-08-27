<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * عرض قائمة المصروفات
     */
    public function index(): View
    {
        $expenses = Expense::with(['branch', 'paymentMethod', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * عرض نموذج إنشاء مصروف جديد
     */
    public function create(): View
    {
        $branches = Branch::where('status', 'active')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('expenses.create', compact('branches', 'paymentMethods'));
    }

    /**
     * حفظ مصروف جديد
     */
    public function store(Request $request): RedirectResponse
    {
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

        try {
            $expense = Expense::create([
                'title' => $request->input('title'),
                'title_ar' => $request->input('title_ar'),
                'amount' => $request->input('amount'),
                'category' => $request->input('category'),
                'description' => $request->input('description'),
                'date' => $request->input('date'),
                'branch_id' => $request->input('branch_id'),
                'payment_method_id' => $request->input('payment_method_id'),
                'notes' => $request->input('notes'),
                'created_by' => Auth::id(),
                'is_approved' => false,
            ]);

            return redirect()->route('expenses.show', $expense)
                ->with('success', 'تم إنشاء المصروف بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء المصروف: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل المصروف
     */
    public function show(Expense $expense): View
    {
        $expense->load(['branch', 'paymentMethod', 'approvedBy', 'createdBy']);

        return view('expenses.show', compact('expense'));
    }

    /**
     * عرض نموذج تعديل المصروف
     */
    public function edit(Expense $expense): View|RedirectResponse
    {
        if ($expense->is_approved) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'لا يمكن تعديل مصروف معتمد!');
        }

        $branches = Branch::where('status', 'active')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('expenses.edit', compact('expense', 'branches', 'paymentMethods'));
    }

    /**
     * تحديث مصروف
     */
    public function update(Request $request, Expense $expense): RedirectResponse
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
     * حذف المصروف
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        if ($expense->is_approved) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'لا يمكن حذف مصروف معتمد!');
        }

        try {
            $expense->delete();

            return redirect()->route('expenses.index')
                ->with('success', 'تم حذف المصروف بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف المصروف: '.$e->getMessage()]);
        }
    }

    /**
     * اعتماد مصروف
     */
    public function approve(Expense $expense): RedirectResponse
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

    /**
     * إلغاء اعتماد مصروف
     */
    public function unapprove(Expense $expense): RedirectResponse
    {
        if (! $expense->is_approved) {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'المصروف غير معتمد بالفعل!');
        }

        $expense->update([
            'is_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()->route('expenses.show', $expense)
            ->with('success', 'تم إلغاء اعتماد المصروف بنجاح!');
    }

    /**
     * تقرير المصروفات
     */
    public function report(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $branchId = $request->get('branch_id');
        $category = $request->get('category');

        $query = Expense::with(['branch', 'paymentMethod', 'approvedBy'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(50);

        $branches = Branch::where('status', 'active')->get();
        $categories = Expense::distinct()->pluck('category');

        $totalAmount = $query->sum('amount');
        $approvedAmount = $query->where('is_approved', true)->sum('amount');
        $pendingAmount = $query->where('is_approved', false)->sum('amount');

        return view('expenses.report', compact(
            'expenses',
            'branches',
            'categories',
            'startDate',
            'endDate',
            'branchId',
            'category',
            'totalAmount',
            'approvedAmount',
            'pendingAmount'
        ));
    }
}
