<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $year
 * @property int $month
 * @property float $base_salary
 * @property array<string, mixed> $allowances
 * @property array<string, mixed> $deductions
 * @property float $net_salary
 * @property string $status
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $paid_by
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string|null $payment_method
 * @property string|null $bank_transfer_ref
 * @property bool $is_exported_to_bank
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $generated_by
 * @property \Illuminate\Support\Carbon|null $generated_at
 * @property float $overtime_hours
 * @property float $overtime_rate
 * @property float $overtime_amount
 * @property float $commission_amount
 * @property float $gross_salary
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereBankTransferRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereBaseSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereDeductions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereIsExportedToBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereNetSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Salary whereYear($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'base_salary',
        'allowances',
        'deductions',
        'net_salary',
        'status',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'payment_method',
        'bank_transfer_ref',
        'is_exported_to_bank',
        'notes',
        'generated_by',
        'generated_at',
        'overtime_hours',
        'overtime_rate',
        'overtime_amount',
        'commission_amount',
        'gross_salary',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'base_salary' => 'decimal:2',
        'allowances' => 'array',
        'deductions' => 'array',
        'net_salary' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_exported_to_bank' => 'boolean',
        'generated_at' => 'datetime',
        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'employee_id' => 'integer',
        'year' => 'integer',
        'month' => 'integer',
        'status' => 'string',
        'reviewed_by' => 'integer',
        'approved_by' => 'integer',
        'paid_by' => 'integer',
        'payment_method' => 'string',
        'bank_transfer_ref' => 'string',
        'notes' => 'string',
    ];

    // العلاقة مع الموظف

    // العلاقة مع الموظف المولد

    // العلاقة مع الموظف المراجع

    // العلاقة مع الموظف المعتمد

    // العلاقة مع الموظف الدافع

    // الرواتب حسب الشهر

    // الرواتب حسب الموظف

    // الرواتب حسب الفرع

    // الرواتب حسب الحالة

    // الرواتب المعتمدة

    // الرواتب المدفوعة

    // الرواتب المصدرة للبنك

    // توليد راتب جديد
    public static function generate(int $employeeId, int $year, int $month, int $generatedBy): self
    {
        // التحقق من عدم وجود راتب مسبق
        $existing = self::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existing) {
            throw new Exception('تم توليد الراتب مسبقاً لهذا الشهر');
        }

        $employee = Employee::findOrFail($employeeId);

        $salary = new self;
        $salary->employee_id = $employeeId;
        $salary->month = $month;
        $salary->year = $year;
        $salary->base_salary = $employee->base_salary;
        $salary->generated_by = $generatedBy;
        $salary->generated_at = now();
        $salary->status = 'generated';

        // حساب الوقت الإضافي
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $overtimeHours = $attendance->sum('overtime_minutes') / 60;
        $salary->overtime_hours = round($overtimeHours, 2);
        $salary->overtime_rate = $employee->overtime_rate ?? 0;
        $salary->overtime_amount = round($overtimeHours * $salary->overtime_rate, 2);

        // حساب العمولة
        $salary->commission_amount = $employee->getMonthlyCommissionAttribute();

        // حساب البدلات
        $salary->allowances = self::calculateAllowances($employee, $year, $month);

        // حساب الخصومات
        $salary->deductions = self::calculateDeductions($employee, $attendance);

        // حساب الراتب الإجمالي
        $salary->gross_salary = $salary->base_salary +
                               $salary->overtime_amount +
                               $salary->commission_amount +
                               array_sum($salary->allowances);

        // حساب الراتب الصافي
        $salary->net_salary = $salary->gross_salary - array_sum($salary->deductions);

        $salary->save();

        return $salary;
    }

    // حساب البدلات
    /**
     * @return (int|mixed)[]
     *
     * @psalm-return array{transport: mixed, meals: mixed, housing: 0|500}
     */
    private static function calculateAllowances(Employee $employee, int $year, int $month): array
    {
        $allowances = [
            'transport' => 0,
            'meals' => 0,
            'housing' => 0,
        ];

        // بدل النقل - حسب أيام الحضور
        $attendanceDays = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'present')
            ->count();

        $allowances['transport'] = $attendanceDays * 20; // 20 ريال يومياً

        // بدل الوجبات - حسب أيام الحضور
        $allowances['meals'] = $attendanceDays * 30; // 30 ريال يومياً

        // بدل السكن - ثابت شهرياً
        if ($employee->department === 'sales' || $employee->department === 'delivery') {
            $allowances['housing'] = 500; // 500 ريال شهرياً
        }

        return $allowances;
    }

    // حساب الخصومات
    /**
     * @return (float|int)[]
     *
     * @psalm-return array{late: float, absence: 0|float, penalties: 0}
     *
     * @phpstan-ignore-next-line
     */
    private static function calculateDeductions(Employee $employee, \Illuminate\Database\Eloquent\Collection $attendance): array
    {
        $deductions = [
            'late' => 0,
            'absence' => 0,
            'penalties' => 0,
        ];

        // خصم التأخير
        $totalLateMinutes = $attendance->sum('late_minutes');
        $deductions['late'] = round(($totalLateMinutes / 60) * 50, 2); // 50 ريال لكل ساعة تأخير

        // خصم الغياب
        $workingDays = 22; // متوسط أيام العمل الشهرية
        $presentDays = $attendance->where('status', 'present')->count();
        $absentDays = $workingDays - $presentDays;

        if ($absentDays > 0) {
            $dailySalary = $employee->base_salary / $workingDays;
            $deductions['absence'] = round($absentDays * $dailySalary, 2);
        }

        // خصم الجزاءات (يمكن إضافتها لاحقاً)
        $deductions['penalties'] = 0;

        return $deductions;
    }

    // مراجعة الراتب
    public function review(int $reviewerId, ?string $notes = null): static
    {
        if ($this->status !== 'generated') {
            throw new Exception('لا يمكن مراجعة الراتب في هذه الحالة');
        }

        $this->status = 'reviewed';
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();

        return $this;
    }

    // اعتماد الراتب
    public function approve(int $approverId, ?string $notes = null): static
    {
        if ($this->status !== 'reviewed') {
            throw new Exception('يجب مراجعة الراتب قبل الاعتماد');
        }

        $this->status = 'approved';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();

        return $this;
    }

    // دفع الراتب
    public function pay(int $payerId, string $paymentMethod, ?string $bankTransferRef = null): static
    {
        if ($this->status !== 'approved') {
            throw new Exception('يجب اعتماد الراتب قبل الدفع');
        }

        $this->status = 'paid';
        $this->paid_by = $payerId;
        $this->paid_at = now();
        $this->payment_method = $paymentMethod;
        $this->bank_transfer_ref = $bankTransferRef;

        $this->save();

        return $this;
    }

    // تصدير للبنك
    public function exportToBank(): static
    {
        if ($this->status !== 'paid') {
            throw new Exception('يجب دفع الراتب قبل التصدير للبنك');
        }

        $this->is_exported_to_bank = true;
        $this->save();

        return $this;
    }

    // الحصول على حالة الراتب

    // الحصول على طريقة الدفع

    // حساب إجمالي البدلات

    // حساب إجمالي الخصومات

    // الحصول على اسم الشهر

    // التحقق من إمكانية المراجعة

    // التحقق من إمكانية الاعتماد

    // التحقق من إمكانية الدفع

    // التحقق من إمكانية التصدير للبنك

}
