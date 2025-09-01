@extends('layouts.app')

@section('title', 'عرض الفرع - نظام إدارة معرض الأثاث')

@section('navbar-title', '🏢 عرض الفرع')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">🏢 {{ $branch->name_ar }}</h2>
                <p>{{ $branch->name }} ({{ $branch->code }})</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('branches.edit', $branch) }}" class="btn-primary">✏️ تعديل</a>
                <form method="POST" action="{{ route('branches.toggle-status', $branch) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-{{ $branch->is_active ? 'warning' : 'success' }}">
                        {{ $branch->is_active ? '⚠️ إلغاء التفعيل' : '✅ تفعيل' }}
                    </button>
                </form>
                <a href="{{ route('branches.index') }}" class="btn-secondary">🔙 رجوع</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- معلومات الفرع -->
    <div class="branch-info-section">
        <h3>📋 معلومات الفرع</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>الاسم بالإنجليزية:</label>
                <span>{{ $branch->name }}</span>
            </div>
            <div class="info-item">
                <label>الاسم بالعربية:</label>
                <span>{{ $branch->name_ar }}</span>
            </div>
            <div class="info-item">
                <label>الكود:</label>
                <span class="code-badge">{{ $branch->code }}</span>
            </div>
            <div class="info-item">
                <label>الترتيب:</label>
                <span>{{ $branch->sort_order }}</span>
            </div>
            <div class="info-item">
                <label>الحالة:</label>
                <span class="status-badge {{ $branch->is_active ? 'active' : 'inactive' }}">
                    {{ $branch->is_active ? 'نشط' : 'غير نشط' }}
                </span>
            </div>
            <div class="info-item">
                <label>الهاتف:</label>
                <span>{{ $branch->phone ?? 'غير محدد' }}</span>
            </div>
            <div class="info-item">
                <label>البريد الإلكتروني:</label>
                <span>{{ $branch->email ?? 'غير محدد' }}</span>
            </div>
            <div class="info-item">
                <label>اسم المدير:</label>
                <span>{{ $branch->manager_name ?? 'غير محدد' }}</span>
            </div>
            @if($branch->address)
                <div class="info-item full-width">
                    <label>العنوان:</label>
                    <span>{{ $branch->address }}</span>
                </div>
            @endif
            @if($branch->notes)
                <div class="info-item full-width">
                    <label>ملاحظات:</label>
                    <span>{{ $branch->notes }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- إحصائيات الفرع -->
    <div class="branch-stats-section">
        <h3>📊 إحصائيات الفرع</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-content">
                    <h4>العملاء</h4>
                    <div class="stat-number">{{ $branch->customers()->count() }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📄</div>
                <div class="stat-content">
                    <h4>الفواتير</h4>
                    <div class="stat-number">{{ $branch->invoices()->count() }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                    <h4>إجمالي الفواتير</h4>
                    <div class="stat-number">{{ number_format($branch->invoices()->sum('total'), 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💳</div>
                <div class="stat-content">
                    <h4>المدفوعات</h4>
                    <div class="stat-number">{{ $branch->payments()->count() }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💸</div>
                <div class="stat-content">
                    <h4>إجمالي المدفوع</h4>
                    <div class="stat-number">{{ number_format($branch->payments()->sum('amount'), 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🏦</div>
                <div class="stat-content">
                    <h4>الحسابات المالية</h4>
                    <div class="stat-number">{{ $branch->accounts()->count() }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💸</div>
                <div class="stat-content">
                    <h4>المصروفات</h4>
                    <div class="stat-number">{{ $branch->expenses()->count() }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👤</div>
                <div class="stat-content">
                    <h4>المستخدمين</h4>
                    <div class="stat-number">{{ $branch->users()->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="additional-info-section">
        <h3>ℹ️ معلومات إضافية</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>تاريخ الإنشاء:</label>
                <span>{{ $branch->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-item">
                <label>آخر تحديث:</label>
                <span>{{ $branch->updated_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.branch-info-section,
.branch-stats-section,
.additional-info-section {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.branch-info-section h3,
.branch-stats-section h3,
.additional-info-section h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
    font-size: 1.2rem;
    border-bottom: 2px solid #4299e1;
    padding-bottom: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.info-item span {
    color: #1a202c;
}

.code-badge {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-block;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2rem;
}

.stat-content h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 0.875rem;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    font-family: monospace;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .card-header > div:last-child {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection


