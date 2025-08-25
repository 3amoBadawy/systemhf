@extends('layouts.app')

@section('title', 'تعديل طريقة الدفع - نظام إدارة معرض الأثاث')

@section('navbar-title', '✏️ تعديل طريقة الدفع')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">✏️ تعديل طريقة الدفع: {{ $paymentMethod->name_ar }}</h2>
        <p>تعديل تفاصيل طريقة الدفع</p>
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

    <form method="POST" action="{{ route('payment-methods.update', $paymentMethod) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="name">الاسم بالإنجليزية *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" 
                       required placeholder="Cash" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="name_ar">الاسم بالعربية *</label>
                <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $paymentMethod->name_ar) }}" 
                       required placeholder="نقداً" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="code">الكود *</label>
                <input type="text" id="code" name="code" value="{{ old('code', $paymentMethod->code) }}" 
                       required placeholder="CASH" class="form-control">
                <small class="form-text">كود فريد لطريقة الدفع</small>
            </div>
            
            <div class="form-group">
                <label for="sort_order">ترتيب العرض</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}" 
                       min="0" placeholder="0" class="form-control">
                <small class="form-text">الترتيب في القوائم (الأقل = الأول)</small>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="description">الوصف</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="وصف مختصر لطريقة الدفع" class="form-control">{{ old('description', $paymentMethod->description) }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}>
                <span class="checkmark"></span>
                طريقة الدفع نشطة
            </label>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ التعديلات</button>
            <a href="{{ route('payment-methods.show', $paymentMethod) }}" class="btn-secondary">❌ إلغاء</a>
        </div>
    </form>
</div>

<style>
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-text {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-weight: 500;
    color: #374151;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    position: relative;
    transition: all 0.2s;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #667eea;
    border-color: #667eea;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 14px;
    font-weight: bold;
}

@media (max-width: 768px) {
    .card-header {
        text-align: center;
    }
    
    form > div:first-child,
    form > div:nth-child(2) {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection


