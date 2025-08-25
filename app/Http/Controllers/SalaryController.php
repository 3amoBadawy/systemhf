<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    protected $salaryService;

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
                auth()->user()->employee->id,
                $request->notes
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
                auth()->user()->employee->id,
                $request->notes
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
                auth()->user()->employee->id,
                $request->payment_method,
                $request->bank_transfer_ref
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
            $result = $this->salaryService->exportSalariesToBank($request->salary_ids);

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
                'base_salary' => $request->base_salary,
                'overtime_rate' => $request->overtime_rate,
                'notes' => $request->notes,
            ]);

            // إعادة حساب الراتب
            $salary->refresh();
            $salary->calculateSalary();

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
