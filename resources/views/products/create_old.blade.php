@extends('layouts.app')

@section('title', 'إضافة منتج جديد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة منتج جديد</h1>
        <p class="mt-1 text-sm text-gray-500">أدخل بيانات المنتج الجديد</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للمنتجات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">معلومات المنتج</h3>
            <p class="mt-1 text-sm text-gray-500">أدخل بيانات المنتج الجديد</p>
        </div>
        
        <form id="productForm" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">المعلومات الأساسية</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المنتج <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name_ar" 
                               id="name_ar" 
                               value="{{ old('name_ar') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('name_ar')) border-red-300 @endif"
                               placeholder="أدخل اسم المنتج باللغة العربية"
                               required>
                        @if(isset($errors) && $errors->has('name_ar'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('name_ar') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم المنتج بالإنجليزية
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('name')) border-red-300 @endif"
                               placeholder="Product name in English">
                        @if(isset($errors) && $errors->has('name'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="product_code" class="block text-sm font-medium text-gray-700 mb-2">
                            كود المنتج
                        </label>
                        <input type="text" 
                               name="product_code" 
                               id="product_code" 
                               value="{{ old('product_code') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('product_code')) border-red-300 @endif"
                               placeholder="سيتم إنشاؤه تلقائياً"
                               readonly>
                        <p class="mt-1 text-xs text-gray-500">سيتم إنشاء كود المنتج تلقائياً</p>
                        @if(isset($errors) && $errors->has('product_code'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('product_code') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            الفئة <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" 
                                id="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('category_id')) border-red-300 @endif"
                                required>
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar ?: $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @if(isset($errors) && $errors->has('category_id'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('category_id') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                            المورد
                        </label>
                        <select name="supplier_id" 
                                id="supplier_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('supplier_id')) border-red-300 @endif">
                            <option value="">اختر المورد</option>
                            @if($suppliers && $suppliers->count() > 0)
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name_ar ?? $supplier->name }} @if($supplier->phone) - {{ $supplier->phone }} @endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @if(isset($errors) && $errors->has('supplier_id'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('supplier_id') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Pricing Information -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">معلومات التسعير</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                            سعر التكلفة <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="cost_price" 
                                   id="cost_price" 
                                   value="{{ old('cost_price') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('cost_price')) border-red-300 @endif"
                                   placeholder="0.00"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $currencySymbol }}</span>
                            </div>
                        </div>
                        @if(isset($errors) && $errors->has('cost_price'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('cost_price') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            السعر النهائي <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   value="{{ old('price') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('price')) border-red-300 @endif"
                                   placeholder="0.00"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $currencySymbol }}</span>
                            </div>
                        </div>
                        @if(isset($errors) && $errors->has('price'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('price') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="profit_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                            نسبة الربح (%)
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="profit_percentage" 
                                   id="profit_percentage" 
                                   value="{{ old('profit_percentage') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('profit_percentage')) border-red-300 @endif"
                                   placeholder="أدخل نسبة الربح">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">أدخل نسبة الربح أو اترك السعر يحسبها تلقائياً</p>
                        @if(isset($errors) && $errors->has('profit_percentage'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('profit_percentage') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Product Components -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">مكونات المنتج</h4>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 mb-4">أضف القطع المكونة للمنتج (مثل: سرير، كمود، تسريحة)</p>
                    

                    
                    <div id="componentsContainer">
                        <!-- الصف الافتراضي الأول -->
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white rounded border border-gray-200" id="component_1">
                            <div class="flex-1">
                                <input type="text" 
                                       name="components[1][name]" 
                                       placeholder="اسم القطعة (مثل: سرير، كمود)"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            <div class="w-24">
                                <input type="number" 
                                       name="components[1][quantity]" 
                                       placeholder="الكمية"
                                       min="1" 
                                       value="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            
                            <!-- خيار تسعير المكون -->
                            <div class="w-32">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="components[1][has_pricing]" 
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           onchange="toggleComponentPricingRow(1)">
                                    <span class="ml-2 text-xs text-gray-700">تسعير</span>
                                </label>
                            </div>
                            
                            <!-- خانات التسعير -->
                            <div class="component-pricing-fields hidden" id="pricing_1">
                                <div class="flex gap-2">
                                    <input type="number" 
                                           name="components[1][cost_price]" 
                                           placeholder="سعر التكلفة"
                                           step="0.01"
                                           min="0"
                                           class="w-24 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-xs">
                                    <input type="number" 
                                           name="components[1][selling_price]" 
                                           placeholder="سعر البيع"
                                           step="0.01"
                                           min="0"
                                           class="w-24 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-xs">
                                </div>
                            </div>
                            
                            <button type="button" 
                                    onclick="removeComponentRow(1)"
                                    class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="button" 
                            onclick="addComponentRow()"
                            class="inline-flex items-center px-4 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        إضافة قطعة جديدة
                    </button>
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">الوصف والتفاصيل</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف المنتج
                        </label>
                        <textarea name="description_ar" 
                                  id="description_ar" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('description_ar')) border-red-300 @endif"
                                  placeholder="أدخل وصف تفصيلي للمنتج باللغة العربية">{{ old('description_ar') }}</textarea>
                        @if(isset($errors) && $errors->has('description_ar'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('description_ar') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف المنتج بالإنجليزية
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('description')) border-red-300 @endif"
                                  placeholder="Product description in English">{{ old('description') }}</textarea>
                        @if(isset($errors) && $errors->has('description'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('description') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Media -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">الوسائط</h4>
                
                <!-- نظام الوسائط المتقدم -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        وسائط المنتج <span class="text-blue-600">🎨</span>
                    </label>
                    <div class="space-y-4">
                        <!-- منطقة رفع الوسائط -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200" 
                             id="mediaDropZone">
                            <div class="space-y-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <div>
                                    <p class="text-lg font-medium text-gray-900">اسحب وأفلت الملفات هنا</p>
                                    <p class="text-sm text-gray-500">أو اضغط لاختيار الملفات</p>
                                </div>
                                <input type="file" 
                                       name="media_files[]" 
                                       id="media_files" 
                                       accept="image/*,video/*,.pdf,.doc,.docx"
                                       multiple
                                       class="hidden">
                                <button type="button" 
                                        onclick="document.getElementById('media_files').click()"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    اختيار ملفات
                                </button>
                            </div>
                        </div>
                        
                        <!-- معلومات الملفات المدعومة -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="text-sm font-medium text-blue-900 mb-2">الملفات المدعومة:</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-blue-700">
                                <div>🖼️ الصور: JPG, PNG, GIF, WebP</div>
                                <div>🎥 الفيديوهات: MP4, AVI, MOV</div>
                                <div>📄 المستندات: PDF, DOC, DOCX</div>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">الحد الأقصى: 10 ميجابايت لكل ملف</p>
                        </div>
                        
                        <!-- معاينة الوسائط -->
                        <div id="media_preview" class="hidden">
                            <h5 class="text-sm font-medium text-gray-700 mb-3">معاينة الوسائط:</h5>
                            <div id="media_preview_container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                <!-- ستتم إضافة الوسائط هنا -->
                            </div>
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    💡 يمكنك ترتيب الوسائط بالسحب والإفلات. الصورة الأولى ستكون الصورة الرئيسية للمنتج.
                                </p>
                            </div>
                        </div>
                        
                        @if(isset($errors) && $errors->has('media_files'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('media_files') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Status -->
            <div>
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="mr-2 block text-sm text-gray-900">
                        المنتج نشط
                    </label>
                </div>
            </div>
            
            <!-- Hidden inputs for components -->
            <div id="componentsHiddenInputs">
                <!-- سيتم إضافة hidden inputs للمكونات هنا -->
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 rtl:space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    إلغاء
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ المنتج
                </button>
            </div>
        </form>
    </div>
</div>



<script>

// ===== نظام إدارة مكونات المنتج =====
let componentCounter = 0;

// إضافة صف مكون جديد
function addComponentRow() {
    componentCounter++;
    const container = document.getElementById('componentsContainer');
    
    const componentRow = document.createElement('div');
    componentRow.className = 'flex items-center gap-3 mb-3 p-3 bg-white rounded border border-gray-200';
    componentRow.id = `component_${componentCounter}`;
    
    componentRow.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="components[${componentCounter}][name]" 
                   placeholder="اسم القطعة (مثل: سرير، كمود)"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>
        <div class="w-24">
            <input type="number" 
                   name="components[${componentCounter}][quantity]" 
                   placeholder="الكمية"
                   min="1" 
                   value="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>
        
        <!-- خيار تسعير المكون -->
        <div class="w-32">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="components[${componentCounter}][has_pricing]" 
                       value="1"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                       onchange="toggleComponentPricingRow(${componentCounter})">
                <span class="ml-2 text-xs text-gray-700">تسعير</span>
            </label>
        </div>
        
        <!-- خانات التسعير -->
        <div class="component-pricing-fields hidden" id="pricing_${componentCounter}">
            <div class="flex gap-2">
                <input type="number" 
                       name="components[${componentCounter}][cost_price]" 
                       placeholder="سعر التكلفة"
                       step="0.01"
                       min="0"
                       class="w-24 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-xs">
                <input type="number" 
                       name="components[${componentCounter}][selling_price]" 
                       placeholder="سعر البيع"
                       step="0.01"
                       min="0"
                       class="w-24 px-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-xs">
            </div>
        </div>
        
        <button type="button" 
                onclick="removeComponentRow(${componentCounter})"
                class="px-3 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    `;
    
    container.appendChild(componentRow);
    console.log(`Added component row ${componentCounter}`);
}

// حذف صف مكون
function removeComponentRow(componentId) {
    const componentRow = document.getElementById(`component_${componentId}`);
    if (componentRow) {
        componentRow.remove();
        console.log(`Removed component row ${componentId}`);
    }
}

// تفعيل/إلغاء تفعيل تسعير مكون محدد
function toggleComponentPricingRow(componentId) {
    const checkbox = document.querySelector(`input[name="components[${componentId}][has_pricing]"]`);
    const pricingFields = document.getElementById(`pricing_${componentId}`);
    
    if (checkbox && pricingFields) {
        if (checkbox.checked) {
            pricingFields.classList.remove('hidden');
        } else {
            pricingFields.classList.add('hidden');
        }
        
        console.log(`Component ${componentId} pricing ${checkbox.checked ? 'enabled' : 'disabled'}`);
    }
}

// إعداد الصفحة عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    // تعيين عداد المكونات لـ 1 (لأن الصف الأول موجود بالفعل)
    componentCounter = 1;
    
    // إعداد النموذج لإنشاء hidden inputs عند الإرسال
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            prepareComponentsForSubmission();
            // إرسال النموذج بعد إعداد المكونات
            this.submit();
        });
    }
    
    // عرض المكونات الموجودة (إذا كانت موجودة)
    @if(isset($defaultComponents) && count($defaultComponents) > 0)
        @foreach($defaultComponents as $index => $component)
            if ({{ $index }} > 0) { // تخطي الأول لأنه موجود بالفعل
                addComponentRow();
            }
            const componentRow = document.getElementById('component_{{ $index + 1 }}');
            if (componentRow) {
                const nameInput = componentRow.querySelector('input[name*="[name]"]');
                const quantityInput = componentRow.querySelector('input[name*="[quantity]"]');
                const hasPricingCheckbox = componentRow.querySelector('input[name*="[has_pricing]"]');
                const costPriceInput = componentRow.querySelector('input[name*="[cost_price]"]');
                const sellingPriceInput = componentRow.querySelector('input[name*="[selling_price]"]');
                
                if (nameInput && quantityInput) {
                    nameInput.value = "{{ $component['name'] ?? '' }}";
                    quantityInput.value = "{{ $component['quantity'] ?? 1 }}";
                    
                    // إعداد التسعير إذا كان موجود
                    if (hasPricingCheckbox && "{{ $component['has_pricing'] ?? false }}") {
                        hasPricingCheckbox.checked = true;
                        if (costPriceInput && "{{ $component['cost_price'] ?? '' }}") {
                            costPriceInput.value = "{{ $component['cost_price'] ?? '' }}";
                        }
                        if (sellingPriceInput && "{{ $component['selling_price'] ?? '' }}") {
                            sellingPriceInput.value = "{{ $component['selling_price'] ?? '' }}";
                        }
                        // إظهار خانات التسعير
                        toggleComponentPricingRow({{ $index + 1 }});
                    }
                }
            }
        @endforeach
    @endif
});

// إعداد المكونات للإرسال
function prepareComponentsForSubmission() {
    const hiddenInputsContainer = document.getElementById('componentsHiddenInputs');
    const componentRows = document.querySelectorAll('[id^="component_"]');
    
    // مسح hidden inputs السابقة
    hiddenInputsContainer.innerHTML = '';
    
    let componentIndex = 0;
    componentRows.forEach(row => {
        const nameInput = row.querySelector('input[name*="[name]"]');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const hasPricingCheckbox = row.querySelector('input[name*="[has_pricing]"]');
        const costPriceInput = row.querySelector('input[name*="[cost_price]"]');
        const sellingPriceInput = row.querySelector('input[name*="[selling_price]"]');
        
        if (nameInput && quantityInput && nameInput.value.trim() && quantityInput.value > 0) {
            // إضافة hidden input للاسم
            const nameHidden = document.createElement('input');
            nameHidden.type = 'hidden';
            nameHidden.name = `components[${componentIndex}][name]`;
            nameHidden.value = nameInput.value.trim();
            hiddenInputsContainer.appendChild(nameHidden);
            
            // إضافة hidden input للكمية
            const quantityHidden = document.createElement('input');
            quantityHidden.type = 'hidden';
            quantityHidden.name = `components[${componentIndex}][quantity]`;
            quantityHidden.value = quantityInput.value;
            hiddenInputsContainer.appendChild(quantityHidden);
            
            // إضافة hidden input لخيار التسعير
            if (hasPricingCheckbox) {
                const hasPricingHidden = document.createElement('input');
                hasPricingHidden.type = 'hidden';
                hasPricingHidden.name = `components[${componentIndex}][has_pricing]`;
                hasPricingHidden.value = hasPricingCheckbox.checked ? '1' : '0';
                hiddenInputsContainer.appendChild(hasPricingHidden);
                
                // إضافة أسعار التكلفة والبيع إذا كان التسعير مفعل
                if (hasPricingCheckbox.checked && costPriceInput && sellingPriceInput) {
                    if (costPriceInput.value > 0) {
                        const costPriceHidden = document.createElement('input');
                        costPriceHidden.type = 'hidden';
                        costPriceHidden.name = `components[${componentIndex}][cost_price]`;
                        costPriceHidden.value = costPriceInput.value;
                        hiddenInputsContainer.appendChild(costPriceHidden);
                    }
                    
                    if (sellingPriceInput.value > 0) {
                        const sellingPriceHidden = document.createElement('input');
                        sellingPriceHidden.type = 'hidden';
                        sellingPriceHidden.name = `components[${componentIndex}][selling_price]`;
                        sellingPriceHidden.value = sellingPriceInput.value;
                        hiddenInputsContainer.appendChild(sellingPriceHidden);
                    }
                }
            }
            
            componentIndex++;
        }
    });
    
    console.log(`Prepared ${componentIndex} components for submission`);
}
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    
    const costPriceInput = document.getElementById('cost_price');
    const priceInput = document.getElementById('price');
    const profitPercentageInput = document.getElementById('profit_percentage');

    // حساب السعر تلقائياً عند تغيير سعر التكلفة أو نسبة الربح
    function calculatePrice() {
        const costPrice = parseFloat(costPriceInput.value) || 0;
        const profitPercent = parseFloat(profitPercentageInput.value) || 0;
        
        if (costPrice > 0 && profitPercent >= 0) {
            const price = costPrice * (1 + profitPercent / 100);
            priceInput.value = price.toFixed(2);
        }
    }

    // حساب نسبة الربح تلقائياً عند تغيير السعر
    function calculateProfitPercentage() {
        const costPrice = parseFloat(costPriceInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        
        if (costPrice > 0 && price > 0) {
            const profitPercentage = ((price - costPrice) / costPrice) * 100;
            profitPercentageInput.value = profitPercentage.toFixed(2);
        }
    }

    // حساب السعر عند تغيير سعر التكلفة أو نسبة الربح
    costPriceInput.addEventListener('input', calculatePrice);
    profitPercentageInput.addEventListener('input', calculatePrice);
    
    // حساب نسبة الربح عند تغيير السعر
    priceInput.addEventListener('input', calculateProfitPercentage);



    // ===== نظام الوسائط المتطور =====
    
    // الصورة الأساسية
    const mainImageInput = document.getElementById('main_image');
    const mainImagePreview = document.getElementById('main_image_preview');
    const mainPreviewImg = document.getElementById('main_preview_img');
    const removeMainImageBtn = document.getElementById('remove_main_image');

    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                mainPreviewImg.src = e.target.result;
                mainImagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeMainImageBtn.addEventListener('click', function() {
        mainImageInput.value = '';
        mainImagePreview.classList.add('hidden');
        mainPreviewImg.src = '';
    });

    // صور المعرض
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('gallery_preview');
    const galleryContainer = document.getElementById('gallery_preview_container');
    let galleryFiles = [];

    galleryInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        galleryFiles = [...galleryFiles, ...files];
        updateGalleryPreview();
    });

    function updateGalleryPreview() {
        if (!galleryContainer) return;
        
        galleryContainer.innerHTML = '';
        
        galleryFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group cursor-move';
                previewDiv.draggable = true;
                previewDiv.dataset.index = index;
                
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="معاينة صورة" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                    <button type="button" class="remove-gallery-image absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity" data-index="${index}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center rounded-b-lg">
                        ${index + 1}
                    </div>
                `;
                
                galleryContainer.appendChild(previewDiv);
                
                // إضافة event listener لحذف الصورة
                previewDiv.querySelector('.remove-gallery-image').addEventListener('click', function() {
                    const removeIndex = parseInt(this.dataset.index);
                    galleryFiles.splice(removeIndex, 1);
                    updateGalleryPreview();
                });
            };
            reader.readAsDataURL(file);
        });
        
        if (galleryFiles.length > 0) {
            galleryPreview.classList.remove('hidden');
        } else {
            galleryPreview.classList.add('hidden');
        }
    }

    // تفعيل السحب والإفلات لصور المعرض
    if (galleryContainer) {
        new Sortable(galleryContainer, {
            animation: 150,
            onEnd: function(evt) {
                // إعادة ترتيب المصفوفة حسب الترتيب الجديد
                const newOrder = [];
                galleryContainer.querySelectorAll('[data-index]').forEach((item, newIndex) => {
                    const oldIndex = parseInt(item.dataset.index);
                    newOrder[newIndex] = galleryFiles[oldIndex];
                    item.dataset.index = newIndex;
                });
                galleryFiles = newOrder;
                
                // تحديث الأرقام فقط
                galleryContainer.querySelectorAll('[data-index]').forEach((item, newIndex) => {
                    const numberDiv = item.querySelector('div:last-child');
                    if (numberDiv) {
                        numberDiv.textContent = newIndex + 1;
                    }
                });
            }
        });
    }

    // الفيديوهات
    const videosInput = document.getElementById('videos');
    const videosPreview = document.getElementById('videos_preview');
    const videosContainer = document.getElementById('videos_preview_container');
    let videoFiles = [];

    videosInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        videoFiles = [...videoFiles, ...files];
        updateVideosPreview();
    });

    function updateVideosPreview() {
        videosContainer.innerHTML = '';
        
        videoFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative';
                
                previewDiv.innerHTML = `
                    <div class="bg-gray-100 rounded-lg p-4 border border-gray-300">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                                <p class="text-sm text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                            <button type="button" class="remove-video flex-shrink-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600" data-index="${index}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
                
                videosContainer.appendChild(previewDiv);
                
                // إضافة event listener لحذف الفيديو
                previewDiv.querySelector('.remove-video').addEventListener('click', function() {
                    const removeIndex = parseInt(this.dataset.index);
                    videoFiles.splice(removeIndex, 1);
                    updateVideosPreview();
                });
            };
            reader.readAsDataURL(file);
        });
        
        if (videoFiles.length > 0) {
            videosPreview.classList.remove('hidden');
        } else {
            videosPreview.classList.add('hidden');
        }
    }
    

    

});
</script>
@endpush
@endsection