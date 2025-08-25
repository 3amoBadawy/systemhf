@extends('layouts.app')

@section('title', 'عرض الحساب المالي - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">عرض الحساب</h1>
        <p class="mt-1 text-sm text-gray-500">تفاصيل الحساب: {{ $account->name_ar }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('accounts.edit', $account) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            تعديل الحساب
        </a>
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للحسابات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">💰 {{ $account->name_ar }}</h2>
                <p>{{ $account->description ?: 'لا يوجد وصف' }}</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('accounts.edit', $account) }}" class="btn-primary">✏️ تعديل</a>
                <a href="{{ route('accounts.index') }}" class="btn-secondary">🔙 العودة</a>
            </div>
        </div>
    </div>

    <!-- ملخص سريع -->
    <div style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-radius: 8px; border: 1px solid #e2e8f0;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">📊 ملخص الحساب</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #667eea; margin-bottom: 0.5rem;">{{ number_format($currentBalance, 2) }}</div>
                <div style="color: #718096; font-size: 0.875rem;">الرصيد الحالي (د.ك)</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #38a169; margin-bottom: 0.5rem;">{{ number_format($totalBalanceFromAllBranches, 2) }}</div>
                <div style="color: #718096; font-size: 0.875rem;">الرصيد الإجمالي من جميع الفروع (د.ك)</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #d69e2e; margin-bottom: 0.5rem;">{{ $account->transactions()->count() }}</div>
                <div style="color: #718096; font-size: 0.875rem;">إجمالي المعاملات</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="font-size: 2rem; color: #e53e3e; margin-bottom: 0.5rem;">{{ count($branchStats) }}</div>
                <div style="color: #718096; font-size: 0.875rem;">عدد الفروع النشطة</div>
            </div>
        </div>
    </div>

    <!-- إحصائيات حسب الفرع -->
    <div style="margin-bottom: 2rem;">
        <h3 style="margin: 0 0 1rem 0; color: #2d3748; font-size: 1.1rem;">🏢 إحصائيات حسب الفرع</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
            @foreach($branchStats as $branchId => $stats)
                <div style="padding: 1.5rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="margin: 0; color: #2d3748;">{{ $stats['branch_name'] }}</h4>
                        <a href="{{ route('accounts.transactions-by-branch', ['account' => $account->id, 'branchId' => $branchId]) }}" class="btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                            👁️ عرض التحويلات
                        </a>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; color: #38a169; font-weight: 600;">{{ number_format($stats['balance'], 2) }}</div>
                            <div style="color: #718096; font-size: 0.875rem;">الرصيد (د.ك)</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; color: #667eea; font-weight: 600;">{{ $stats['transaction_count'] }}</div>
                            <div style="color: #718096; font-size: 0.875rem;">عدد التحويلات</div>
                        </div>
                    </div>
                    
                    @if($stats['last_transaction'])
                        <div style="padding: 0.75rem; background: #f7fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                            <small style="color: #718096;">آخر تحويل: {{ $stats['last_transaction']->date->format('Y-m-d') }} - {{ number_format($stats['last_transaction']->amount, 2) }} د.ك</small>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">إجراءات متقدمة</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('accounts.all-transactions', $account) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                جميع التحويلات من جميع الفروع
            </a>
            <a href="{{ route('accounts.report', $account) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                تقرير شامل
            </a>
            <form method="POST" action="{{ route('accounts.update-balance', $account) }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    تحديث الرصيد
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">آخر المعاملات</h3>
                <a href="{{ route('accounts.all-transactions', $account) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    عرض جميع المعاملات
                </a>
            </div>
        </div>
        
        @if($recentTransactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفرع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->date->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($transaction->branch)
                                        {{ $transaction->branch->name }}
                                    @else
                                        <span class="text-gray-400">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($transaction->user)
                                        {{ $transaction->user->name }}
                                    @else
                                        <span class="text-gray-400">غير محدد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($transaction->amount, 2) }} د.ك
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->description ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->type === 'credit')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $transaction->type_name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $transaction->type_name }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد معاملات</h3>
                    <p class="mt-1 text-sm text-gray-500">لم يتم تسجيل أي معاملات لهذا الحساب بعد.</p>
                </div>
            </div>
        @endif
    </div>
</div>


@endsection


