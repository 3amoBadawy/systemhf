<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Support\Facades\Log;

class SalaryService
{
    /**
     * إنشاء رواتب الموظفين
     */
    public function generateSalaries(string $period): array
    {
        // الحصول على الموظفين النشطين
        $employees = Employee::where('is_active', true)->get();

        $generatedSalaries = [];

        foreach ($employees as $employee) {
            try {
                $salary = $this->generateEmployeeSalary($employee, $period);
                if ($salary) {
                    $generatedSalaries[] = $salary;
                }
            } catch (\Exception $e) {
                Log::error("Error generating salary for employee {$employee->id}", [
                    'error' => $e->getMessage(),
                    'employee_id' => $employee->id,
                    'period' => $period,
                ]);
            }
        }

        return $generatedSalaries;
    }

    /**
     * إنشاء راتب موظف واحد
     */
    public function generateEmployeeSalary(Employee $employee, string $period): ?Salary
    {
        // التحقق من عدم وجود راتب مسبق
        $existingSalary = Salary::where('employee_id', $employee->id)
            ->where('year', $period['year'])
            ->where('month', $period['month'])
            ->first();

        if ($existingSalary) {
            return null; // الراتب موجود مسبقاً
        }

        // حساب الراتب
        $salaryData = $this->calculateSalary($employee, $period);

        // إنشاء الراتب
        $salary = Salary::create([
            'employee_id' => $employee->id,
            'year' => $period['year'],
            'month' => $period['month'],
            'base_salary' => $employee->base_salary,
            'allowances' => $salaryData['allowances'],
            'deductions' => $salaryData['deductions'],
            'net_salary' => $salaryData['net_salary'],
            'status' => 'generated',
        ]);

        return $salary;
    }

    /**
     * مراجعة الراتب
     */
    public function reviewSalary(int $salaryId, int $reviewerId, ?string $notes = null): ?Salary
    {
        $salary = Salary::findOrFail($salaryId);

        if ($salary->status !== 'generated') {
            throw new \Exception('لا يمكن مراجعة الراتب في هذه الحالة');
        }

        $salary->status = 'reviewed';
        $salary->reviewed_by = $reviewerId;
        $salary->reviewed_at = now();
        if ($notes) {
            $salary->notes = $notes;
        }

        $salary->save();

        return $salary;
    }

    /**
     * اعتماد الراتب
     */
    public function approveSalary(int $salaryId, int $approverId, ?string $notes = null): ?Salary
    {
        $salary = Salary::findOrFail($salaryId);

        if ($salary->status !== 'reviewed') {
            throw new \Exception('يجب مراجعة الراتب قبل الاعتماد');
        }

        $salary->status = 'approved';
        $salary->approved_by = $approverId;
        $salary->approved_at = now();
        if ($notes) {
            $salary->notes = $notes;
        }

        $salary->save();

        return $salary;
    }

    /**
     * دفع الراتب
     */
    public function paySalary(int $salaryId, int $payerId, string $paymentMethod, ?string $bankTransferRef = null): ?Salary
    {
        $salary = Salary::findOrFail($salaryId);

        if ($salary->status !== 'approved') {
            throw new \Exception('يجب اعتماد الراتب قبل الدفع');
        }

        $salary->status = 'paid';
        $salary->paid_by = $payerId;
        $salary->paid_at = now();
        $salary->payment_method = $paymentMethod;
        $salary->bank_transfer_ref = $bankTransferRef;

        $salary->save();

        return $salary;
    }

    /**
     * تصدير الرواتب للبنك
     */
    public function exportSalariesToBank(array $salaryIds): array
    {
        $salaries = Salary::whereIn('id', $salaryIds)
            ->where('status', 'paid')
            ->where('is_exported_to_bank', false)
            ->get();

        $exportedCount = 0;

        foreach ($salaries as $salary) {
            try {
                $salary->is_exported_to_bank = true;
                $salary->save();
                $exportedCount++;
            } catch (\Exception $e) {
                Log::error("Error exporting salary {$salary->id} to bank", [
                    'error' => $e->getMessage(),
                    'salary_id' => $salary->id,
                ]);
            }
        }

        return [
            'total_salaries' => count($salaryIds),
            'exported_count' => $exportedCount,
            'failed_count' => count($salaryIds) - $exportedCount,
        ];
    }

    /**
     * الحصول على تقرير الرواتب
     */
    public function getSalaryReport(array $filters = []): array
    {
        $query = Salary::query();

        // تطبيق الفلاتر
        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        $salaries = $query->get();

        return [
            'total_salaries' => $salaries->count(),
            'total_amount' => $salaries->sum('net_salary'),
            'status_summary' => $salaries->groupBy('status')->map->count(),
            'employee_summary' => $salaries->groupBy('employee_id')->map->sum('net_salary'),
        ];
    }

    /**
     * الحصول على تقرير الرواتب لشهر معين
     */
    public function getMonthlySalaryReport(int $year, int $month, ?int $branchId = null): array
    {
        $query = Salary::where('year', $year)
            ->where('month', $month)
            ->with(['employee.branch']);

        if ($branchId) {
            $query->whereHas('employee', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        $salaries = $query->get();

        $report = [
            'year' => $year,
            'month' => $month,
            'branch_id' => $branchId,
            'total_salaries' => $salaries->count(),
            'total_gross_salary' => $salaries->sum('gross_salary'),
            'total_net_salary' => $salaries->sum('net_salary'),
            'total_allowances' => $salaries->sum('total_allowances'),
            'total_deductions' => $salaries->sum('total_deductions'),
            'total_overtime_amount' => $salaries->sum('overtime_amount'),
            'total_commission_amount' => $salaries->sum('commission_amount'),
            'status_breakdown' => [
                'generated' => $salaries->where('status', 'generated')->count(),
                'reviewed' => $salaries->where('status', 'reviewed')->count(),
                'approved' => $salaries->where('status', 'approved')->count(),
                'paid' => $salaries->where('status', 'paid')->count(),
            ],
            'salaries' => $salaries,
        ];

        return $report;
    }

    /**
     * الحصول على تقرير راتب موظف
     */
    public function getEmployeeSalaryReport(int $employeeId, int $startYear, int $endYear): array
    {
        $salaries = Salary::where('employee_id', $employeeId)
            ->whereBetween('year', [$startYear, $endYear])
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $report = [
            'employee_id' => $employeeId,
            'start_year' => $startYear,
            'end_year' => $endYear,
            'total_salaries' => $salaries->count(),
            'total_gross_salary' => $salaries->sum('gross_salary'),
            'total_net_salary' => $salaries->sum('net_salary'),
            'total_allowances' => $salaries->sum('total_allowances'),
            'total_deductions' => $salaries->sum('total_deductions'),
            'total_overtime_amount' => $salaries->sum('overtime_amount'),
            'total_commission_amount' => $salaries->sum('commission_amount'),
            'monthly_breakdown' => $salaries->groupBy('year')->map(function ($yearSalaries) {
                return $yearSalaries->groupBy('month')->map(function ($monthSalaries) {
                    return [
                        'count' => $monthSalaries->count(),
                        'gross_salary' => $monthSalaries->sum('gross_salary'),
                        'net_salary' => $monthSalaries->sum('net_salary'),
                        'allowances' => $monthSalaries->sum('total_allowances'),
                        'deductions' => $monthSalaries->sum('total_deductions'),
                    ];
                });
            }),
            'salaries' => $salaries,
        ];

        return $report;
    }

    /**
     * تصدير تقرير الرواتب إلى CSV
     */
    public function exportSalaryReportToCSV(array $salaryData, ?string $filename = null): string
    {
        if (! $filename) {
            'salary_report_'.now()->format('Y-m-d_H-i-s').'.csv';
        }

        $headers = [
            'Employee ID',
            'Employee Name',
            'Branch',
            'Month',
            'Year',
            'Base Salary',
            'Overtime Hours',
            'Overtime Amount',
            'Commission',
            'Allowances',
            'Deductions',
            'Gross Salary',
            'Net Salary',
            'Status',
        ];

        $csvContent = implode(',', $headers)."\n";

        foreach ($salaryData as $salary) {
            $row = [
                $salary->employee->employee_number,
                $salary->employee->name,
                $salary->employee->branch->name ?? '',
                $salary->month_name,
                $salary->year,
                $salary->base_salary,
                $salary->overtime_hours,
                $salary->overtime_amount,
                $salary->commission_amount,
                $salary->total_allowances,
                $salary->total_deductions,
                $salary->gross_salary,
                $salary->net_salary,
                $salary->status_text,
            ];

            $csvContent .= implode(',', $row)."\n";
        }

        return $csvContent;
    }

    /**
     * إنشاء ملف CSV للبنك
     */
    public function createBankCSV(array $salaryIds): string
    {
        $salaries = Salary::whereIn('id', $salaryIds)
            ->where('status', 'paid')
            ->with(['employee'])
            ->get();

        $headers = [
            'Employee Name',
            'Bank Name',
            'Account Number',
            'IBAN',
            'Amount',
            'Reference',
        ];

        $csvContent = implode(',', $headers)."\n";

        foreach ($salaries as $salary) {
            $row = [
                $salary->employee->name,
                $salary->employee->bank_name ?? '',
                $salary->employee->bank_account ?? '',
                $salary->employee->iban ?? '',
                $salary->net_salary,
                'SALARY_'.$salary->id,
            ];

            $csvContent .= implode(',', $row)."\n";
        }

        return $csvContent;
    }

    /**
     * حساب إحصائيات الرواتب
     */
    public function getSalaryStatistics(?int $year = null, ?int $month = null): array
    {
        $query = Salary::query();

        if ($year) {
            $query->where('year', $year);
        }
        if ($month) {
            $query->where('month', $month);
        }

        $salaries = $query->get();

        $stats = [
            'total_salaries' => $salaries->count(),
            'total_gross_salary' => $salaries->sum('gross_salary'),
            'total_net_salary' => $salaries->sum('net_salary'),
            'average_gross_salary' => $salaries->avg('gross_salary'),
            'average_net_salary' => $salaries->avg('net_salary'),
            'total_allowances' => $salaries->sum('total_allowances'),
            'total_deductions' => $salaries->sum('total_deductions'),
            'total_overtime_amount' => $salaries->sum('overtime_amount'),
            'total_commission_amount' => $salaries->sum('commission_amount'),
            'status_distribution' => $salaries->groupBy('status')->map->count(),
            'monthly_totals' => $salaries->groupBy('month')->map(function ($monthSalaries) {
                return [
                    'count' => $monthSalaries->count(),
                    'gross_salary' => $monthSalaries->sum('gross_salary'),
                    'net_salary' => $monthSalaries->sum('net_salary'),
                ];
            }),
        ];

        return $stats;
    }
}
