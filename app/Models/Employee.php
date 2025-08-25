<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $employee_number
 * @property string $name
 * @property string $name_ar
 * @property string $national_id
 * @property string $phone
 * @property string $email
 * @property \Illuminate\Support\Carbon $birth_date
 * @property \Illuminate\Support\Carbon $hire_date
 * @property string $position
 * @property string $position_ar
 * @property string $department
 * @property string $department_ar
 * @property int $branch_id
 * @property int $role_id
 * @property int|null $user_id
 * @property numeric $base_salary
 * @property numeric $commission_rate
 * @property string|null $bank_name
 * @property string|null $bank_account
 * @property string|null $iban
 * @property string $address
 * @property string|null $emergency_contact
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $termination_date
 * @property string|null $termination_reason
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendance
 * @property-read int|null $attendance_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Commission> $commissions
 * @property-read int|null $commissions_count
 * @property-read mixed $monthly_commission
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Salary> $salaries
 * @property-read int|null $salaries_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBaseSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereDepartmentAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereHireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNationalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePositionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerminationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereTerminationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'name',
        'name_ar',
        'national_id',
        'phone',
        'email',
        'birth_date',
        'hire_date',
        'position',
        'position_ar',
        'department',
        'department_ar',
        'branch_id',
        'role_id',
        'user_id',
        'base_salary',
        'commission_rate',
        'bank_name',
        'bank_account',
        'iban',
        'address',
        'emergency_contact',
        'status',
        'termination_date',
        'termination_reason',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'base_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع العمولات
     */
    public function commissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * العلاقة مع المرتبات
     */
    public function salaries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * العلاقة مع الفواتير (إذا كان بائع)
     */
    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * العلاقة مع الفرع
     */
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * العلاقة مع الدور
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة مع المستخدم
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع الشِفتات
     */
    public function shifts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Shift::class, 'employee_shift');
    }

    /**
     * العلاقة مع الحضور
     */
    public function attendance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // الموظفين النشطين فقط

    // الموظفين حسب الفرع

    // الموظفين حسب القسم

    // ترتيب الموظفين

    // حساب سنوات الخدمة

    // حساب العمولة الشهرية
    public function getMonthlyCommissionAttribute(): float
    {
        $month = now()->format('Y-m');

        return $this->commissions()
            ->where('status', 'approved')
            ->where('commission_date', 'like', $month.'%')
            ->sum('commission_amount');
    }

    // حساب إجمالي الحضور الشهري

    // الحصول على الاسم بالعربية

    // التحقق من أن الموظف نشط
    public function isActive(): bool
    {
        return $this->is_active && $this->status === 'active';
    }

    // حساب الراتب الإجمالي

}
