<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BusinessSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return Auth::check() && $user && method_exists($user, 'hasPermission') && $user->hasPermission('manage_business_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => 'required|string|max:255',
            'business_name_ar' => 'required|string|max:255',
            'currency_symbol' => 'required|string|max:10',
            'default_profit_percent' => 'required|numeric|min:0|max:100',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'business_name.required' => 'اسم العمل مطلوب',
            'business_name_ar.required' => 'اسم العمل بالعربية مطلوب',
            'currency_symbol.required' => 'رمز العملة مطلوب',
            'default_profit_percent.required' => 'نسبة الربح الافتراضية مطلوبة',
            'default_profit_percent.numeric' => 'نسبة الربح يجب أن تكون رقماً',
            'default_profit_percent.min' => 'نسبة الربح يجب أن تكون 0 أو أكثر',
            'default_profit_percent.max' => 'نسبة الربح يجب أن تكون 100 أو أقل',
            'timezone.required' => 'المنطقة الزمنية مطلوبة',
            'date_format.required' => 'تنسيق التاريخ مطلوب',
            'time_format.required' => 'تنسيق الوقت مطلوب',
            'logo.image' => 'الملف يجب أن يكون صورة',
            'logo.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
            'logo.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ];
    }
}
