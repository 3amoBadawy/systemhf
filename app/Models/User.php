<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property int|null $branch_id
 * @property string|null $employee_number
 * @property bool $is_active
 * @property int|null $role_id
 * @property-read \App\Models\Employee|null $employee
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read array<string>|null $permissions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'branch_id',
        'employee_number',
        'is_active',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'role' => 'string',
        'phone' => 'string',
        'branch_id' => 'integer',
        'employee_number' => 'string',
        'is_active' => 'boolean',
        'role_id' => 'integer',
    ];

    /**
     * العلاقة مع الموظف
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * العلاقة مع الدور
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة مع الفرع
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * الحصول على صلاحيات المستخدم
     */
    public function getPermissionsAttribute(): ?array
    {
        $role = $this->role()->first();

        return $role ? $role->permissions : null;
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission(string $permissionName): bool
    {
        $role = $this->role()->first();
        if ($role && property_exists($role, 'permissions') && $role->permissions) {
            return in_array($permissionName, $role->permissions);
        }

        return false;
    }

    /**
     * نطاق المستخدمين النشطين
     *
     * @phpstan-ignore-next-line
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق حسب الفرع
     *
     * @phpstan-ignore-next-line
     */
    public function scopeByBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * نطاق حسب الدور
     *
     * @phpstan-ignore-next-line
     */
    public function scopeByRole(Builder $query, string|int $role): Builder
    {
        if (is_numeric($role)) {
            return $query->where('role_id', $role);
        }

        return $query->whereHas('role', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * الحصول على اسم الفرع
     */
    public function getBranchNameAttribute(): string
    {
        return $this->branch ? $this->branch->name : '';
    }
}
