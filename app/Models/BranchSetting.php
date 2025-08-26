<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $setting_key
 * @property string|null $setting_value
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereSettingKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereSettingValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BranchSetting whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class BranchSetting extends Model
{
    protected $fillable = [
        'branch_id',
        'setting_key',
        'setting_value',
        'type',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'branch_id' => 'integer',
        'setting_key' => 'string',
        'setting_value' => 'string',
        'type' => 'string',
    ];

    /**
     * الحصول على قيمة إعداد للفرع
     */
    public static function get(int $branchId, string $key, mixed $default = null): mixed
    {
        $cacheKey = "branch_setting_{$branchId}_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($branchId, $key, $default) {
            $setting = static::where('branch_id', $branchId)
                ->where('setting_key', $key)
                ->first();

            if (! $setting) {
                return $default;
            }

            return static::castValue($setting->setting_value, $setting->type);
        });
    }

    /**
     * تعيين قيمة إعداد للفرع
     *
     * @param  'boolean'|'number'|'string'  $type
     */
    public static function set(int $branchId, string $key, mixed $value, string $type = 'string'): static
    {
        $setting = static::updateOrCreate(
            [
                'branch_id' => $branchId,
                'setting_key' => $key,
            ],
            [
                'setting_value' => is_array($value) || is_object($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );

        // مسح الكاش
        Cache::forget("branch_setting_{$branchId}_{$key}");

        return $setting;
    }

    /**
     * تحويل القيمة حسب النوع
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
            case 'number':
                return (int) $value;
            case 'float':
            case 'decimal':
                return (float) $value;
            case 'json':
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * مسح كاش إعدادات الفرع
     */
    public static function clearBranchCache(int $branchId): void
    {
        Cache::forget("all_branch_settings_{$branchId}");

        // مسح كاش الإعدادات الفردية
        $keys = static::where('branch_id', $branchId)->pluck('setting_key');
        foreach ($keys as $key) {
            Cache::forget("branch_setting_{$branchId}_{$key}");
        }
    }

    /**
     * الحصول على الإعدادات الافتراضية للفرع الجديد
     *
     * @return (bool|float|int|string)[]
     *
     * @psalm-return array{working_hours_start: '09:00', working_hours_end: '17:00', break_duration_minutes: 60, late_tolerance_minutes: 15, overtime_rate_multiplier: float, max_discount_percentage: 10, auto_backup_enabled: true, notification_email: '', sms_notifications_enabled: false, require_manager_approval: true, pos_receipt_footer: 'شكراً لزيارتكم', invoice_terms: 'الشروط والأحكام', currency_symbol: 'ج.م', tax_rate: 14, delivery_fee: 0}
     */
    public static function getDefaultSettings(): array
    {
        return [
            'working_hours_start' => '09:00',
            'working_hours_end' => '17:00',
            'break_duration_minutes' => 60,
            'late_tolerance_minutes' => 15,
            'overtime_rate_multiplier' => 1.5,
            'max_discount_percentage' => 10,
            'auto_backup_enabled' => true,
            'notification_email' => '',
            'sms_notifications_enabled' => false,
            'require_manager_approval' => true,
            'pos_receipt_footer' => 'شكراً لزيارتكم',
            'invoice_terms' => 'الشروط والأحكام',
            'currency_symbol' => 'ج.م',
            'tax_rate' => 14,
            'delivery_fee' => 0,
        ];
    }
}
