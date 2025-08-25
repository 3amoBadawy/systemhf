<?php

namespace App\Helpers;

use App\Services\ConfigurationService;

class ValidationHelper
{
    /**
     * قواعد التحقق للصور فقط
     */
    public static function getImageValidationRules($branchId = null): string
    {
        $maxSize = ConfigurationService::get('max_file_size', 10240, $branchId);
        $allowedTypes = ConfigurationService::get('allowed_image_types', ['jpg', 'jpeg', 'png', 'gif', 'webp'], $branchId);

        return 'nullable|image|mimes:'.implode(',', $allowedTypes).'|max:'.$maxSize;
    }

    /**
     * قواعد التحقق للفيديوهات فقط
     */
    public static function getVideoValidationRules($branchId = null): string
    {
        $maxSize = ConfigurationService::get('max_video_size', 51200, $branchId); // حجم أكبر للفيديوهات
        $allowedTypes = ConfigurationService::get('allowed_video_types', ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'], $branchId);

        return 'nullable|file|mimes:'.implode(',', $allowedTypes).'|max:'.$maxSize;
    }

    /**
     * قواعد التحقق للنصوص
     *
     * @psalm-param 'description'|'normal' $type
     */
    public static function getTextValidationRules(string $type = 'normal', $branchId = null): string
    {
        $limits = [
            'short' => ConfigurationService::get('max_short_text_length', 255, $branchId),
            'normal' => ConfigurationService::get('max_normal_text_length', 500, $branchId),
            'long' => ConfigurationService::get('max_long_text_length', 1000, $branchId),
            'description' => ConfigurationService::get('max_description_length', 2000, $branchId),
        ];

        return 'string|max:'.($limits[$type] ?? $limits['normal']);
    }

    /**
     * قواعد التحقق للأرقام
     *
     * @psalm-param 'positive'|'price' $type
     */
    public static function getNumericValidationRules(string $type = 'positive', $branchId = null): string
    {
        switch ($type) {
            case 'positive':
                return 'numeric|min:0';
            case 'percentage':
                return 'numeric|min:0|max:100';
            case 'quantity':
                $maxQuantity = ConfigurationService::get('max_quantity_limit', 10000, $branchId);

                return 'integer|min:1|max:'.$maxQuantity;
            case 'price':
                $maxPrice = ConfigurationService::get('max_price_limit', 1000000, $branchId);

                return 'numeric|min:0|max:'.$maxPrice;
            default:
                return 'numeric|min:0';
        }
    }

    /**
     * قواعد التحقق للبريد الإلكتروني
     *
     * @return string[]
     *
     * @psalm-return list{0: 'email', 1?: string, 2?: string}
     */
    public static function getEmailValidationRules($unique = false, $table = null, $column = 'email', $ignore = null): array
    {
        $rules = ['email'];

        $maxLength = ConfigurationService::get('max_email_length', 255);
        if ($maxLength) {
            $rules[] = 'max:'.$maxLength;
        }

        if ($unique && $table) {
            $uniqueRule = 'unique:'.$table.','.$column;
            if ($ignore) {
                $uniqueRule .= ','.$ignore;
            }
            $rules[] = $uniqueRule;
        }

        return $rules;
    }

    /**
     * قواعد التحقق للهواتف
     *
     * @return string[]
     *
     * @psalm-return list{0: 'string', 1: string, 2: string, 3?: string}
     */
    public static function getPhoneValidationRules($branchId = null): array
    {
        $minLength = ConfigurationService::get('phone_min_length', 10, $branchId);
        $maxLength = ConfigurationService::get('phone_max_length', 20, $branchId);
        $pattern = ConfigurationService::get('phone_validation_pattern', null, $branchId);

        $rules = ['string', 'min:'.$minLength, 'max:'.$maxLength];

        if ($pattern) {
            $rules[] = 'regex:'.$pattern;
        }

        return $rules;
    }

    /**
     * قواعد التحقق المخصصة حسب النوع
     */
    public static function getCustomValidationRules($type, $options = [], $branchId = null)
    {
        // يمكن إضافة قواعد مخصصة هنا حسب الحاجة
        $customRules = ConfigurationService::get('custom_validation_rules', [], $branchId);

        if (isset($customRules[$type])) {
            return $customRules[$type];
        }

        // القواعد الافتراضية
        return ['required', 'string'];
    }
}
