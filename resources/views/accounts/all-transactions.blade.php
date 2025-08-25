@extends('layouts.app')

@section('title', 'جميع معاملات الحساب - نظام إدارة معرض الأثاث')

@section('navbar-title', '📋 جميع معاملات الحساب')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">📋 جميع معاملات الحساب: {{ $account->name_ar }}</h2>
                <p>عرض جميع المعاملات من جميع الفروع</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('accounts.show', $account) }}" class="btn-secondary">🔙 العودة للحساب</a>
                <a href="{{ route('accounts.report', $account) }}" class="btn-primary">📊 تقرير شامل</a>
            </div>
        </div>
    </div>

    <!-- الرصيد الإجمالي من جميع الفروع -->
    <div style="margin-bottom: 2rem; padding: 1rem; background: #e6fffa; border-radius: 8px; border: 1px solid #81e6d9;">
        <div style="text-align: center;">
            <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">💰 الرصيد الإجمالي من جميع الفروع</h4>
            <div style="font-size: 2rem; color: #38a169; font-weight: 700;">{{ number_format($totalBalanceFromAllBranches, 2) }} د.ك</div>
            <small style="color: #718096;">هذا هو الرصيد الإجمالي المتاح في الحساب من جميع الفروع</small>
        </div>
    </div>

    <!-- إحصائيات جميع الفروع -->
    <div style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">🏢 إحصائيات جميع الفروع</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            @foreach($allBranchesStats as $branchId => $stats)
                <div style="padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0; color: #2d3748; font-size: 1rem;">{{ $stats['branch_name'] }}</h4>
                        <a href="{{ route('accounts.transactions-by-branch', ['account' => $account->id, 'branchId' => $branchId]) }}" class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                            👁️ عرض
                        </a>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
                        <div style="text-align: center;">
                            <div style="font-size: 1.25rem; color: #38a169; font-weight: 600;">{{ number_format($stats['balance'], 2) }}</div>
                            <div style="color: #718096; font-size: 0.75rem;">الرصيد (د.ك)</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.25rem; color: #667eea; font-weight: 600;">{{ $stats['transaction_count'] }}</div>
                            <div style="color: #718096; font-size: 0.75rem;">التحويلات</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- جميع المعاملات -->
    <div>
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">📋 جميع المعاملات من جميع الفروع</h3>
        
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>📅 التاريخ</th>
                            <th>🏢 الفرع</th>
                            <th>👤 المستخدم</th>
                            <th>💰 المبلغ</th>
                            <th>📝 الوصف</th>
                            <th>📊 النوع</th>
                            <th>🔗 المرجع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->date->format('Y-m-d') }}</td>
                                <td>
                                    @if($transaction->branch)
                                        <span style="color: #2d3748;">{{ $transaction->branch->name }}</span>
                                    @else
                                        <span style="color: #a0aec0;">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->user)
                                        <span style="color: #2d3748;">{{ $transaction->user->name }}</span>
                                    @else
                                        <span style="color: #a0aec0;">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: {{ $transaction->type === 'credit' ? '#38a169' : '#e53e3e' }};">
                                        {{ number_format($transaction->amount, 2) }} د.ك
                                    </span>
                                </td>
                                <td>{{ $transaction->description ?: '-' }}</td>
                                <td>
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: {{ $transaction->type === 'credit' ? '#d1fae5' : '#fee2e2' }}; color: {{ $transaction->type === 'credit' ? '#065f46' : '#991b1b' }};">
                                        {{ $transaction->type_name }}
                                    </span>
                                </td>
                                <td>
                                    @if($transaction->reference_type && $transaction->reference_id)
                                        <span style="color: #667eea; font-size: 0.875rem;">
                                            {{ $transaction->reference_type }} #{{ $transaction->reference_id }}
                                        </span>
                                    @else
                                        <span style="color: #a0aec0;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: #718096;">
                <h4>📭 لا توجد معاملات</h4>
                <p>لم يتم تسجيل أي معاملات لهذا الحساب بعد.</p>
            </div>
        @endif
    </div>
</div>

<style>
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
}

.card-title {
    margin: 0;
    color: #2d3748;
    font-size: 1.5rem;
    font-weight: 700;
}

.table-responsive {
    overflow-x: auto;
}

.table th {
    background: #f7fafc;
    color: #2d3748;
    font-weight: 600;
    padding: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f7fafc;
    transition: background 0.2s ease;
}

.btn-primary, .btn-secondary {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
    transform: translateY(-1px);
}
</style>
@endsection
