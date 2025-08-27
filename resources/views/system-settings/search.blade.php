@extends('layouts.app')

@section('title', 'البحث في الإعدادات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">البحث في الإعدادات</h1>
                <p class="text-gray-600">ابحث عن إعدادات محددة في النظام</p>
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

    <!-- نموذج البحث -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('system-settings.search') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="q" 
                       value="{{ $query }}" 
                       placeholder="ابحث في الإعدادات..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-search ml-2"></i>
                بحث
            </button>
        </form>
    </div>

    <!-- نتائج البحث -->
    @if($query)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    نتائج البحث عن "{{ $query }}"
                </h2>
                <p class="text-gray-600">{{ count($settings) }} نتيجة</p>
            </div>

            @if($settings && count($settings) > 0)
                <div class="space-y-4">
                    @foreach($settings as $key => $setting)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $key }}</h3>
                                    @if(isset($setting['description']))
                                        <p class="text-gray-600 mb-3">{{ $setting['description'] }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">القيمة:</span>
                                            <span class="text-gray-900 ml-2">{{ $setting['value'] ?? 'غير محدد' }}</span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">النوع:</span>
                                            <span class="text-gray-900 ml-2">{{ $setting['type'] ?? 'string' }}</span>
                                        </div>
                                        @if(isset($setting['category']))
                                            <div>
                                                <span class="font-medium text-gray-700">الفئة:</span>
                                                <span class="text-gray-900 ml-2">{{ \App\Helpers\SystemHelper::getCategoryName($setting['category']) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    @if($setting['is_editable'])
                                        <button type="button" 
                                                onclick="editSetting('{{ $key }}', '{{ $setting['value'] ?? '' }}')"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="fas fa-edit ml-1"></i>
                                            تعديل
                                        </button>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-lock ml-1"></i>
                                            للقراءة فقط
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد نتائج</h3>
                    <p class="text-gray-500">لم يتم العثور على إعدادات تطابق البحث "{{ $query }}".</p>
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Modal تعديل الإعداد -->
<div id="editSettingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">تعديل الإعداد</h3>
            <form id="editSettingForm" class="space-y-4">
                <div>
                    <label for="settingKey" class="block text-sm font-medium text-gray-700 mb-2">المفتاح</label>
                    <input type="text" id="settingKey" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                <div>
                    <label for="settingValue" class="block text-sm font-medium text-gray-700 mb-2">القيمة</label>
                    <input type="text" id="settingValue" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" 
                            onclick="closeEditModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editSetting(key, value) {
    document.getElementById('settingKey').value = key;
    document.getElementById('settingValue').value = value;
    document.getElementById('editSettingModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editSettingModal').classList.add('hidden');
}

document.getElementById('editSettingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const key = document.getElementById('settingKey').value;
    const value = document.getElementById('settingValue').value;
    
    // إرسال طلب AJAX لتحديث الإعداد
    fetch('{{ route("system-settings.update-single") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ key, value })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إغلاق Modal وإعادة تحميل الصفحة
            closeEditModal();
            location.reload();
        } else {
            alert('خطأ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الإعداد');
    });
});

// إغلاق Modal عند النقر خارجه
document.getElementById('editSettingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endpush
