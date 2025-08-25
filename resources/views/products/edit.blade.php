@extends('layouts.app')

@section('title', 'تعديل المنتج - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل المنتج</h1>
        <p class="mt-1 text-sm text-gray-500">تعديل بيانات المنتج: {{ $product->name_ar }}</p>
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
            <p class="mt-1 text-sm text-gray-500">تعديل بيانات المنتج</p>
        </div>
        
        <form id="productForm" method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
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
                               value="{{ old('name_ar', $product->name_ar) }}"
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
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('name')) border-red-300 @endif"
                               placeholder="Product name in English">
                        @if(isset($errors) && $errors->has('name'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
            
            <!-- Description -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">الوصف</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف المنتج باللغة العربية
                        </label>
                        <textarea name="description_ar" 
                                  id="description_ar" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('description_ar')) border-red-300 @endif"
                                  placeholder="أدخل وصف المنتج باللغة العربية">{{ old('description_ar', $product->description_ar) }}</textarea>
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
                                  placeholder="Product description in English">{{ old('description', $product->description) }}</textarea>
                        @if(isset($errors) && $errors->has('description'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('description') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Pricing -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">التسعير</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                            سعر التكلفة <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                {{ $currencySymbol }}
                            </span>
                            <input type="number" 
                                   name="cost_price" 
                                   id="cost_price" 
                                   value="{{ old('cost_price', $product->cost_price) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pr-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('cost_price')) border-red-300 @endif"
                                   placeholder="0.00"
                                   required>
                        </div>
                        @if(isset($errors) && $errors->has('cost_price'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('cost_price') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="profit_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                            نسبة الربح (%)
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">%</span>
                            <input type="number" 
                                   name="profit_percentage" 
                                   id="profit_percentage" 
                                   value="{{ old('profit_percentage', $product->profit_percentage) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pr-8 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('profit_percentage')) border-red-300 @endif"
                                   placeholder="0">
                        </div>
                        @if(isset($errors) && $errors->has('profit_percentage'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('profit_percentage') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            سعر البيع <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                {{ $currencySymbol }}
                            </span>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   value="{{ old('price', $product->price) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pr-12 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @if(isset($errors) && $errors->has('price')) border-red-300 @endif"
                                   placeholder="0.00"
                                   required>
                        </div>
                        @if(isset($errors) && $errors->has('price'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('price') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Media -->
            <div>
                <h4 class="text-md font-medium text-gray-900 mb-4">الوسائط</h4>
                
                <!-- الوسائط الحالية -->
                @if($product->media && $product->media->count() > 0)
                <div class="mb-6">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">الوسائط الحالية:</h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($product->media as $media)
                        <div class="relative group">
                            @if($media->isImage())
                                <img src="{{ $media->thumbnail_url }}" alt="{{ $media->alt_text }}" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                            @elseif($media->isVideo())
                                <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-1 py-0.5 rounded-tl-lg">
                                {{ $media->isImage() ? '🖼️' : ($media->isVideo() ? '🎥' : '📄') }}
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center rounded-b-lg">
                                {{ $loop->iteration }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-600 mt-2">
                        💡 سيتم استبدال الوسائط الحالية بالوسائط الجديدة عند التحديث
                    </p>
                </div>
                @endif
                
                <!-- نظام الوسائط المتقدم -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        وسائط جديدة <span class="text-blue-600">🎨</span>
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
                            <h5 class="text-sm font-medium text-gray-700 mb-3">معاينة الوسائط الجديدة:</h5>
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
                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="mr-2 block text-sm text-gray-900">
                        المنتج نشط
                    </label>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    تحديث المنتج
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== نظام الوسائط المتقدم الجديد =====
    
    // عناصر نظام الوسائط
    const mediaInput = document.getElementById('media_files');
    const mediaDropZone = document.getElementById('mediaDropZone');
    const mediaPreview = document.getElementById('media_preview');
    const mediaContainer = document.getElementById('media_preview_container');
    let mediaFiles = [];

    // معالجة اختيار الملفات
    mediaInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        mediaFiles = [...mediaFiles, ...files];
        updateMediaPreview();
    });

    // معالجة السحب والإفلات
    mediaDropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        mediaDropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    mediaDropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        mediaDropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    mediaDropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        mediaDropZone.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = Array.from(e.dataTransfer.files);
        mediaFiles = [...mediaFiles, ...files];
        updateMediaPreview();
    });

    // تحديث معاينة الوسائط
    function updateMediaPreview() {
        if (!mediaContainer) return;
        
        mediaContainer.innerHTML = '';
        
        mediaFiles.forEach((file, index) => {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative group cursor-move';
            previewDiv.draggable = true;
            previewDiv.dataset.index = index;
            
            let previewContent = '';
            let fileType = 'document';
            
            if (file.type.startsWith('image/')) {
                fileType = 'image';
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContent = `
                        <img src="${e.target.result}" alt="معاينة صورة" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                    `;
                    updatePreviewContent(previewDiv, previewContent, index, fileType);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                fileType = 'video';
                previewContent = `
                    <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                `;
                updatePreviewContent(previewDiv, previewContent, index, fileType);
            } else {
                fileType = 'document';
                previewContent = `
                    <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                `;
                updatePreviewContent(previewDiv, previewContent, index, fileType);
            }
        });
        
        if (mediaFiles.length > 0) {
            mediaPreview.classList.remove('hidden');
        } else {
            mediaPreview.classList.add('hidden');
        }
    }

    // تحديث محتوى المعاينة
    function updatePreviewContent(previewDiv, content, index, fileType) {
        previewDiv.innerHTML = `
            ${content}
            <button type="button" class="remove-media-file absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity" data-index="${index}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center rounded-b-lg">
                ${index + 1}
            </div>
            <div class="absolute top-0 left-0 bg-blue-500 text-white text-xs px-1 py-0.5 rounded-tl-lg">
                ${fileType === 'image' ? '🖼️' : fileType === 'video' ? '🎥' : '📄'}
            </div>
        `;
        
        mediaContainer.appendChild(previewDiv);
        
        // إضافة event listener لحذف الملف
        previewDiv.querySelector('.remove-media-file').addEventListener('click', function() {
            const removeIndex = parseInt(this.dataset.index);
            mediaFiles.splice(removeIndex, 1);
            updateMediaPreview();
        });
    }

    // تفعيل السحب والإفلات للوسائط
    if (mediaContainer) {
        new Sortable(mediaContainer, {
            animation: 150,
            onEnd: function(evt) {
                // إعادة ترتيب المصفوفة حسب الترتيب الجديد
                const newOrder = [];
                mediaContainer.querySelectorAll('[data-index]').forEach((item, newIndex) => {
                    const oldIndex = parseInt(item.dataset.index);
                    newOrder[newIndex] = mediaFiles[oldIndex];
                });
                mediaFiles = newOrder;
                updateMediaPreview();
            }
        });
    }
});
</script>
@endpush
