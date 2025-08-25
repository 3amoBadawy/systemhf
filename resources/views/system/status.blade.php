@extends('layouts.app')

@section('title', 'حالة النظام')
@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">حالة النظام</h1>
        <p class="mt-1 text-sm text-gray-500">معلومات شاملة عن حالة النظام</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">حالة النظام</h3>
            <p class="mt-1 text-sm text-gray-500">فحص شامل لجميع مكونات النظام</p>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-blue-600 truncate">إجمالي المكونات</dt>
                                <dd class="text-lg font-medium text-blue-900">{{ $systemStatus['total_components'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-green-600 truncate">مكونات تعمل</dt>
                                <dd class="text-lg font-medium text-green-900">{{ $systemStatus['working_components'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-yellow-600 truncate">تحذيرات</dt>
                                <dd class="text-lg font-medium text-yellow-900">{{ $systemStatus['warning_components'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-red-600 truncate">أخطاء</dt>
                                <dd class="text-lg font-medium text-red-900">{{ $systemStatus['error_components'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فحص Controllers -->
    <div class="status-section">
        <h3>🎮 Controllers</h3>
        <div class="status-grid">
            @foreach($systemStatus['controllers'] as $controller => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $controller }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص Models -->
    <div class="status-section">
        <h3>🏗️ Models</h3>
        <div class="status-grid">
            @foreach($systemStatus['models'] as $model => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $model }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص Routes -->
    <div class="status-section">
        <h3>🛣️ Routes</h3>
        <div class="status-grid">
            @foreach($systemStatus['routes'] as $route => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $route }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص Views -->
    <div class="status-section">
        <h3>👁️ Views</h3>
        <div class="status-grid">
            @foreach($systemStatus['views'] as $view => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $view }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص قاعدة البيانات -->
    <div class="status-section">
        <h3>🗄️ قاعدة البيانات</h3>
        <div class="status-grid">
            @foreach($systemStatus['database'] as $table => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $table }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص الملفات -->
    <div class="status-section">
        <h3>📁 الملفات</h3>
        <div class="status-grid">
            @foreach($systemStatus['files'] as $file => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $file }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص الخدمات -->
    <div class="status-section">
        <h3>⚙️ الخدمات</h3>
        <div class="status-grid">
            @foreach($systemStatus['services'] as $service => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $service }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص البيئة (.env) -->
    <div class="status-section">
        <h3>🌍 البيئة (.env)</h3>
        <div class="status-grid">
            @foreach($systemStatus['env'] as $key => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $key }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- فحص امتدادات PHP -->
    <div class="status-section">
        <h3>🧩 امتدادات PHP</h3>
        <div class="status-grid">
            @foreach($systemStatus['php_extensions'] as $ext => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $ext }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- صلاحيات المجلدات -->
    <div class="status-section">
        <h3>🗂️ صلاحيات المجلدات</h3>
        <div class="status-grid">
            @foreach($systemStatus['permissions'] as $label => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $label }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- حالة الهجرات -->
    <div class="status-section">
        <h3>📦 حالة الهجرات</h3>
        <div class="status-grid">
            @foreach($systemStatus['migrations'] as $label => $status)
            <div class="status-item status-{{ $status['status'] }}">
                <div class="status-icon">
                    @if($status['status'] == 'working') ✅
                    @elseif($status['status'] == 'warning') ⚠️
                    @else ❌
                    @endif
                </div>
                <div class="status-content">
                    <div class="status-name">{{ $label }}</div>
                    <div class="status-message">{{ $status['message'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- معلومات النظام -->
    <div class="status-section">
        <h3>ℹ️ معلومات النظام</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">إصدار PHP:</div>
                <div class="info-value">{{ $systemStatus['php_version'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">إصدار Laravel:</div>
                <div class="info-value">{{ $systemStatus['laravel_version'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">إصدار قاعدة البيانات:</div>
                <div class="info-value">{{ $systemStatus['database_version'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">المساحة المتاحة:</div>
                <div class="info-value">{{ $systemStatus['disk_space'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">الذاكرة المستخدمة:</div>
                <div class="info-value">{{ $systemStatus['memory_usage'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">وقت التشغيل:</div>
                <div class="info-value">{{ $systemStatus['uptime'] }}</div>
            </div>
        </div>
    </div>

    <!-- أزرار الإجراءات -->
    <div class="actions-section">
        <button type="button" class="btn btn-primary" onclick="refreshStatus()">
            🔄 تحديث الحالة
        </button>
        <button type="button" class="btn btn-secondary" onclick="exportReport()">
            📊 تصدير التقرير
        </button>
        <button type="button" class="btn btn-info" onclick="runDiagnostics()">
            🔍 تشغيل التشخيص
        </button>
        <button type="button" class="btn btn-secondary" onclick="openMaintenance()">
            🧰 خيارات الصيانة
        </button>
        <a href="{{ route('system.logs.latest') }}" class="btn btn-secondary">
            📥 تنزيل آخر سجل
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshStatus() {
    location.reload();
}

function exportReport() {
    // تصدير تقرير حالة النظام
    const report = {
        timestamp: new Date().toISOString(),
        status: @json($systemStatus)
    };
    
    const blob = new Blob([JSON.stringify(report, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'system-status-report.json';
    a.click();
    URL.revokeObjectURL(url);
}

function runDiagnostics() {
    // تشغيل تشخيص شامل
    alert('جاري تشغيل التشخيص الشامل...');
    
    fetch('/system/diagnostics', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تشغيل التشخيص بنجاح!');
            location.reload();
        } else {
            alert('حدث خطأ أثناء التشخيص');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء التشخيص');
    });
}

function openMaintenance() {
    const options = [
        { key: 'cache_clear', label: 'مسح Cache' },
        { key: 'config_clear', label: 'مسح Config' },
        { key: 'route_clear', label: 'مسح Routes' },
        { key: 'view_clear', label: 'مسح Views' },
        { key: 'cache_config', label: 'بناء Config Cache' },
        { key: 'cache_route', label: 'بناء Route Cache' },
        { key: 'cache_view', label: 'بناء View Cache' },
        { key: 'optimize', label: 'Optimize' },
        { key: 'storage_link', label: 'إنشاء storage:link' },
        { key: 'queue_restart', label: 'إعادة تشغيل Queue' },
    ];

    const choice = prompt('اختر إجراء الصيانة:\n' + options.map((o, i) => `${i+1}. ${o.label}`).join('\n'));
    const idx = parseInt(choice, 10) - 1;
    if (Number.isNaN(idx) || !options[idx]) return;

    const action = options[idx].key;

    fetch('{{ route('system.maintenance') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ action })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('تم التنفيذ بنجاح');
            location.reload();
        } else {
            alert(data.message || 'تعذر تنفيذ الإجراء');
        }
    })
    .catch(err => {
        console.error(err);
        alert('خطأ غير متوقع');
    });
}
</script>
@endpush

@push('styles')
<style>
.page-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.page-header h1 {
    margin: 0 0 1rem 0;
    color: #2d3748;
    font-size: 2rem;
}

.page-header p {
    margin: 0;
    color: #718096;
    font-size: 1.1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #718096;
    font-size: 0.875rem;
}

.status-section {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.status-section h3 {
    margin: 0 0 1.5rem 0;
    color: #2d3748;
    font-size: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
}

.status-item.status-working {
    background: #f0fff4;
    border-color: #48bb78;
}

.status-item.status-warning {
    background: #fffbeb;
    border-color: #ed8936;
}

.status-item.status-error {
    background: #fed7d7;
    border-color: #e53e3e;
}

.status-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.status-working .status-icon {
    background: #48bb78;
    color: white;
}

.status-warning .status-icon {
    background: #ed8936;
    color: white;
}

.status-error .status-icon {
    background: #e53e3e;
    color: white;
}

.status-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.status-message {
    color: #718096;
    font-size: 0.875rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
}

.info-label {
    font-weight: 500;
    color: #4a5568;
}

.info-value {
    color: #2d3748;
    font-weight: 600;
}

.actions-section {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
    transform: translateY(-2px);
}

.btn-info {
    background: #4299e1;
    color: white;
}

.btn-info:hover {
    background: #3182ce;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-section {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
@endpush


