@extends('layouts.app')

@section('title', 'تعديل العميل - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل العميل</h1>
        <p class="mt-1 text-sm text-gray-500">تحديث بيانات العميل: {{ $customer->name }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للعملاء
        </a>
        <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            عرض العميل
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">تحديث معلومات العميل</h3>
            <p class="mt-1 text-sm text-gray-500">قم بتحديث البيانات المطلوبة</p>
        </div>
        
        <form method="POST" action="{{ route('customers.update', $customer) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">المعلومات الشخصية</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم العميل <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $customer->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                               placeholder="أدخل اسم العميل الكامل"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الهاتف الرئيسي <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               value="{{ old('phone', $customer->phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror"
                               placeholder="05xxxxxxxx"
                               required>
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
                               value="{{ old('phone2', $customer->phone2) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone2') border-red-300 @enderror"
                               placeholder="05xxxxxxxx (اختياري)">
                        @error('phone2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                            الفرع
                        </label>
                        <select name="branch_id" 
                                id="branch_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('branch_id') border-red-300 @enderror">
                            <option value="">اختر الفرع</option>
                            @foreach(\App\Models\Branch::where('is_active', true)->get() as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $customer->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Location Information -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">معلومات الموقع</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            البلد <span class="text-red-500">*</span>
                        </label>
                        <select name="country" 
                                id="country"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-300 @enderror"
                                required>
                            <option value="">اختر البلد</option>
                            <option value="مصر" {{ old('country', $customer->country) == 'مصر' ? 'selected' : '' }}>مصر</option>
                            <option value="السعودية" {{ old('country', $customer->country) == 'السعودية' ? 'selected' : '' }}>السعودية</option>
                            <option value="الإمارات" {{ old('country', $customer->country) == 'الإمارات' ? 'selected' : '' }}>الإمارات</option>
                            <option value="الكويت" {{ old('country', $customer->country) == 'الكويت' ? 'selected' : '' }}>الكويت</option>
                            <option value="قطر" {{ old('country', $customer->country) == 'قطر' ? 'selected' : '' }}>قطر</option>
                            <option value="البحرين" {{ old('country', $customer->country) == 'البحرين' ? 'selected' : '' }}>البحرين</option>
                            <option value="عمان" {{ old('country', $customer->country) == 'عمان' ? 'selected' : '' }}>عمان</option>
                            <option value="الأردن" {{ old('country', $customer->country) == 'الأردن' ? 'selected' : '' }}>الأردن</option>
                            <option value="لبنان" {{ old('country', $customer->country) == 'لبنان' ? 'selected' : '' }}>لبنان</option>
                            <option value="سوريا" {{ old('country', $customer->country) == 'سوريا' ? 'selected' : '' }}>سوريا</option>
                            <option value="العراق" {{ old('country', $customer->country) == 'العراق' ? 'selected' : '' }}>العراق</option>
                            <option value="فلسطين" {{ old('country', $customer->country) == 'فلسطين' ? 'selected' : '' }}>فلسطين</option>
                            <option value="اليمن" {{ old('country', $customer->country) == 'اليمن' ? 'selected' : '' }}>اليمن</option>
                        </select>
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="governorate" class="block text-sm font-medium text-gray-700 mb-2">
                            المحافظة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="governorate" 
                               id="governorate" 
                               value="{{ old('governorate', $customer->governorate) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('governorate') border-red-300 @enderror"
                               placeholder="أدخل اسم المحافظة"
                               required>
                        @error('governorate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        العنوان التفصيلي
                    </label>
                    <textarea name="address" 
                              id="address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-300 @enderror"
                              placeholder="أدخل العنوان التفصيلي للعميل">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Additional Information -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">معلومات إضافية</h4>
                <div class="space-y-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-300 @enderror"
                                  placeholder="أدخل أي ملاحظات إضافية عن العميل">{{ old('notes', $customer->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               value="1"
                               {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="mr-2 block text-sm text-gray-900">
                            العميل نشط
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 rtl:space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('customers.index') }}" 
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
                    تحديث العميل
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-format phone numbers
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0 && !value.startsWith('05')) {
            value = '05' + value;
        }
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        e.target.value = value;
    });
    
    document.getElementById('phone2').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0 && !value.startsWith('05')) {
            value = '05' + value;
        }
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        e.target.value = value;
    });
</script>
@endsection