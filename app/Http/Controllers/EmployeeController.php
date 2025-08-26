<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * تحديث موظف
     */
    public function update(Request $request, Employee $employee): \Illuminate\Http\RedirectResponse
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
}
