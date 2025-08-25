@extends('layouts.app')

@section('title', 'العملاء - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">العملاء</h1>
        <p class="mt-1 text-sm text-gray-500">إدارة جميع العملاء في النظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i data-lucide="plus" class="h-4 w-4 ml-2"></i>
            إضافة عميل جديد
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Search and Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">البحث والتصفية</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('customers.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                               placeholder="البحث بالاسم، الهاتف، أو العنوان">
                    </div>
                    
                    <div>
                        <label for="search_type" class="block text-sm font-medium text-gray-700 mb-1">نوع البحث</label>
                        <select name="search_type" id="search_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="all" {{ request('search_type') == 'all' ? 'selected' : '' }}>جميع الحقول</option>
                            <option value="name" {{ request('search_type') == 'name' ? 'selected' : '' }}>الاسم فقط</option>
                            <option value="phone" {{ request('search_type') == 'phone' ? 'selected' : '' }}>الهاتف فقط</option>
                            <option value="location" {{ request('search_type') == 'location' ? 'selected' : '' }}>الموقع فقط</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">ترتيب حسب</label>
                        <select name="sort_by" id="sort_by" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="phone" {{ request('sort_by') == 'phone' ? 'selected' : '' }}>الهاتف</option>
                            <option value="governorate" {{ request('sort_by') == 'governorate' ? 'selected' : '' }}>المحافظة</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i data-lucide="search" class="h-4 w-4 ml-2"></i>
                            بحث
                        </button>
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i data-lucide="refresh-cw" class="h-4 w-4 ml-2"></i>
                            إعادة تعيين
                        </a>
                    </div>
                    
                    @if(request('search') || request('search_type') || request('sort_by'))
                        <div class="text-sm text-gray-500">
                            نتائج البحث: {{ $customers->total() }} عميل
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">قائمة العملاء</h3>
            <p class="mt-1 text-sm text-gray-500">إجمالي العملاء: {{ $customers->total() }}</p>
        </div>
        
        @if($customers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">معلومات الاتصال</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الموقع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة المالية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($customer->name, 0, 1) }}</span>
                                        </div>
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $customer->phone }}</div>
                                    @if($customer->phone2)
                                        <div class="text-sm text-gray-500">{{ $customer->phone2 }}</div>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $customer->governorate }}</div>
                                    <div class="text-sm text-gray-500">{{ $customer->country }}</div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ number_format($customer->total_invoiced, 0) }} ريال</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        مدفوع: {{ number_format($customer->total_paid, 0) }} ريال
                                    </div>
                                    @if($customer->remaining_balance > 0)
                                        <div class="text-sm text-red-600 font-medium">
                                            متبقي: {{ number_format($customer->remaining_balance, 0) }} ريال
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($customer->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i data-lucide="check-circle" class="h-3 w-3 ml-1"></i>
                                            نشط
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i data-lucide="x-circle" class="h-3 w-3 ml-1"></i>
                                            غير نشط
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2 rtl:space-x-reverse">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                                                                       class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50">
                                            <i data-lucide="edit" class="h-4 w-4"></i>
                                        </a>
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" 
                                              class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $customers->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i data-lucide="users" class="mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا يوجد عملاء</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ بإضافة عميل جديد.</p>
                <div class="mt-6">
                    <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i data-lucide="plus" class="h-4 w-4 ml-2"></i>
                        إضافة عميل جديد
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection
