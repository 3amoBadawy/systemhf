@extends('layouts.app')

@section('title', 'الإعدادات المتقدمة')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">الإعدادات المتقدمة</h1>
        <p class="text-gray-600">معلومات النظام والأداء والإعدادات المتقدمة</p>
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

    <!-- معلومات النظام -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- معلومات النظام الأساسية -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات النظام</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">إصدار PHP:</span>
                    <span class="font-medium">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">إصدار Laravel:</span>
                    <span class="font-medium">{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">حجم قاعدة البيانات:</span>
                    <span class="font-medium">{{ $systemInfo['database_size'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">مساحة التخزين المستخدمة:</span>
                    <span class="font-medium">{{ $systemInfo['storage_used'] }}</span>
                </div>
            </div>
        </div>

        <!-- معلومات الذاكرة والأداء -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الأداء والذاكرة</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">استخدام الذاكرة:</span>
                    <span class="font-medium">{{ $systemInfo['memory_usage'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">حد الذاكرة:</span>
                    <span class="font-medium">{{ $systemInfo['memory_limit'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الحد الأقصى لوقت التنفيذ:</span>
                    <span class="font-medium">{{ $systemInfo['max_execution_time'] }} ثانية</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الحد الأقصى لحجم الملف:</span>
                    <span class="font-medium">{{ $systemInfo['upload_max_filesize'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- إعدادات الكاش -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إعدادات الكاش</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($cacheInfo as $key => $value)
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600 mb-2">{{ $this->getCacheDriverName($key) }}</div>
                    <div class="font-medium text-gray-900">{{ $value }}</div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="clearAllCache()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-trash ml-2"></i>
                مسح جميع أنواع الكاش
            </button>
        </div>
    </div>

    <!-- أدوات النظام -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- إعادة تشغيل الخدمات -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">إعادة تشغيل الخدمات</h3>
            <div class="space-y-3">
                <button onclick="restartQueue()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sync ml-2"></i>
                    إعادة تشغيل قائمة الانتظار
                </button>
                <button onclick="restartCache()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-database ml-2"></i>
                    إعادة تشغيل الكاش
                </button>
                <button onclick="restartConfig()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-cog ml-2"></i>
                    إعادة تحميل التكوين
                </button>
            </div>
        </div>

        <!-- صيانة النظام -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">صيانة النظام</h3>
            <div class="space-y-3">
                <button onclick="toggleMaintenanceMode()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-tools ml-2"></i>
                    تبديل وضع الصيانة
                </button>
                <button onclick="optimizeDatabase()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-database ml-2"></i>
                    تحسين قاعدة البيانات
                </button>
                <button onclick="clearLogs()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-alt ml-2"></i>
                    مسح السجلات القديمة
                </button>
            </div>
        </div>
    </div>

    <!-- مراقبة الأداء -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">مراقبة الأداء</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600" id="cpuUsage">--</div>
                <div class="text-sm text-blue-800">استخدام المعالج</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600" id="memoryUsage">--</div>
                <div class="text-sm text-green-800">استخدام الذاكرة</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-600" id="diskUsage">--</div>
                <div class="text-sm text-purple-800">استخدام القرص</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600" id="activeUsers">--</div>
                <div class="text-sm text-red-800">المستخدمون النشطون</div>
            </div>
        </div>
        
        <div class="mt-6">
            <button onclick="refreshMetrics()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-sync ml-2"></i>
                تحديث المقاييس
            </button>
        </div>
    </div>

    <!-- سجل الأنشطة -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">آخر الأنشطة</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النشاط</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="activityLogs">
                    <!-- سيتم تحميل البيانات عبر AJAX -->
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 flex justify-between items-center">
            <button onclick="loadMoreActivities()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus ml-2"></i>
                تحميل المزيد
            </button>
            <button onclick="exportActivityLogs()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-download ml-2"></i>
                تصدير السجل
            </button>
        </div>
    </div>
</div>

<!-- Modal تأكيد العمليات -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4" id="confirmTitle">تأكيد العملية</h3>
            <p class="text-gray-600 mb-6" id="confirmMessage">هل أنت متأكد من تنفيذ هذه العملية؟</p>
            
            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="hideConfirmModal()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
                <button onclick="executeConfirmedAction()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    تأكيد
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentAction = null;

// إظهار modal التأكيد
function showConfirmModal(title, message, action) {
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    currentAction = action;
    document.getElementById('confirmModal').classList.remove('hidden');
}

// إخفاء modal التأكيد
function hideConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    currentAction = null;
}

// تنفيذ العملية المؤكدة
function executeConfirmedAction() {
    if (currentAction) {
        currentAction();
    }
    hideConfirmModal();
}

// مسح جميع أنواع الكاش
function clearAllCache() {
    showConfirmModal(
        'مسح الكاش',
        'هل أنت متأكد من مسح جميع أنواع الكاش؟ هذا قد يؤثر على الأداء مؤقتاً.',
        () => {
            window.location.href = '{{ route("system-settings.clear-cache") }}';
        }
    );
}

// إعادة تشغيل قائمة الانتظار
function restartQueue() {
    showConfirmModal(
        'إعادة تشغيل قائمة الانتظار',
        'هل تريد إعادة تشغيل قائمة الانتظار؟',
        () => {
            // تنفيذ العملية
            fetch('{{ route("system-settings.restart-queue") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إعادة تشغيل قائمة الانتظار بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء إعادة تشغيل قائمة الانتظار', 'error');
                }
            });
        }
    );
}

// إعادة تشغيل الكاش
function restartCache() {
    showConfirmModal(
        'إعادة تشغيل الكاش',
        'هل تريد إعادة تشغيل نظام الكاش؟',
        () => {
            fetch('{{ route("system-settings.restart-cache") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إعادة تشغيل الكاش بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء إعادة تشغيل الكاش', 'error');
                }
            });
        }
    );
}

// إعادة تحميل التكوين
function restartConfig() {
    showConfirmModal(
        'إعادة تحميل التكوين',
        'هل تريد إعادة تحميل إعدادات التكوين؟',
        () => {
            fetch('{{ route("system-settings.restart-config") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إعادة تحميل التكوين بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء إعادة تحميل التكوين', 'error');
                }
            });
        }
    );
}

// تبديل وضع الصيانة
function toggleMaintenanceMode() {
    showConfirmModal(
        'وضع الصيانة',
        'هل تريد تبديل وضع الصيانة؟',
        () => {
            fetch('{{ route("system-settings.toggle-maintenance") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم تبديل وضع الصيانة بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء تبديل وضع الصيانة', 'error');
                }
            });
        }
    );
}

// تحسين قاعدة البيانات
function optimizeDatabase() {
    showConfirmModal(
        'تحسين قاعدة البيانات',
        'هل تريد تحسين قاعدة البيانات؟ قد يستغرق هذا وقتاً.',
        () => {
            fetch('{{ route("system-settings.optimize-database") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم تحسين قاعدة البيانات بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء تحسين قاعدة البيانات', 'error');
                }
            });
        }
    );
}

// مسح السجلات القديمة
function clearLogs() {
    showConfirmModal(
        'مسح السجلات',
        'هل تريد مسح السجلات القديمة؟ لا يمكن التراجع عن هذه العملية.',
        () => {
            fetch('{{ route("system-settings.clear-logs") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم مسح السجلات القديمة بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ أثناء مسح السجلات', 'error');
                }
            });
        }
    );
}

// تحديث مقاييس الأداء
function refreshMetrics() {
    fetch('{{ route("system-settings.metrics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cpuUsage').textContent = data.metrics.cpu + '%';
                document.getElementById('memoryUsage').textContent = data.metrics.memory + '%';
                document.getElementById('diskUsage').textContent = data.metrics.disk + '%';
                document.getElementById('activeUsers').textContent = data.metrics.active_users;
            }
        });
}

// تحميل سجل الأنشطة
function loadActivityLogs() {
    fetch('{{ route("system-settings.activity-logs") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('activityLogs');
                tbody.innerHTML = '';
                
                data.logs.forEach(log => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${log.description}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.user_name || 'غير محدد'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.created_at}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.ip_address || 'غير محدد'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        });
}

// تحميل المزيد من الأنشطة
function loadMoreActivities() {
    // تنفيذ تحميل المزيد
    showNotification('جاري تحميل المزيد من الأنشطة...', 'info');
}

// تصدير سجل الأنشطة
function exportActivityLogs() {
    window.location.href = '{{ route("system-settings.export-logs") }}';
}

// إظهار الإشعارات
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// تحميل البيانات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadActivityLogs();
    refreshMetrics();
    
    // تحديث المقاييس كل دقيقة
    setInterval(refreshMetrics, 60000);
});
</script>
@endpush

@push('styles')
<style>
/* تحسينات للواجهة */
.bg-gray-50, .bg-blue-50, .bg-green-50, .bg-purple-50, .bg-red-50 {
    transition: all 0.2s ease-in-out;
}

.bg-gray-50:hover, .bg-blue-50:hover, .bg-green-50:hover, .bg-purple-50:hover, .bg-red-50:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* تحسينات للأزرار */
button {
    transition: all 0.2s ease-in-out;
}

button:hover {
    transform: translateY(-1px);
}

/* تحسينات للجداول */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* تحسينات للـ Modal */
#confirmModal {
    backdrop-filter: blur(4px);
}

/* تحسينات للمقاييس */
#cpuUsage, #memoryUsage, #diskUsage, #activeUsers {
    transition: all 0.3s ease-in-out;
}

/* تحسينات للتنبيهات */
.notification {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
@endpush





