<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaRequest extends FormRequest
{
    /**
     * Get custom messages for validator errors.
     */
    #[\Override]
    public function messages(): array
    {
        return [
            'alt_text.max' => 'النص البديل يجب أن يكون أقل من 255 حرف',
            'caption.max' => 'التعليق يجب أن يكون أقل من 500 حرف',
            'description.max' => 'الوصف يجب أن يكون أقل من 1000 حرف',
            'order.min' => 'الترتيب يجب أن يكون 0 أو أكثر',
            'metadata.title.max' => 'عنوان البيانات الوصفية يجب أن يكون أقل من 255 حرف',
            'metadata.keywords.max' => 'الكلمات المفتاحية يجب أن تكون أقل من 500 حرف',
            'metadata.copyright.max' => 'حقوق النشر يجب أن تكون أقل من 255 حرف',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    #[\Override]
    public function attributes(): array
    {
        return [
            'alt_text' => 'النص البديل',
            'caption' => 'التعليق',
            'description' => 'الوصف',
            'is_public' => 'عام',
            'is_featured' => 'مميز',
            'order' => 'الترتيب',
            'metadata' => 'البيانات الوصفية',
            'metadata.title' => 'العنوان',
            'metadata.keywords' => 'الكلمات المفتاحية',
            'metadata.copyright' => 'حقوق النشر',
        ];
    }
}
