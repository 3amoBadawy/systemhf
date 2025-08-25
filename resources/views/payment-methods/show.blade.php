@extends('layouts.app')

@section('title', 'عرض طريقة الدفع - نظام إدارة معرض الأثاث')

@section('navbar-title', '💳 عرض طريقة الدفع')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">💳 {{ $paymentMethod->name_ar }}</h2>
                <p>{{ $paymentMethod->description ?: 'لا يوجد وصف' }}</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('payment-methods.edit', $paymentMethod) }}" class="btn-primary">✏️ تعديل</a>
                <a href="{{ route('payment-methods.index') }}" class="btn-secondary">🔙 العودة</a>
            </div>
        </div>
    </div>

    <!-- ملخص سريع -->
    <div style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-radius: 8px; border: 1px solid #e2e8f0;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">📊 ملخص طريقة الدفع</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #667eea; margin-bottom: 0.5rem;">{{ $paymentMethod->name_ar }}</div>
                <div style="color: #718096; font-size: 0.875rem;">اسم طريقة الدفع</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #38a169; margin-bottom: 0.5rem;">{{ $paymentMethod->branch ? $paymentMethod->branch->name : 'غير محدد' }}</div>
                <div style="color: #718096; font-size: 0.875rem;">الفرع</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #d69e2e; margin-bottom: 0.5rem;">{{ $paymentMethod->code ?: 'غير محدد' }}</div>
                <div style="color: #718096; font-size: 0.875rem;">الكود</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #e53e3e; margin-bottom: 0.5rem;">{{ $paymentMethod->is_active ? 'نشط' : 'غير نشط' }}</div>
                <div style="color: #718096; font-size: 0.875rem;">الحالة</div>
            </div>
        </div>
    </div>

    <!-- معلومات الحساب المالي -->
    @if($paymentMethod->account)
    <div style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">💰 الحساب المالي المرتبط</h3>
        <div style="padding: 1.5rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h4 style="margin: 0; color: #2d3748;">{{ $paymentMethod->account->name_ar }}</h4>
                <a href="{{ route('payment-methods.account', $paymentMethod) }}" class="btn-primary">
                    👁️ عرض تفاصيل الحساب
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; color: #38a169; font-weight: 600;">{{ number_format($paymentMethod->current_balance, 2) }}</div>
                    <div style="color: #718096; font-size: 0.875rem;">الرصيد الحالي (د.ك)</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; color: #667eea; font-weight: 600;">{{ $paymentMethod->account->transactions()->count() }}</div>
                    <div style="color: #718096; font-size: 0.875rem;">عدد المعاملات</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; color: #d69e2e; font-weight: 600;">{{ $paymentMethod->account->type_name }}</div>
                    <div style="color: #718096; font-size: 0.875rem;">نوع الحساب</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; color: #e53e3e; font-weight: 600;">{{ $paymentMethod->account->branch ? $paymentMethod->account->branch->name : 'غير محدد' }}</div>
                    <div style="color: #718096; font-size: 0.875rem;">فرع الحساب</div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div style="margin-bottom: 2rem; padding: 1.5rem; background: #fef5e7; border-radius: 8px; border: 1px solid #f6ad55;">
        <div style="text-align: center;">
            <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">⚠️ لا يوجد حساب مالي مرتبط</h4>
            <p style="color: #718096; margin-bottom: 1rem;">هذه طريقة الدفع لا تحتوي على حساب مالي مرتبط.</p>
            <form method="POST" action="{{ route('payment-methods.create-account', $paymentMethod) }}">
                @csrf
                <button type="submit" class="btn-primary">
                    ➕ إنشاء حساب مالي مرتبط
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- آخر المعاملات -->
    @if($paymentMethod->account && $paymentMethod->account->transactions()->count() > 0)
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📋 آخر المعاملات</h3>
            <a href="{{ route('payment-methods.account', $paymentMethod) }}" class="btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                👁️ عرض جميع المعاملات
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>📅 التاريخ</th>
                        <th>👤 المستخدم</th>
                        <th>💰 المبلغ</th>
                        <th>📝 الوصف</th>
                        <th>📊 النوع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethod->account->transactions()->with(['user'])->latest()->limit(5)->get() as $transaction)
                        <tr>
                            <td>{{ $transaction->date->format('Y-m-d') }}</td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- معلومات إضافية -->
    <div style="margin-top: 2rem; padding: 1.5rem; background: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">ℹ️ معلومات إضافية</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div>
                <label style="font-weight: 600; color: #2d3748;">🔑 الكود:</label>
                <span>{{ $paymentMethod->code ?: 'غير محدد' }}</span>
            </div>
            <div>
                <label style="font-weight: 600; color: #2d3748;">📅 تاريخ الإنشاء:</label>
                <span>{{ $paymentMethod->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <label style="font-weight: 600; color: #2d3748;">🔄 آخر تحديث:</label>
                <span>{{ $paymentMethod->updated_at->format('Y-m-d H:i') }}</span>
            </div>
            <div>
                <label style="font-weight: 600; color: #2d3748;">📊 ترتيب العرض:</label>
                <span>{{ $paymentMethod->sort_order ?: 'غير محدد' }}</span>
            </div>
        </div>
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


