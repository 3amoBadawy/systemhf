@extends('layouts.app')

@section('title', 'عرض المصروف - نظام إدارة معرض الأثاث')

@section('navbar-title', '💸 عرض المصروف')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">💸 {{ $expense->title_ar }}</h2>
                <p>{{ $expense->title }} - {{ $expense->category_name }}</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                @if(!$expense->is_approved)
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn-primary">✏️ تعديل</a>
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-success">✅ اعتماد</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('expenses.unapprove', $expense) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-warning">⚠️ إلغاء الاعتماد</button>
                    </form>
                @endif
                <a href="{{ route('expenses.index') }}" class="btn-secondary">🔙 رجوع</a>
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

    <!-- معلومات المصروف -->
    <div class="expense-info-section">
        <h3>📋 معلومات المصروف</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>العنوان:</label>
                <span>{{ $expense->title_ar }}</span>
            </div>
            <div class="info-item">
                <label>Title:</label>
                <span>{{ $expense->title }}</span>
            </div>
            <div class="info-item">
                <label>المبلغ:</label>
                <span class="amount negative">{{ number_format($expense->amount, 2) }} ريال</span>
            </div>
            <div class="info-item">
                <label>الفئة:</label>
                <span class="category-badge category-{{ $expense->category }}">
                    {{ $expense->category_name }}
                </span>
            </div>
            <div class="info-item">
                <label>الفرع:</label>
                <span>{{ $expense->branch->name_ar }} ({{ $expense->branch->code }})</span>
            </div>
            <div class="info-item">
                <label>تاريخ المصروف:</label>
                <span>{{ $expense->date->format('Y-m-d') }}</span>
            </div>
            <div class="info-item">
                <label>طريقة الدفع:</label>
                <span>{{ $expense->paymentMethod->name_ar }} ({{ $expense->paymentMethod->code }})</span>
            </div>
            <div class="info-item">
                <label>الحالة:</label>
                <span class="status-badge {{ $expense->is_approved ? 'approved' : 'pending' }}">
                    {{ $expense->is_approved ? 'معتمد' : 'في الانتظار' }}
                </span>
            </div>
            @if($expense->description)
                <div class="info-item full-width">
                    <label>الوصف:</label>
                    <span>{{ $expense->description }}</span>
                </div>
            @endif
            @if($expense->notes)
                <div class="info-item full-width">
                    <label>ملاحظات:</label>
                    <span>{{ $expense->notes }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- معلومات الاعتماد -->
    @if($expense->is_approved)
        <div class="approval-info-section">
            <h3>✅ معلومات الاعتماد</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>معتمد بواسطة:</label>
                    <span>{{ $expense->approver->name }}</span>
                </div>
                <div class="info-item">
                    <label>تاريخ الاعتماد:</label>
                    <span>{{ $expense->approved_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- معلومات إضافية -->
    <div class="additional-info-section">
        <h3>ℹ️ معلومات إضافية</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>أنشئ بواسطة:</label>
                <span>{{ $expense->user->name }}</span>
            </div>
            <div class="info-item">
                <label>تاريخ الإنشاء:</label>
                <span>{{ $expense->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-item">
                <label>آخر تحديث:</label>
                <span>{{ $expense->updated_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- صورة الإيصال -->
    @if($expense->receipt_image)
        <div class="receipt-section">
            <h3>🧾 صورة الإيصال</h3>
            <div class="receipt-image">
                <img src="{{ asset('storage/' . $expense->receipt_image) }}" 
                     alt="صورة الإيصال" style="max-width: 100%; border-radius: 8px;">
            </div>
        </div>
    @endif
</div>

<style>
.expense-info-section,
.approval-info-section,
.additional-info-section,
.receipt-section {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.expense-info-section h3,
.approval-info-section h3,
.additional-info-section h3,
.receipt-section h3 {
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

.amount {
    font-weight: 600;
    font-family: monospace;
    font-size: 1.1rem;
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

.category-rent {
    background: #dbeafe;
    color: #1e40af;
}

.category-utilities {
    background: #fef3c7;
    color: #92400e;
}

.category-salaries {
    background: #d1fae5;
    color: #065f46;
}

.category-maintenance {
    background: #fee2e2;
    color: #991b1b;
}

.category-marketing {
    background: #f3e8ff;
    color: #7c3aed;
}

.category-office_supplies {
    background: #ecfdf5;
    color: #047857;
}

.category-transportation {
    background: #fef2f2;
    color: #dc2626;
}

.category-insurance {
    background: #f0f9ff;
    color: #0369a1;
}

.category-taxes {
    background: #fefce8;
    color: #a16207;
}

.category-other {
    background: #f3f4f6;
    color: #374151;
}

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

.receipt-image {
    text-align: center;
    background: white;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
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
}
</style>
@endsection


