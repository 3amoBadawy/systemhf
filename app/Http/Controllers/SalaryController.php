<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Services\SalaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    protected SalaryService $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    /**
     * مراجعة الراتب
     */
    public function review(Request $request, Salary $salary): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $salary = $this->salaryService->reviewSalary(
                $salary->id,
                Auth::user()->employee->id ?? 0,
                $request->input('notes')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم مراجعة الراتب بنجاح',
                'salary' => $salary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * اعتماد الراتب
     */
    public function approve(Request $request, Salary $salary): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $salary = $this->salaryService->approveSalary(
                $salary->id,
                Auth::user()->employee->id ?? 0,
                $request->input('notes')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم اعتماد الراتب بنجاح',
                'salary' => $salary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * دفع الراتب
     */
    public function pay(Request $request, Salary $salary): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,check,card',
            'bank_transfer_ref' => 'nullable|string|max:100',
        ]);

        try {
            $salary = $this->salaryService->paySalary(
                $salary->id,
                Auth::user()->employee->id ?? 0,
                $request->input('payment_method'),
                $request->input('bank_transfer_ref')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم دفع الراتب بنجاح',
                'salary' => $salary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * تصدير الرواتب للبنك
     */
    public function exportToBank(Request $request): JsonResponse
    {
        $request->validate([
            'salary_ids' => 'required|array',
            'salary_ids.*' => 'exists:salaries,id',
        ]);

        try {
            $result = $this->salaryService->exportSalariesToBank($request->input('salary_ids'));

            return response()->json([
                'success' => true,
                'message' => "تم تصدير {$result['exported_count']} راتب للبنك بنجاح",
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * تحديث الراتب
     */
    public function update(Request $request, Salary $salary): JsonResponse
    {
        $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $salary->update([
                'base_salary' => $request->input('base_salary'),
                'overtime_rate' => $request->input('overtime_rate'),
                'notes' => $request->input('notes'),
            ]);

            // إعادة حساب الراتب
            $salary->refresh();
            // Get employee from the employee_id
            $employee = \App\Models\Employee::find($salary->employee_id);
            if ($employee) {
                $this->salaryService->calculateSalary($employee);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الراتب بنجاح',
                'salary' => $salary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
