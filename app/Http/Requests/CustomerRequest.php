<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    #[\Override]
    public function messages(): array
    {
        return [
            'name.required' => 'اسم العميل مطلوب',
            'name.max' => 'اسم العميل يجب أن لا يتجاوز 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'phone.max' => 'رقم الهاتف يجب أن لا يتجاوز 20 رقم',
            'country.required' => 'البلد مطلوب',
            'governorate.required' => 'المحافظة مطلوبة',
            'branch_id.exists' => 'الفرع المحدد غير موجود',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    #[\Override]
    public function attributes(): array
    {
        return [
            'name' => 'اسم العميل',
            'phone' => 'رقم الهاتف',
            'phone2' => 'رقم الهاتف الثاني',
            'country' => 'البلد',
            'governorate' => 'المحافظة',
            'address' => 'العنوان',
            'notes' => 'الملاحظات',
            'is_active' => 'الحالة',
            'branch_id' => 'الفرع',
        ];
    }
}
