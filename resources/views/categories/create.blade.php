@extends('layouts.app')

@section('title', 'إضافة فئة جديدة - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة فئة جديدة</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة فئة جديدة للمنتجات</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للفئات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">🏷️ إضافة فئة جديدة</h2>
                <p>أدخل بيانات الفئة الجديدة مثل غرف النوم، غرف المعيشة، المطابخ</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn-secondary">🔙 رجوع للفئات</a>
        </div>
    </div>

    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label for="name">🏷️ اسم الفئة <span style="color: #e53e3e;">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           placeholder="مثال: غرف النوم، غرف المعيشة، المطابخ" 
                           class="form-control @error('name') error @enderror" required>
                    @error('name')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sort_order">🔢 ترتيب العرض</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                           placeholder="0" min="0" class="form-control @error('sort_order') error @enderror">
                    @error('sort_order')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="image">🖼️ صورة الفئة</label>
                    <input type="file" id="image" name="image" accept="image/*" 
                           class="form-control @error('image') error @enderror">
                    <div style="color: #718096; font-size: 0.875rem; margin-top: 0.5rem;">
                        الصيغ المدعومة: JPG, PNG, GIF. الحد الأقصى: 2MB
                    </div>
                    @error('image')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">📝 وصف الفئة</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="أدخل وصف مفصل للفئة..." 
                              class="form-control @error('description') error @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('categories.index') }}" class="btn-secondary">
                🔙 رجوع
            </a>
            <button type="submit" class="btn-primary">
                💾 حفظ الفئة
            </button>
        </div>
    </form>
</div>

<style>
.form-control.error {
    border-color: #e53e3e;
}
.form-control.error:focus {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}
</style>

<script>
// معاينة الصورة
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        console.log('تم اختيار صورة:', file.name);
        // يمكن إضافة معاينة للصورة هنا إذا لزم الأمر
    }
});
</script>
@endsection
