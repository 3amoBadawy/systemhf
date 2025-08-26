<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * قواعد التحقق للصور فقط
     */
    public static function getImageValidationRules(): string
    {
        $maxSize = 10240; // Default max file size
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Default allowed types

        return 'nullable|image|mimes:'.implode(',', $allowedTypes).'|max:'.$maxSize;
    }

    /**
     * قواعد التحقق للفيديوهات فقط
     */
    public static function getVideoValidationRules(): string
    {
        $maxSize = 51200; // Default max video size
        $allowedTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm']; // Default allowed types

        return 'nullable|file|mimes:'.implode(',', $allowedTypes).'|max:'.$maxSize;
    }

    /**
     * قواعد التحقق للنصوص
     *
     * @psalm-param 'description'|'normal' $type
     */
    public static function getTextValidationRules(string $type = 'normal'): string
    {
        $limits = [
            'short' => 255,
            'normal' => 500,
            'long' => 1000,
            'description' => 2000,
        ];

        return 'string|max:'.($limits[$type] ?? $limits['normal']);
    }

    /**
     * قواعد التحقق للأرقام
     *
     * @psalm-param 'positive'|'price' $type
     */
    public static function getNumericValidationRules(string $type = 'positive'): string
    {
        switch ($type) {
            case 'positive':
                return 'numeric|min:0';
            case 'percentage':
                return 'numeric|min:0|max:100';
            case 'quantity':
                $maxQuantity = 10000; // Default max quantity limit

                return 'integer|min:1|max:'.$maxQuantity;
            case 'price':
                $maxPrice = 1000000; // Default max price limit

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
    public static function getEmailValidationRules(): array
    {
        $rules = ['email'];

        $maxLength = 255; // Default max email length
        $rules[] = 'max:'.$maxLength;

        return $rules;
    }

    /**
     * قواعد التحقق للبريد الإلكتروني مع فحص التكرار
     */
    public static function getUniqueEmailValidationRules(string $table, string $column = 'email', ?string $ignore = null): array
    {
        $rules = self::getEmailValidationRules();

        $uniqueRule = 'unique:'.$table.','.$column;
        if ($ignore) {
            $uniqueRule .= ','.$ignore;
        }
        $rules[] = $uniqueRule;

        return $rules;
    }

    /**
     * قواعد التحقق للهواتف
     *
     * @return string[]
     *
     * @psalm-return list{0: 'string', 1: string, 2: string, 3?: string}
     */
    public static function getPhoneValidationRules(): array
    {
        $minLength = 10; // Default min phone length
        $maxLength = 20; // Default max phone length

        $rules = ['string', 'min:'.$minLength, 'max:'.$maxLength];

        return $rules;
    }

    /**
     * قواعد التحقق المخصصة حسب النوع
     */
    public static function getCustomValidationRules(string $type): array
    {
        // يمكن إضافة قواعد مخصصة هنا حسب الحاجة
        $customRules = [
            'user' => ['required', 'string', 'min:3'],
            'product' => ['required', 'string', 'min:2'],
            'category' => ['required', 'string', 'min:2'],
        ];

        if (array_key_exists($type, $customRules)) {
            return $customRules[$type];
        }

        // القواعد الافتراضية
        return ['required', 'string'];
    }
}
