@extends('layouts.app')

@section('title', 'إعدادات النظام')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إعدادات النظام</h1>
                <p class="text-gray-600">إدارة جميع إعدادات النظام والتكوين</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('system-settings.advanced') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-tools ml-2"></i>
                    الأدوات المتقدمة
                </a>
            </div>
        </div>
    </div>

    <!-- رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- شريط الأدوات -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4 space-x-reverse">
                <button onclick="resetAllSettings()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-undo ml-2"></i>
                    إعادة تعيين للافتراضي
                </button>
                
                <button onclick="exportSettings()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download ml-2"></i>
                    تصدير الإعدادات
                </button>
                
                <button onclick="showImportModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-upload ml-2"></i>
                    استيراد الإعدادات
                </button>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <input type="text" id="searchSettings" placeholder="البحث في الإعدادات..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button onclick="clearCache()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-broom ml-2"></i>
                    مسح الكاش
                </button>
            </div>
        </div>
    </div>

    <!-- تبويبات الإعدادات -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Debug Test Button -->
        <div class="p-4 bg-yellow-100 border-b border-yellow-200">
            <button onclick="testShowCategory()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">
                🧪 Test Tab Switching (Debug)
            </button>
            <span class="ml-4 text-sm text-gray-600">Click this button to test if showCategory function works</span>
        </div>
        
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 space-x-reverse px-6" aria-label="Tabs">
                <!-- تبويب إعدادات الأعمال -->
                <button onclick="showCategory('business')" 
                        class="category-tab py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600"
                        data-category="business">
                    إعدادات الأعمال
                    <span class="ml-2 bg-blue-100 text-blue-900 py-0.5 px-2.5 rounded-full text-xs font-medium">
                        أساسية
                    </span>
                </button>

                @if($settingsByCategory && $settingsByCategory->count() > 0)
                    @php $firstCategory = true; @endphp
                    @foreach($settingsByCategory as $category => $settings)
                        <button onclick="showCategory('{{ $category }}')" 
                                class="category-tab py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $firstCategory ? 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                data-category="{{ $category }}">
                            {{ \App\Helpers\SystemHelper::getCategoryName($category) }}
                            <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs font-medium">
                                {{ $settings->count() }}
                            </span>
                        </button>
                        @php $firstCategory = false; @endphp
                    @endforeach
                @endif
            </nav>
        </div>

        <!-- محتوى التبويبات -->
        <div class="p-6">
            <!-- تبويب إعدادات الأعمال -->
            <div id="category-business" class="category-content">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">إعدادات الأعمال</h3>
                    <p class="text-gray-600">إعدادات أساسية للأعمال والشعار والمعلومات العامة</p>
                </div>

                <form action="{{ route('system-settings.update-business') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- معلومات الأعمال الأساسية -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">اسم الأعمال (عربي)</label>
                            <input type="text" name="business_name_ar" value="{{ $businessSettingsModel->business_name_ar ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">اسم الأعمال (إنجليزي)</label>
                            <input type="text" name="business_name" value="{{ $businessSettingsModel->business_name ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ $businessSettingsModel->phone ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ $businessSettingsModel->email ?? '' }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 mb-2">العنوان</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $businessSettingsModel->address ?? '' }}</textarea>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 mb-2">الوصف</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $businessSettingsModel->description ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- إعدادات العملة -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">إعدادات العملة</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">العملة</label>
                                <select name="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                                                    @foreach($currencies as $code => $currency)
                                    <option value="{{ $code }}" {{ ($businessSettingsModel->currency ?? '') == $code ? 'selected' : '' }}>
                                        {{ $currency['name'] }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">رمز العملة</label>
                                <input type="text" name="currency_symbol" value="{{ $businessSettingsModel->currency_symbol ?? '' }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">موضع رمز العملة</label>
                                <select name="currency_symbol_placement" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="before" {{ ($businessSettingsModel->currency_symbol_placement ?? '') == 'before' ? 'selected' : '' }}>قبل الرقم</option>
                                    <option value="after" {{ ($businessSettingsModel->currency_symbol_placement ?? '') == 'after' ? 'selected' : '' }}>بعد الرقم</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- إعدادات الوقت والتاريخ -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">إعدادات الوقت والتاريخ</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">المنطقة الزمنية</label>
                                <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ ($businessSettingsModel->timezone ?? '') == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">تنسيق التاريخ</label>
                                <select name="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach($dateFormats as $format => $example)
                                        <option value="{{ $format }}" {{ ($businessSettingsModel->date_format ?? '') == $format ? 'selected' : '' }}>
                                            {{ $example }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-900 mb-2">تنسيق الوقت</label>
                                <select name="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach($timeFormats as $format => $example)
                                        <option value="{{ $format }}" {{ ($businessSettingsModel->time_format ?? '') == $format ? 'selected' : '' }}>
                                            {{ $example }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- الشعار -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">شعار الأعمال</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($businessSettingsModel->logo)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($businessSettingsModel->logo) }}" alt="شعار الأعمال" class="h-20 w-auto">
                                </div>
                            @endif
                            
                            <div class="flex items-center space-x-4 space-x-reverse">
                                <input type="file" name="logo" accept="image/*" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                
                                @if($businessSettingsModel->logo)
                                    <a href="{{ route('system-settings.remove-logo') }}" 
                                       onclick="return confirm('هل أنت متأكد من حذف الشعار؟')"
                                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                        حذف الشعار
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- إعدادات الربح -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">إعدادات الربح</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">نسبة الربح الافتراضية (%)</label>
                            <input type="number" name="default_profit_percent" value="{{ $businessSettingsModel->default_profit_percent ?? 20 }}" 
                                   min="0" max="100" step="0.1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save ml-2"></i>
                            حفظ إعدادات الأعمال
                        </button>
                    </div>
                </form>
            </div>

            @if($settingsByCategory && $settingsByCategory->count() > 0)
                @php $firstContent = false; @endphp
                @foreach($settingsByCategory as $category => $settings)
                    <div id="category-{{ $category }}" class="category-content hidden">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ \App\Helpers\SystemHelper::getCategoryName($category) }}</h3>
                            <p class="text-gray-600">{{ \App\Helpers\SystemHelper::getCategoryDescription($category) }}</p>
                        </div>

                        <form action="{{ route('system-settings.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @if($settings && $settings->count() > 0)
                                @foreach($settings as $setting)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-900 mb-2">
                                                    {{ $setting['description'] ?? $setting['key'] }}
                                                    @if(isset($setting['requires_restart']) && $setting['requires_restart'] == true)
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            يتطلب إعادة تشغيل
                                                        </span>
                                                    @endif
                                                </label>
                                                
                                                @if(isset($setting['description']) && $setting['description'])
                                                    <p class="text-sm text-gray-600 mb-3">{{ $setting['description'] }}</p>
                                                @endif

                                                <!-- حقل الإدخال حسب النوع -->
                                                @switch($setting['type'] ?? 'string')
                                                    @case('boolean')
                                                    @case('bool')
                                                        <div class="flex items-center">
                                                            <input type="checkbox" 
                                                                   name="settings[{{ $setting['key'] }}][value]" 
                                                                   value="1" 
                                                                   {{ ($setting['value'] == '1' || $setting['value'] == 'true' || $setting['value'] == 1 || $setting['value'] === true) ? 'checked' : '' }}
                                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                            <span class="mr-2 text-sm text-gray-700">تفعيل</span>
                                                        </div>
                                                        @break

                                                    @case('integer')
                                                    @case('decimal')
                                                    @case('number')
                                                    @case('float')
                                                        <input type="number" 
                                                               name="settings[{{ $setting['key'] }}][value]" 
                                                               value="{{ $setting['value'] ?? '' }}" 
                                                               step="{{ in_array($setting['type'] ?? 'string', ['decimal', 'float', 'number']) ? '0.01' : '1' }}"
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                        @break

                                                    @case('json')
                                                    @case('array')
                                                        <textarea name="settings[{{ $setting['key'] }}][value]" 
                                                                  rows="3"
                                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                                  placeholder="أدخل البيانات بصيغة JSON">{{ $setting['value'] ?? '' }}</textarea>
                                                        @break

                                                    @case('text')
                                                    @case('string')
                                                    @default
                                                        <input type="text" 
                                                               name="settings[{{ $setting['key'] }}][value]" 
                                                               value="{{ $setting['value'] ?? '' }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                @endswitch

                                                <input type="hidden" name="settings[{{ $setting['key'] }}][key]" value="{{ $setting['key'] }}">
                                            </div>

                                            <div class="mr-4 text-right">
                                                <div class="text-xs text-gray-500 mb-1">{{ $setting['key'] ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-400">{{ $setting['type'] ?? 'string' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-save ml-2"></i>
                                        حفظ الإعدادات
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500">لا توجد إعدادات قابلة للتحرير في هذه الفئة.</p>
                                </div>
                            @endif
                        </form>
                    </div>
                    @php $firstContent = false; @endphp
                @endforeach
            @else
                <!-- رسالة عندما لا توجد إعدادات نظام -->
                <div class="text-center py-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد إعدادات نظام</h3>
                        <p class="mt-1 text-sm text-gray-500">لم يتم العثور على إعدادات نظام قابلة للتحرير.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal استيراد الإعدادات -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-4">استيراد الإعدادات</h3>
            <form action="{{ route('system-settings.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <input type="file" name="settings_file" accept=".json" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="hideImportModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        استيراد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// إظهار/إخفاء التبويبات
function showCategory(category) {
    console.log('showCategory called with:', category);
    
    // إخفاء جميع المحتويات
    const allContents = document.querySelectorAll('.category-content');
    console.log('Found category-content elements:', allContents.length);
    allContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // إزالة التفعيل من جميع التبويبات
    const allTabs = document.querySelectorAll('.category-tab');
    console.log('Found category-tab elements:', allTabs.length);
    allTabs.forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // إظهار المحتوى المطلوب
    const targetContent = document.getElementById('category-' + category);
    console.log('Target content element:', targetContent);
    if (targetContent) {
        targetContent.classList.remove('hidden');
        console.log('Removed hidden class from target content');
    } else {
        console.error('Target content element not found for category:', category);
    }
    
    // تفعيل التبويب المطلوب
    const activeTab = document.querySelector(`[data-category="${category}"]`);
    console.log('Active tab element:', activeTab);
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-blue-500', 'text-blue-600');
        console.log('Updated active tab classes');
    } else {
        console.error('Active tab element not found for category:', category);
    }
}

// البحث في الإعدادات
document.getElementById('searchSettings').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const settings = document.querySelectorAll('.bg-gray-50');
    
    settings.forEach(setting => {
        const text = setting.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            setting.style.display = '';
        } else {
            setting.style.display = 'none';
        }
    });
});

// إعادة تعيين جميع الإعدادات
function resetAllSettings() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع الإعدادات للقيم الافتراضية؟')) {
        window.location.href = '{{ route("system-settings.reset") }}';
    }
}

// تصدير الإعدادات
function exportSettings() {
    window.location.href = '{{ route("system-settings.export") }}';
}

// إظهار modal الاستيراد
function showImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

// إخفاء modal الاستيراد
function hideImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

// مسح الكاش
function clearCache() {
    if (confirm('هل أنت متأكد من مسح الكاش؟')) {
        fetch('{{ route("system-settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم مسح الكاش بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء مسح الكاش');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء مسح الكاش');
        });
    }
}

// تفعيل التبويب الأول عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event fired');
    
    // Debug: Check all category content elements
    const allCategoryContents = document.querySelectorAll('.category-content');
    console.log('All category-content elements found:', allCategoryContents.length);
    allCategoryContents.forEach((content, index) => {
        console.log(`Category content ${index}:`, content.id, 'hidden:', content.classList.contains('hidden'));
    });
    
    // Debug: Check all category tabs
    const allCategoryTabs = document.querySelectorAll('.category-tab');
    console.log('All category-tab elements found:', allCategoryTabs.length);
    allCategoryTabs.forEach((tab, index) => {
        console.log(`Category tab ${index}:`, tab.getAttribute('data-category'), 'onclick:', tab.getAttribute('onclick'));
    });
    
    const firstCategory = '{{ $settingsByCategory->keys()->first() ?? "general" }}';
    console.log('First category from PHP:', firstCategory);
    console.log('Calling showCategory with:', firstCategory);
    
    // Ensure the first category is shown
    showCategory(firstCategory);
    
    // Debug: Check final state
    setTimeout(() => {
        console.log('Final state after showCategory:');
        allCategoryContents.forEach((content, index) => {
            console.log(`Category content ${index}:`, content.id, 'hidden:', content.classList.contains('hidden'));
        });
    }, 100);
});

// Debug function for testing tab switching
function testShowCategory() {
    const testCategory = 'business'; // Change this to test other categories
    console.log('Testing showCategory with:', testCategory);
    showCategory(testCategory);
}
</script>
@endpush
@endsection
