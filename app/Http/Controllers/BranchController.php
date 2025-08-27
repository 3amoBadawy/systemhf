<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BranchController extends Controller
{
    /**
     * عرض قائمة الفروع
     */
    public function index(): View
    {
        $branches = Branch::orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('branches.index', compact('branches'));
    }

    /**
     * عرض نموذج إنشاء فرع جديد
     */
    public function create(): View
    {
        return view('branches.create');
    }

    /**
     * حفظ فرع جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $branch = Branch::create([
                'name' => $request->input('name'),
                'name_ar' => $request->input('name_ar'),
                'code' => $request->input('code'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'manager_name' => $request->input('manager_name'),
                'is_active' => $request->input('is_active', true),
                'sort_order' => $request->input('sort_order', 0),
                'notes' => $request->input('notes'),
                'status' => 'active',
            ]);

            return redirect()->route('branches.index')
                ->with('success', 'تم إنشاء الفرع بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفرع: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الفرع
     */
    public function show(Branch $branch): View
    {
        $branch->load(['employees', 'customers', 'invoices', 'expenses']);
        
        return view('branches.show', compact('branch'));
    }

    /**
     * عرض نموذج تعديل الفرع
     */
    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    /**
     * تحديث فرع
     */
    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,'.$branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')
            ->with('success', 'تم تحديث الفرع بنجاح!');
    }

    /**
     * حذف الفرع
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        try {
            // التحقق من عدم وجود موظفين أو عملاء مرتبطين
            if ($branch->employees()->count() > 0 || $branch->customers()->count() > 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن حذف الفرع لوجود موظفين أو عملاء مرتبطين به!']);
            }

            $branch->delete();

            return redirect()->route('branches.index')
                ->with('success', 'تم حذف الفرع بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الفرع: '.$e->getMessage()]);
        }
    }

    /**
     * تبديل حالة الفرع
     */
    public function toggleStatus(Branch $branch): RedirectResponse
    {
        try {
            $newStatus = $branch->status === 'active' ? 'inactive' : 'active';
            $branch->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
            return redirect()->back()
                ->with('success', "تم {$statusText} الفرع بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة الفرع: '.$e->getMessage()]);
        }
    }

    /**
     * إحصائيات الفرع
     */
    public function stats(Branch $branch): View
    {
        $branch->load(['employees', 'customers', 'invoices', 'expenses']);

        $stats = [
            'total_employees' => $branch->employees()->count(),
            'active_employees' => $branch->employees()->where('status', 'active')->count(),
            'total_customers' => $branch->customers()->count(),
            'active_customers' => $branch->customers()->where('status', 'active')->count(),
            'total_invoices' => $branch->invoices()->count(),
            'total_invoice_amount' => $branch->invoices()->sum('total'),
            'total_expenses' => $branch->expenses()->sum('amount'),
            'monthly_revenue' => $branch->invoices()
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total'),
        ];

        return view('branches.stats', compact('branch', 'stats'));
    }
}
