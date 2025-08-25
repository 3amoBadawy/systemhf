@extends('layouts.app')

@section('title', 'إضافة مورد جديد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة مورد جديد</h1>
        <p class="mt-1 text-sm text-gray-500">تسجيل مورد جديد في النظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للموردين
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">معلومات المورد</h3>
            <p class="mt-1 text-sm text-gray-500">أدخل بيانات المورد الجديد</p>
        </div>
        
        <form method="POST" action="{{ route('suppliers.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- اسم المورد -->
            <div>
                <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                    اسم المورد <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name_ar" 
                       id="name_ar" 
                       value="{{ old('name_ar') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name_ar') border-red-300 @enderror"
                       placeholder="أدخل اسم المورد"
                       required>
                @error('name_ar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- أرقام الهاتف -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        رقم الهاتف الرئيسي
                    </label>
                    <input type="tel" 
                           name="phone" 
                           id="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror"
                           placeholder="05xxxxxxxx">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone2" class="block text-sm font-medium text-gray-700 mb-2">
                        رقم الهاتف الثاني
                    </label>
                    <input type="tel" 
                           name="phone2" 
                           id="phone2" 
                           value="{{ old('phone2') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone2') border-red-300 @enderror"
                           placeholder="05xxxxxxxx (اختياري)">
                    @error('phone2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- البريد الإلكتروني -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    البريد الإلكتروني
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                       placeholder="supplier@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- المحافظة -->
            <div>
                <label for="governorate" class="block text-sm font-medium text-gray-700 mb-2">
                    المحافظة
                </label>
                <input type="text" 
                       name="governorate" 
                       id="governorate" 
                       value="{{ old('governorate') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('governorate') border-red-300 @enderror"
                       placeholder="القاهرة">
                @error('governorate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- العنوان -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    العنوان التفصيلي
                </label>
                <textarea name="address" 
                          id="address" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-300 @enderror"
                          placeholder="أدخل العنوان التفصيلي للمورد">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- ملاحظات -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    ملاحظات
                </label>
                <textarea name="notes" 
                          id="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror"
                          placeholder="أدخل أي ملاحظات إضافية عن المورد">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- الحالة -->
            <div>
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="mr-2 block text-sm text-gray-900">
                        المورد نشط
                    </label>
                </div>
            </div>
            
            <!-- أزرار النموذج -->
            <div class="flex items-center justify-end space-x-3 rtl:space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('suppliers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    إلغاء
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ المورد
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تنسيق رقم الهاتف
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('05')) {
                    value = value.substring(0, 10);
                } else if (value.startsWith('5')) {
                    value = '0' + value.substring(0, 9);
                }
                e.target.value = value;
            }
        });
    });
});
</script>
@endpush
@endsection