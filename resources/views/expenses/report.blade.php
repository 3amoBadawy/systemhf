@extends('layouts.app')

@section('title', 'تقرير المصروفات - نظام إدارة معرض الأثاث')

@section('navbar-title', '📊 تقرير المصروفات')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 تقرير المصروفات</h2>
        <p>تقرير شامل عن جميع المصروفات</p>
    </div>

    <!-- فلاتر التقرير -->
    <div class="filters-section">
        <form method="GET" action="{{ route('expenses.report') }}" class="filters-form">
            <div class="filters-grid">
                <div class="form-group">
                    <label for="branch_id">الفرع</label>
                    <select id="branch_id" name="branch_id" class="form-control">
                        <option value="">جميع الفروع</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name_ar }} ({{ $branch->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="category">الفئة</label>
                    <select id="category" name="category" class="form-control">
                        <option value="">جميع الفئات</option>
                        @foreach(App\Models\Expense::getCategories() as $key => $value)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_from">من تاريخ</label>
                    <input type="date" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="date_to">إلى تاريخ</label>
                    <input type="date" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}" class="form-control">
                </div>
            </div>
            
            <div class="filters-actions">
                <button type="submit" class="btn-primary">🔍 تطبيق الفلاتر</button>
                <a href="{{ route('expenses.report') }}" class="btn-secondary">🔄 إعادة تعيين</a>
            </div>
        </form>
    </div>

    <!-- ملخص التقرير -->
    <div class="report-summary">
        <h3>📈 ملخص التقرير</h3>
        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon">💸</div>
                <div class="summary-content">
                    <h4>إجمالي المصروفات</h4>
                    <div class="summary-amount negative">
                        {{ number_format($expenses->sum('amount'), 2) }} ريال
                    </div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">📊</div>
                <div class="summary-content">
                    <h4>عدد المصروفات</h4>
                    <div class="summary-amount">
                        {{ $expenses->count() }}
                    </div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">✅</div>
                <div class="summary-content">
                    <h4>المصروفات المعتمدة</h4>
                    <div class="summary-amount">
                        {{ $expenses->where('is_approved', true)->count() }}
                    </div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">⏳</div>
                <div class="summary-content">
                    <h4>في الانتظار</h4>
                    <div class="summary-amount">
                        {{ $expenses->where('is_approved', false)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تفاصيل المصروفات -->
    <div class="expenses-details">
        <h3>📋 تفاصيل المصروفات</h3>
        
        @if($expenses->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>المبلغ</th>
                            <th>الفئة</th>
                            <th>الفرع</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                            <th>طريقة الدفع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr>
                                <td>
                                    <div class="expense-title">
                                        <div>{{ $expense->title_ar }}</div>
                                        <small>{{ $expense->title }}</small>
                                    </div>
                                </td>
                                <td class="amount negative">
                                    {{ number_format($expense->amount, 2) }} ريال
                                </td>
                                <td>
                                    <span class="category-badge category-{{ $expense->category }}">
                                        {{ $expense->category_name }}
                                    </span>
                                </td>
                                <td>{{ $expense->branch->name_ar }}</td>
                                <td>{{ $expense->date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="status-badge {{ $expense->is_approved ? 'approved' : 'pending' }}">
                                        {{ $expense->is_approved ? 'معتمد' : 'في الانتظار' }}
                                    </span>
                                </td>
                                <td>{{ $expense->paymentMethod->name_ar }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('expenses.show', $expense) }}" class="btn-icon" title="عرض">
                                            👁️
                                        </a>
                                        @if(!$expense->is_approved)
                                            <a href="{{ route('expenses.edit', $expense) }}" class="btn-icon" title="تعديل">
                                                ✏️
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <p>لا توجد مصروفات تطابق الفلاتر المحددة</p>
            </div>
        @endif
    </div>
</div>

<style>
.filters-section {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filters-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
}

.report-summary {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.report-summary h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
    font-size: 1.2rem;
    border-bottom: 2px solid #4299e1;
    padding-bottom: 0.5rem;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.summary-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.summary-icon {
    font-size: 2rem;
}

.summary-content h4 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 0.875rem;
}

.summary-amount {
    font-size: 1.25rem;
    font-weight: 700;
    font-family: monospace;
}

.summary-amount.negative {
    color: #dc2626;
}

.expenses-details {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
}

.expenses-details h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
    font-size: 1.2rem;
    border-bottom: 2px solid #4299e1;
    padding-bottom: 0.5rem;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: right;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    background: #f1f5f9;
    font-weight: 600;
    color: #374151;
}

.expense-title {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.expense-title small {
    color: #6b7280;
    font-size: 0.875rem;
}

.amount {
    font-weight: 600;
    font-family: monospace;
}

.amount.negative {
    color: #dc2626;
}

.category-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
}

.category-rent { background: #dbeafe; color: #1e40af; }
.category-utilities { background: #fef3c7; color: #92400e; }
.category-salaries { background: #d1fae5; color: #065f46; }
.category-maintenance { background: #fee2e2; color: #991b1b; }
.category-marketing { background: #f3e8ff; color: #7c3aed; }
.category-office_supplies { background: #ecfdf5; color: #047857; }
.category-transportation { background: #fef2f2; color: #dc2626; }
.category-insurance { background: #f0f9ff; color: #0369a1; }
.category-taxes { background: #fefce8; color: #a16207; }
.category-other { background: #f3f4f6; color: #374151; }

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    padding: 0.5rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #f1f5f9;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }
    
    .filters-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .data-table {
        font-size: 0.875rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
@endsection


