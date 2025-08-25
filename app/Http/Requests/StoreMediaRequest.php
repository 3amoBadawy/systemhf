<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    /**
     * Get custom messages for validator errors.
     */
    #[\Override]
    public function messages(): array
    {
        return [
            'files.required' => 'يجب اختيار ملف واحد على الأقل',
            'files.max' => 'يمكن رفع 20 ملف كحد أقصى في المرة الواحدة',
            'files.*.required' => 'جميع الملفات مطلوبة',
            'files.*.file' => 'يجب أن يكون الملف صالحاً',
            'files.*.mimes' => 'صيغ الملفات المدعومة: jpeg, png, jpg, gif, webp, mp4, avi, mov, pdf, doc, docx',
            'files.*.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
            'alt_text.max' => 'النص البديل يجب أن يكون أقل من 255 حرف',
            'caption.max' => 'التعليق يجب أن يكون أقل من 500 حرف',
            'description.max' => 'الوصف يجب أن يكون أقل من 1000 حرف',
            'mediaable_type.in' => 'نوع الكائن غير مدعوم',
            'mediaable_id.exists' => 'الكائن المحدد غير موجود',
            'order.min' => 'الترتيب يجب أن يكون 0 أو أكثر',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    #[\Override]
    public function attributes(): array
    {
        return [
            'files' => 'الملفات',
            'alt_text' => 'النص البديل',
            'caption' => 'التعليق',
            'description' => 'الوصف',
            'is_public' => 'عام',
            'is_featured' => 'مميز',
            'mediaable_type' => 'نوع الكائن',
            'mediaable_id' => 'معرف الكائن',
            'order' => 'الترتيب',
        ];
    }

    /**
     * الحصول على اسم الجدول حسب نوع الكائن
     */
    private function getTableName(): string
    {
        $type = $this->input('mediaable_type');

        switch ($type) {
            case 'App\Models\Product':
                return 'products';
            case 'App\Models\Category':
                return 'categories';
            case 'App\Models\Supplier':
                return 'suppliers';
            default:
                return 'products';
        }
    }
}
