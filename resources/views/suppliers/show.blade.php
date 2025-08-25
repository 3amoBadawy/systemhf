@extends('layouts.app')

@section('title', 'عرض مورد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">عرض مورد</h1>
        <p class="mt-1 text-sm text-gray-500">تفاصيل {{ $supplier->name_ar }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            تعديل
        </a>
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للموردين
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- معلومات المورد الأساسية -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">معلومات المورد الأساسية</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">اسم المورد</label>
                    <p class="text-lg font-medium text-gray-900">{{ $supplier->name_ar }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">رقم الهاتف الرئيسي</label>
                    <p class="text-lg text-gray-900">
                        @if($supplier->phone)
                            <span class="inline-flex items-center">
                                <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $supplier->phone }}
                            </span>
                        @else
                            <span class="text-gray-400">غير محدد</span>
                        @endif
                    </p>
                </div>
                
                @if($supplier->phone2)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">رقم الهاتف الثاني</label>
                    <p class="text-lg text-gray-900">
                        <span class="inline-flex items-center">
                            <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $supplier->phone2 }}
                        </span>
                    </p>
                </div>
                @endif
                
                @if($supplier->email)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">البريد الإلكتروني</label>
                    <p class="text-lg text-gray-900">
                        <span class="inline-flex items-center">
                            <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $supplier->email }}
                        </span>
                    </p>
                </div>
                @endif
                
                @if($supplier->governorate)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">المحافظة</label>
                    <p class="text-lg text-gray-900">
                        <span class="inline-flex items-center">
                            <svg class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $supplier->governorate }}
                        </span>
                    </p>
                </div>
                @endif
                
                @if($supplier->address)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">العنوان التفصيلي</label>
                    <p class="text-lg text-gray-900">{{ $supplier->address }}</p>
                </div>
                @endif
                
                @if($supplier->notes)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">ملاحظات</label>
                    <p class="text-lg text-gray-900">{{ $supplier->notes }}</p>
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                    <div class="mt-1">
                        {!! $supplier->status_badge !!}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">تاريخ التسجيل</label>
                    <p class="text-lg text-gray-900">{{ $supplier->created_at->format('Y/m/d') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- المنتجات المرتبطة -->
    @if($supplier->products->count() > 0)
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">المنتجات المرتبطة</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $supplier->products->count() }} منتج مرتبط بهذا المورد</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($supplier->products->take(10) as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->main_image)
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name_ar }}">
                                </div>
                                @else
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                @endif
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name_ar }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->product_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($product->category)
                                    {{ $product->category->name_ar }}
                                @else
                                    <span class="text-gray-400">غير محدد</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($product->price)
                                    <span class="font-medium">{{ number_format($product->price, 0) }} ريال</span>
                                @else
                                    <span class="text-gray-400">غير محدد</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">نشط</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">غير نشط</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($supplier->products->count() > 10)
        <div class="px-6 py-4 border-t border-gray-200">
            <p class="text-sm text-gray-500">عرض {{ $supplier->products->take(10)->count() }} من أصل {{ $supplier->products->count() }} منتج</p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد منتجات</h3>
            <p class="mt-1 text-sm text-gray-500">لم يتم ربط أي منتجات بهذا المورد بعد.</p>
        </div>
    </div>
    @endif
</div>
@endsection
