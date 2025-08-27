<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SupplierController extends Controller
{
    /**
     * عرض قائمة الموردين
     */
    public function index(): View
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * عرض نموذج إنشاء مورد جديد
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * حفظ مورد جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'governorate' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            $supplier = Supplier::create([
                'name' => $request->input('name'),
                'name_ar' => $request->input('name_ar'),
                'phone' => $request->input('phone'),
                'phone2' => $request->input('phone2'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'governorate' => $request->input('governorate'),
                'notes' => $request->input('notes'),
                'status' => 'active',
            ]);

            return redirect()->route('suppliers.index')
                ->with('success', 'تم إنشاء المورد بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء المورد: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل المورد
     */
    public function show(Supplier $supplier): View
    {
        $supplier->load(['products']);
        
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * عرض نموذج تعديل المورد
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * تحديث مورد
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
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

    /**
     * حذف المورد
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        try {
            // التحقق من عدم وجود منتجات مرتبطة
            if ($supplier->products()->count() > 0) {
                return redirect()->back()
                    ->withErrors(['error' => 'لا يمكن حذف المورد لوجود منتجات مرتبطة به!']);
            }

            $supplier->delete();

            return redirect()->route('suppliers.index')
                ->with('success', 'تم حذف المورد بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف المورد: '.$e->getMessage()]);
        }
    }

    /**
     * تبديل حالة المورد
     */
    public function toggleStatus(Supplier $supplier): RedirectResponse
    {
        try {
            $newStatus = $supplier->status === 'active' ? 'inactive' : 'active';
            $supplier->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';
            return redirect()->back()
                ->with('success', "تم {$statusText} المورد بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة المورد: '.$e->getMessage()]);
        }
    }
}
