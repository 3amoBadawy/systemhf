@extends('layouts.app')

@section('title', 'إضافة فرع جديد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة فرع جديد</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة فرع جديد للمعرض</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('branches.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للفروع
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ إضافة فرع جديد</h2>
        <p>أدخل تفاصيل الفرع الجديد</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-right: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('branches.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="name">الاسم بالإنجليزية *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       required placeholder="Main Branch" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="name_ar">الاسم بالعربية *</label>
                <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                       required placeholder="الفرع الرئيسي" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="code">الكود *</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" 
                       required placeholder="MAIN" class="form-control">
                <small style="color: #6b7280;">يستخدم في النظام (مثل: MAIN, BRANCH1)</small>
            </div>
            
            <div class="form-group">
                <label for="sort_order">ترتيب العرض</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                       min="0" class="form-control">
                <small style="color: #6b7280;">الترتيب في القوائم (الأقل = الأول)</small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                       placeholder="+966 50 123 4567" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       placeholder="branch@example.com" class="form-control">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="manager_name">اسم المدير</label>
            <input type="text" id="manager_name" name="manager_name" value="{{ old('manager_name') }}" 
                   placeholder="اسم مدير الفرع" class="form-control">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="address">العنوان</label>
            <textarea id="address" name="address" rows="3" 
                      placeholder="عنوان الفرع الكامل" class="form-control">{{ old('address') }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="notes">ملاحظات</label>
            <textarea id="notes" name="notes" rows="3" 
                      placeholder="ملاحظات إضافية حول الفرع" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <span>نشط</span>
            </label>
            <small style="color: #6b7280;">الفرع سيكون متاحاً للاستخدام</small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ الفرع</button>
            <a href="{{ route('branches.index') }}" class="btn-secondary">❌ إلغاء</a>
        </div>
    </form>
</div>
@endsection
