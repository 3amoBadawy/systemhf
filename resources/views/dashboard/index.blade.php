@extends('layouts.app')

@section('title', 'لوحة الإدارة - نظام إدارة معرض الأثاث')

@section('navbar-title', '🏠 لوحة الإدارة')

@section('content')
<div class="dashboard">
    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <!-- العملاء -->
        <div class="stat-card customers">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>{{ number_format($customersCount) }}</h3>
                <p>إجمالي العملاء</p>
                <small>+{{ $newCustomersThisMonth }} هذا الشهر</small>
            </div>
        </div>

        <!-- الفواتير -->
        <div class="stat-card invoices">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <h3>{{ number_format($invoicesCount) }}</h3>
                <p>إجمالي الفواتير</p>
                <small>{{ number_format($totalInvoiced, 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</small>
            </div>
        </div>

        <!-- المدفوعات -->
        <div class="stat-card payments">
            <div class="stat-icon">💳</div>
            <div class="stat-content">
                <h3>{{ number_format($paymentsCount) }}</h3>
                <p>إجمالي المدفوعات</p>
                <small>{{ number_format($totalPaid, 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</small>
            </div>
        </div>

        <!-- المنتجات -->
        <div class="stat-card products">
            <div class="stat-icon">🪑</div>
            <div class="stat-content">
                <h3>{{ number_format($productsCount) }}</h3>
                <p>إجمالي المنتجات</p>
                <small>{{ $activeProducts }} نشط</small>
            </div>
        </div>
    </div>

    <!-- تفاصيل إضافية -->
    <div class="details-grid">
        <!-- حالة الفواتير -->
        <div class="detail-card">
            <h3>📊 حالة الفواتير</h3>
            <div class="status-list">
                <div class="status-item">
                    <span class="status-badge pending">في الانتظار</span>
                    <span class="status-count">{{ $pendingInvoices }}</span>
                </div>
                <div class="status-item">
                    <span class="status-badge completed">مكتملة</span>
                    <span class="status-count">{{ $completedInvoices }}</span>
                </div>
            </div>
        </div>

        <!-- طرق الدفع -->
        <div class="detail-card">
            <h3>💳 طرق الدفع</h3>
            <div class="payment-methods-info">
                <div class="method-stat">
                    <span>إجمالي الطرق:</span>
                    <strong>{{ $paymentMethodsCount }}</strong>
                </div>
                <div class="method-stat">
                    <span>الطرق النشطة:</span>
                    <strong>{{ $activePaymentMethods }}</strong>
                </div>
                <a href="{{ route('financial.index') }}" class="btn-secondary">إدارة مالية موحدة</a>
            </div>
        </div>

        <!-- مدفوعات الشهر -->
        <div class="detail-card">
            <h3>📅 مدفوعات هذا الشهر</h3>
            <div class="monthly-payments">
                <div class="amount">{{ number_format($paymentsThisMonth, 2) }}</div>
                <div class="label">{{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</div>
            </div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="charts-grid">
        <!-- الإحصائيات الشهرية -->
        <div class="chart-card">
            <h3>📈 الإحصائيات الشهرية</h3>
            <div class="simple-chart">
                @foreach($monthlyStats['months'] as $index => $month)
                    <div class="month-bar">
                        <div class="month-label">{{ $month }}</div>
                        <div class="month-data">
                            <div class="data-item">
                                <span class="data-label">فواتير:</span>
                                <span class="data-value">{{ number_format($monthlyStats['invoices'][$index], 0) }}</span>
                            </div>
                            <div class="data-item">
                                <span class="data-label">مدفوعات:</span>
                                <span class="data-value">{{ number_format($monthlyStats['payments'][$index], 0) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- طرق الدفع الأكثر استخداماً -->
        <div class="chart-card">
            <h3>🏆 طرق الدفع الأكثر استخداماً</h3>
            <div class="payment-methods-chart">
                @forelse($topPaymentMethods as $method)
                    <div class="method-bar">
                        <div class="method-name">{{ $method->payment_method }}</div>
                        <div class="method-bar-container">
                            <div class="method-bar-fill" style="width: {{ ($method->count / max($topPaymentMethods->pluck('count')->toArray())) * 100 }}%"></div>
                        </div>
                        <div class="method-count">{{ $method->count }}</div>
                    </div>
                @empty
                    <div class="no-data">لا توجد مدفوعات بعد</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- أحدث النشاطات -->
    <div class="activity-grid">
        <!-- أحدث الفواتير -->
        <div class="activity-card">
            <div class="card-header">
                <h3>📋 أحدث الفواتير</h3>
                <a href="{{ route('invoices.index') }}" class="btn-secondary">عرض الكل</a>
            </div>
            <div class="activity-list">
                @forelse($recentInvoices as $invoice)
                    <div class="activity-item">
                        <div class="activity-icon">📄</div>
                        <div class="activity-content">
                            <div class="activity-title">فاتورة {{ $invoice->invoice_number }}</div>
                            <div class="activity-subtitle">{{ $invoice->customer->name }}</div>
                            <div class="activity-meta">{{ number_format($invoice->total, 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</div>
                        </div>
                        <div class="activity-time">{{ $invoice->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="no-activity">لا توجد فواتير حديثة</div>
                @endforelse
            </div>
        </div>

        <!-- أحدث المدفوعات -->
        <div class="activity-card">
            <div class="card-header">
                <h3>💳 أحدث المدفوعات</h3>
                <a href="{{ route('payments.index') }}" class="btn-secondary">عرض الكل</a>
            </div>
            <div class="activity-list">
                @forelse($recentPayments as $payment)
                    <div class="activity-item">
                        <div class="activity-icon">💰</div>
                        <div class="activity-content">
                            <div class="activity-title">{{ number_format($payment->amount, 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</div>
                            <div class="activity-subtitle">{{ $payment->customer->name }}</div>
                            <div class="activity-meta">{{ $payment->payment_method }}</div>
                        </div>
                        <div class="activity-time">{{ $payment->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="no-activity">لا توجد مدفوعات حديثة</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.dashboard {
    padding: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.stat-card.customers .stat-icon { background: #e3f2fd; }
.stat-card.invoices .stat-icon { background: #f3e5f5; }
.stat-card.payments .stat-icon { background: #e8f5e8; }
.stat-card.products .stat-icon { background: #fff3e0; }

.stat-content h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: bold;
    color: #1a202c;
}

.stat-content p {
    margin: 0.25rem 0;
    color: #4a5568;
    font-weight: 500;
}

.stat-content small {
    color: #718096;
    font-size: 0.875rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.detail-card h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
    font-size: 1.2rem;
}

.status-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
}

.status-count {
    font-weight: bold;
    color: #1a202c;
}

.payment-methods-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.method-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.monthly-payments {
    text-align: center;
}

.monthly-payments .amount {
    font-size: 2rem;
    font-weight: bold;
    color: #1a202c;
}

.monthly-payments .label {
    color: #718096;
    font-size: 0.875rem;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-card h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
    font-size: 1.2rem;
}

/* الرسم البياني البسيط */
.simple-chart {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.month-bar {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
    border-left: 4px solid #4299e1;
}

.month-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.month-data {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.data-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    background: white;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.data-label {
    color: #4a5568;
    font-size: 0.875rem;
}

.data-value {
    font-weight: 600;
    color: #1a202c;
    font-size: 1rem;
}

.payment-methods-chart {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.method-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.method-name {
    width: 120px;
    font-size: 0.875rem;
    color: #4a5568;
}

.method-bar-container {
    flex: 1;
    height: 20px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.method-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #4299e1, #3182ce);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.method-count {
    width: 40px;
    text-align: center;
    font-weight: bold;
    color: #1a202c;
}

.no-data {
    text-align: center;
    color: #a0aec0;
    padding: 2rem;
    font-style: italic;
}

.activity-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.activity-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    padding: 1.5rem 1.5rem 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #1a202c;
    font-size: 1.2rem;
}

.activity-list {
    padding: 1rem 1.5rem 1.5rem 1.5rem;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f7fafc;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7fafc;
    border-radius: 50%;
    font-size: 1.2rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.activity-subtitle {
    font-size: 0.875rem;
    color: #4a5568;
    margin-bottom: 0.25rem;
}

.activity-meta {
    font-size: 0.75rem;
    color: #718096;
}

.activity-time {
    font-size: 0.75rem;
    color: #a0aec0;
}

.no-activity {
    text-align: center;
    color: #a0aec0;
    padding: 2rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .activity-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- تم إزالة Chart.js لتحسين الأداء -->
@endsection
