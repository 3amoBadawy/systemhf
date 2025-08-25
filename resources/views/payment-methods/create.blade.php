@extends('layouts.app')

@section('title', 'إضافة طريقة دفع جديدة - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة طريقة دفع جديدة</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة طريقة دفع جديدة للنظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('payment-methods.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة لطرق الدفع
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ إضافة طريقة دفع جديدة</h2>
        <p>أدخل تفاصيل طريقة الدفع</p>
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

    <form method="POST" action="{{ route('payment-methods.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="name">الاسم بالإنجليزية *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                       required placeholder="Cash" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="name_ar">الاسم بالعربية *</label>
                <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" 
                       required placeholder="نقداً" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="code">الكود *</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" 
                       required placeholder="cash" class="form-control">
                <small style="color: #6b7280;">يستخدم في النظام (مثل: cash, check, bank_transfer)</small>
            </div>
            
            <div class="form-group">
                <label for="sort_order">ترتيب العرض</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                       min="0" class="form-control">
                <small style="color: #6b7280;">الترتيب في القوائم (الأقل = الأول)</small>
            </div>
        </div>

        <!-- حقول الفروع المطلوبة -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="branch_id">الفرع *</label>
                <select id="branch_id" name="branch_id" required class="form-control">
                    <option value="">اختر الفرع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                <small style="color: #6b7280;">الفرع الذي ستستخدم فيه طريقة الدفع</small>
            </div>
            
            <div class="form-group">
                <label for="initial_balance">الرصيد الابتدائي</label>
                <input type="number" id="initial_balance" name="initial_balance" 
                       value="{{ old('initial_balance', 0) }}" 
                       step="0.01" min="0" class="form-control">
                <small style="color: #6b7280;">الرصيد الابتدائي للحساب المالي (اختياري)</small>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea id="description" name="description" rows="3" 
                          placeholder="وصف طريقة الدفع (اختياري)" class="form-control">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="is_active" name="is_active" value="1" 
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <span>نشط</span>
            </label>
            <small style="color: #6b7280;">طريقة الدفع ستكون متاحة للاستخدام</small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ طريقة الدفع</button>
            <a href="{{ route('payment-methods.index') }}" class="btn-secondary">❌ إلغاء</a>
        </div>
    </form>
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

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2d3748;
    font-weight: 600;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    background: white;
    color: #2d3748;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:invalid {
    border-color: #e53e3e;
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

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-error {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
</style>
@endsection
