@extends('layouts.app')

@section('title', 'إعدادات الفئة - ' . \App\Helpers\SystemHelper::getCategoryName($category))

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    إعدادات {{ \App\Helpers\SystemHelper::getCategoryName($category) }}
                </h1>
                <p class="text-gray-600">{{ \App\Helpers\SystemHelper::getCategoryDescription($category) }}</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('system-settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للإعدادات
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

    <!-- إعدادات الفئة -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if($settings && count($settings) > 0)
            <form action="{{ route('system-settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @foreach($settings as $key => $setting)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <label for="{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting['description'] ?? $key }}
                                </label>
                                @if(isset($setting['description']))
                                    <p class="text-sm text-gray-500 mb-3">{{ $setting['description'] }}</p>
                                @endif
                                
                                @if($setting['type'] === 'boolean')
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="{{ $key }}" 
                                               name="settings[{{ $key }}][value]" 
                                               value="1"
                                               {{ $setting['value'] ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="{{ $key }}" class="mr-2 text-sm text-gray-700">
                                            {{ $setting['value'] ? 'مفعل' : 'معطل' }}
                                        </label>
                                    </div>
                                @elseif($setting['type'] === 'select')
                                    <select id="{{ $key }}" 
                                            name="settings[{{ $key }}][value]" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        @foreach($setting['options'] ?? [] as $optionValue => $optionLabel)
                                            <option value="{{ $optionValue }}" {{ $setting['value'] == $optionValue ? 'selected' : '' }}>
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                @elseif($setting['type'] === 'textarea')
                                    <textarea id="{{ $key }}" 
                                              name="settings[{{ $key }}][value]" 
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="أدخل القيمة...">{{ $setting['value'] }}</textarea>
                                @else
                                    <input type="text" 
                                           id="{{ $key }}" 
                                           name="settings[{{ $key }}][value]" 
                                           value="{{ $setting['value'] }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="أدخل القيمة...">
                                @endif
                                
                                <input type="hidden" name="settings[{{ $key }}][key]" value="{{ $key }}">
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                @if($setting['requires_restart'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                        يتطلب إعادة تشغيل
                                    </span>
                                @endif
                                
                                @if(!$setting['is_editable'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-lock ml-1"></i>
                                        للقراءة فقط
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="flex justify-end pt-6">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <i class="fas fa-cog text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد إعدادات</h3>
                <p class="text-gray-500">لا توجد إعدادات متاحة لهذه الفئة.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle boolean checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            label.textContent = this.checked ? 'مفعل' : 'معطل';
        });
    });
});
</script>
@endpush
