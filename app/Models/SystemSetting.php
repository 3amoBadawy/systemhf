<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $category
 * @property string $key
 * @property string|null $value
 * @property string $type
 * @property string $name_ar
 * @property string|null $name_en
 * @property string|null $description_ar
 * @property string|null $description_en
 * @property string|null $validation_rules
 * @property string|null $default_value
 * @property bool $is_editable
 * @property bool $requires_restart
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereIsEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereRequiresRestart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereValidationRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereValue($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SystemSetting extends Model
{
    protected $fillable = [
        'category',
        'key',
        'value',
        'type',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'validation_rules',
        'default_value',
        'is_editable',
        'requires_restart',
        'sort_order',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_editable' => 'boolean',
        'requires_restart' => 'boolean',
        'sort_order' => 'integer',
        'category' => 'string',
        'key' => 'string',
        'value' => 'string',
        'type' => 'string',
        'name_ar' => 'string',
        'name_en' => 'string',
        'description_ar' => 'string',
        'description_en' => 'string',
        'validation_rules' => 'string',
        'default_value' => 'string',
    ];

    /**
     * الحصول على قيمة إعداد
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            return self::castValue($setting->value, $setting->type);
        }

        return $default;
    }

    /**
     * تعيين قيمة إعداد
     */
    public static function set(string $key, mixed $value, string $type = 'string'): static
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
            ]
        );

        return $setting;
    }

    /**
     * Cast value based on type.
     */
    protected static function castValue(mixed $value, string $type): mixed
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
