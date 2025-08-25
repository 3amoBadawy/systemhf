@extends('layouts.app')

@section('title', 'عرض العميل - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">عرض العميل</h1>
        <p class="mt-1 text-sm text-gray-500">تفاصيل العميل: {{ $customer->name }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للعملاء
        </a>
        <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            تعديل العميل
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Customer Information Card -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <span class="text-lg font-medium text-blue-600">{{ substr($customer->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-500">عميل منذ {{ $customer->created_at->format('Y/m/d') }}</p>
                </div>
                <div class="mr-auto">
                    @if($customer->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="h-3 w-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            نشط
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="h-3 w-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            غير نشط
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Contact Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">معلومات الاتصال</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">الهاتف الرئيسي</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->phone }}</dd>
                        </div>
                        @if($customer->phone2)
                            <div>
                                <dt class="text-xs font-medium text-gray-500">الهاتف الثاني</dt>
                                <dd class="text-sm text-gray-900">{{ $customer->phone2 }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
                
                <!-- Location Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">معلومات الموقع</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">البلد</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->country ?? 'غير محدد' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">المحافظة</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->governorate ?? 'غير محدد' }}</dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Branch Information -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">معلومات إضافية</h4>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500">الفرع</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->branch->name ?? 'غير محدد' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500">تاريخ التسجيل</dt>
                            <dd class="text-sm text-gray-900">{{ $customer->created_at->format('Y/m/d H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            @if($customer->address)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">العنوان التفصيلي</h4>
                    <p class="text-sm text-gray-700">{{ $customer->address }}</p>
                </div>
            @endif
            
            @if($customer->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">الملاحظات</h4>
                    <p class="text-sm text-gray-700">{{ $customer->notes }}</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Customer Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Invoices -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mr-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الفواتير</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $customer->invoices_count ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Amount -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="mr-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي المبلغ</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($customer->invoices_sum_total ?? 0, 0) }} ريال</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Payments -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mr-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">إجمالي المدفوعات</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($customer->payments_sum_amount ?? 0, 0) }} ريال</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Invoices -->
    @if($customer->invoices && $customer->invoices->count() > 0)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">الفواتير الأخيرة</h3>
                    <a href="{{ route('invoices.index', ['customer_id' => $customer->id]) }}" class="text-sm text-blue-600 hover:text-blue-900">
                        عرض جميع الفواتير
                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customer->invoices->take(5) as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $invoice->invoice_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $invoice->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($invoice->total, 0) }} ريال
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($invoice->status == 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">مدفوعة</span>
                                    @elseif($invoice->status == 'partial')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">مدفوعة جزئياً</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">غير مدفوعة</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection