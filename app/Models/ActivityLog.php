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
 * @method staticfinal \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityLog whereUserId($value)
 *
 * @mixin \Eloquent
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

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * تسجيل نشاط جديد
     */
    public static function log(string $action, Payment|Invoice|Product|null $model = null, ?string $description = null, $oldValues = null, ?array $newValues = null)
    {
        $user = Auth::user();
        $employee = $user && $user->employee ? $user->employee : null;
        $branch = $employee ? $employee->branch : ($user && $user->branch ? $user->branch : null);

        return static::create([
            'user_id' => $user ? $user->id : null,
            'employee_id' => $employee ? $employee->id : null,
            'branch_id' => $branch ? $branch->id : null,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * تسجيل إنشاء سجل
     */
    public static function logCreated(Invoice|Payment $model, ?string $description = null)
    {
        return static::log('created', $model, $description, null, $model->toArray());
    }

    /**
     * تسجيل نشاط مخصص
     *
     * @psalm-param 'inventory_update'|'low_stock_alert' $action
     * @psalm-param array{old_quantity?: mixed, new_quantity?: mixed, change_type?: mixed, current_stock?: mixed, min_level?: mixed}|null $data
     */
    public static function logCustom(string $action, string $description, ?Product $model = null, ?array $data = null)
    {
        return static::log($action, $model, $description, null, $data);
    }
}
