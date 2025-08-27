<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentMethodController extends Controller
{
    /**
     * عرض قائمة طرق الدفع
     */
    public function index(): View
    {
        $paymentMethods = PaymentMethod::with(['branch'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * عرض نموذج إنشاء طريقة دفع جديدة
     */
    public function create(): View
    {
        $branches = Branch::where('status', 'active')->get();
        
        return view('payment-methods.create', compact('branches'));
    }

    /**
     * حفظ طريقة دفع جديدة
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'branch_id' => 'required|exists:branches,id',
            'initial_balance' => 'nullable|numeric|min:0',
        ]);

        try {
            $paymentMethod = PaymentMethod::create([
                'name' => $request->input('name'),
                'name_ar' => $request->input('name_ar'),
                'code' => $request->input('code'),
                'description' => $request->input('description'),
                'is_active' => $request->input('is_active', true),
                'sort_order' => $request->input('sort_order', 0),
                'branch_id' => $request->input('branch_id'),
                'initial_balance' => $request->input('initial_balance', 0),
                'status' => 'active',
            ]);

            // إنشاء الحساب المالي المرتبط
            $paymentMethod->createLinkedAccount();

            return redirect()->route('payment-methods.index')
                ->with('success', 'تم إنشاء طريقة الدفع والحساب المالي المرتبط بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء طريقة الدفع: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل طريقة الدفع
     */
    public function show(PaymentMethod $paymentMethod): View
    {
        $paymentMethod->load(['branch', 'linkedAccount']);
        
        return view('payment-methods.show', compact('paymentMethod'));
    }

    /**
     * عرض نموذج تعديل طريقة الدفع
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        $branches = Branch::where('status', 'active')->get();
        
        return view('payment-methods.edit', compact('paymentMethod', 'branches'));
    }

    /**
     * تحديث طريقة دفع
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
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

    /**
     * حذف طريقة الدفع
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        try {
            // التحقق من عدم وجود مدفوعات مرتبطة
            if ($paymentMethod->payments()->count() > 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن حذف طريقة الدفع لوجود مدفوعات مرتبطة بها!']);
            }

            $paymentMethod->delete();

            return redirect()->route('payment-methods.index')
                ->with('success', 'تم حذف طريقة الدفع بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف طريقة الدفع: '.$e->getMessage()]);
        }
    }

    /**
     * تبديل حالة طريقة الدفع
     */
    public function toggleStatus(PaymentMethod $paymentMethod): RedirectResponse
    {
        try {
            $newStatus = $paymentMethod->status === 'active' ? 'inactive' : 'active';
            $paymentMethod->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
            return redirect()->back()
                ->with('success', "تم {$statusText} طريقة الدفع بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة طريقة الدفع: '.$e->getMessage()]);
        }
    }

    /**
     * عرض الحساب المالي المرتبط
     */
    public function showAccount(PaymentMethod $paymentMethod): View
    {
        $paymentMethod->load(['linkedAccount', 'branch']);
        
        return view('payment-methods.account', compact('paymentMethod'));
    }

    /**
     * إنشاء حساب مالي مرتبط
     */
    public function createAccount(PaymentMethod $paymentMethod): RedirectResponse
    {
        try {
            $paymentMethod->createLinkedAccount();

            return redirect()->route('payment-methods.show', $paymentMethod)
                ->with('success', 'تم إنشاء الحساب المالي المرتبط بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحساب المالي: '.$e->getMessage()]);
        }
    }
}
