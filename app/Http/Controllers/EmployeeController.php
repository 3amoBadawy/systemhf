<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * عرض قائمة الموظفين
     */
    public function index(): View
    {
        $employees = Employee::with(['branch', 'role', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employees.index', compact('employees'));
    }

    /**
     * عرض نموذج إنشاء موظف جديد
     */
    public function create(): View
    {
        $branches = Branch::where('status', 'active')->get();
        $roles = Role::where('status', 'active')->get();

        return view('employees.create', compact('branches', 'roles'));
    }

    /**
     * حفظ موظف جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'national_id' => 'required|string|max:50|unique:employees,national_id',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:employees,email',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'position' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'department_ar' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'base_salary' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $employee = Employee::create($request->all());

            return redirect()->route('employees.index')
                ->with('success', 'تم إنشاء الموظف بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الموظف: '.$e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل الموظف
     */
    public function show(Employee $employee): View
    {
        $employee->load(['branch', 'role', 'user', 'attendances', 'salaries']);

        return view('employees.show', compact('employee'));
    }

    /**
     * عرض نموذج تعديل الموظف
     */
    public function edit(Employee $employee): View
    {
        $branches = Branch::where('status', 'active')->get();
        $roles = Role::where('status', 'active')->get();

        return view('employees.edit', compact('employee', 'branches', 'roles'));
    }

    /**
     * تحديث موظف
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number,'.$employee->id,
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'national_id' => 'required|string|max:50|unique:employees,national_id,'.$employee->id,
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:employees,email,'.$employee->id,
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'position' => 'required|string|max:255',
            'position_ar' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'department_ar' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'role_id' => 'required|exists:roles,id',
            'base_salary' => 'required|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'address' => 'required|string',
            'emergency_contact' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $employee->update($request->all());

        // تحديث بيانات المستخدم
        if ($employee->user) {
            /** @var \App\Models\User $user */
            $user = $employee->user;
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);
        }

        return redirect()->route('employees.index')
            ->with('success', 'تم تحديث الموظف بنجاح!');
    }

    /**
     * حذف الموظف
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        try {
            $employee->delete();

            return redirect()->route('employees.index')
                ->with('success', 'تم حذف الموظف بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء حذف الموظف: '.$e->getMessage()]);
        }
    }

    /**
     * البحث في الموظفين
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $employees = Employee::where('name', 'like', "%{$query}%")
            ->orWhere('name_ar', 'like', "%{$query}%")
            ->orWhere('employee_number', 'like', "%{$query}%")
            ->orWhere('national_id', 'like', "%{$query}%")
            ->with(['branch', 'role', 'user'])
            ->paginate(20);

        return view('employees.index', compact('employees', 'query'));
    }

    /**
     * تبديل حالة الموظف
     */
    public function toggleStatus(Employee $employee): RedirectResponse
    {
        try {
            $newStatus = $employee->status === 'active' ? 'inactive' : 'active';
            $employee->update(['status' => $newStatus]);

            $statusText = $newStatus === 'active' ? 'تفعيل' : 'إلغاء تفعيل';

            return redirect()->back()
                ->with('success', "تم {$statusText} الموظف بنجاح!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تغيير حالة الموظف: '.$e->getMessage()]);
        }
    }

    /**
     * تقرير الموظف
     */
    public function report(Employee $employee): View
    {
        $employee->load(['branch', 'role', 'attendances', 'salaries']);

        // إحصائيات الحضور
        $attendanceStats = [
            'total_days' => $employee->attendances()->count(),
            'present_days' => $employee->attendances()->where('status', 'present')->count(),
            'absent_days' => $employee->attendances()->where('status', 'absent')->count(),
            'late_days' => $employee->attendances()->where('status', 'late')->count(),
        ];

        // إحصائيات الرواتب
        $salaryStats = [
            'total_salaries' => $employee->salaries()->sum('amount'),
            'average_salary' => $employee->salaries()->avg('amount'),
            'last_salary' => $employee->salaries()->latest()->first(),
        ];

        return view('employees.report', compact('employee', 'attendanceStats', 'salaryStats'));
    }
}
