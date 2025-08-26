<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $employee_id
 * @property int|null $branch_id
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property array<array-key, mixed>|null $old_values
 * @property array<array-key, mixed>|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereNewValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereOldValues($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'branch_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'user_id' => 'integer',
        'employee_id' => 'integer',
        'branch_id' => 'integer',
        'action' => 'string',
        'model_type' => 'string',
        'model_id' => 'integer',
        'ip_address' => 'string',
        'user_agent' => 'string',
        'description' => 'string',
    ];

    /**
     * تسجيل نشاط جديد
     */
    public static function log(string $action, Payment|Invoice|Product|null $model = null, ?string $description = null, mixed $oldValues = null, ?array $newValues = null): static
    {
        $user = Auth::user();
        $employee = self::getEmployeeFromUser($user);
        $branch = self::getBranchFromUserOrEmployee($user, $employee);

        return static::create([
            'user_id' => $user?->id,
            'employee_id' => $employee?->id,
            'branch_id' => $branch?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * الحصول على الموظف من المستخدم
     */
    private static function getEmployeeFromUser($user): ?object
    {
        if (! $user || ! property_exists($user, 'employee') || ! $user->employee) {
            return null;
        }

        return $user->employee;
    }

    /**
     * الحصول على الفرع من المستخدم أو الموظف
     */
    private static function getBranchFromUserOrEmployee($user, $employee): ?object
    {
        $branch = self::getBranchFromEmployee($employee);
        if ($branch) {
            return $branch;
        }

        return self::getBranchFromUser($user);
    }

    /**
     * الحصول على الفرع من الموظف
     */
    private static function getBranchFromEmployee($employee): ?object
    {
        if (! $employee || ! method_exists($employee, 'branch')) {
            return null;
        }

        $branch = $employee->branch;

        return $branch ?: null;
    }

    /**
     * الحصول على الفرع من المستخدم
     */
    private static function getBranchFromUser($user): ?object
    {
        if (! $user || ! property_exists($user, 'branch') || ! $user->branch) {
            return null;
        }

        return $user->branch;
    }

    /**
     * تسجيل إنشاء سجل
     */
    public static function logCreated(Invoice|Payment $model, ?string $description = null): static
    {
        return static::log('created', $model, $description, null, $model->toArray());
    }

    /**
     * تسجيل نشاط مخصص
     *
     * @psalm-param 'inventory_update'|'low_stock_alert' $action
     * @psalm-param array{old_quantity?: mixed, new_quantity?: mixed, change_type?: mixed, current_stock?: mixed, min_level?: mixed}|null $data
     */
    public static function logCustom(string $action, string $description, ?Product $model = null, ?array $data = null): static
    {
        return static::log($action, $model, $description, null, $data);
    }
}
