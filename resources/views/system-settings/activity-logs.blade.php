@extends('layouts.app')

@section('title', 'سجلات النشاط')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">سجلات النشاط</h1>
                <p class="text-gray-600">عرض سجلات نشاط النظام والعمليات</p>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('system-settings.export-logs') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download ml-2"></i>
                    تصدير السجلات
                </a>
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

    <!-- سجلات النشاط -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if($logs && count($logs) > 0)
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    آخر {{ count($logs) }} سجل نشاط
                </h2>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <button onclick="refreshLogs()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                        <i class="fas fa-sync-alt ml-1"></i>
                        تحديث
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الوقت
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المستوى
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                السياق
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الرسالة
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log['timestamp'] ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if(($log['level'] ?? '') === 'ERROR') bg-red-100 text-red-800
                                        @elseif(($log['level'] ?? '') === 'WARNING') bg-yellow-100 text-yellow-800
                                        @elseif(($log['level'] ?? '') === 'INFO') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $log['level'] ?? 'UNKNOWN' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log['context'] ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-md truncate">
                                    {{ $log['message'] ?? 'رسالة فارغة' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    عرض {{ count($logs) }} من {{ count($logs) }} سجل
                </div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <button onclick="loadMoreLogs()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus ml-2"></i>
                        تحميل المزيد
                    </button>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد سجلات</h3>
                <p class="text-gray-500">لا توجد سجلات نشاط متاحة حالياً.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshLogs() {
    location.reload();
}

function loadMoreLogs() {
    // يمكن إضافة منطق لتحميل المزيد من السجلات هنا
    alert('ميزة تحميل المزيد من السجلات ستكون متاحة قريباً');
}

// تحديث تلقائي كل 30 ثانية
setInterval(function() {
    // يمكن إضافة منطق للتحديث التلقائي هنا
}, 30000);
</script>
@endpush
