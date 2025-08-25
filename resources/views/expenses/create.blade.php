@extends('layouts.app')

@section('title', 'إضافة مصروف جديد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة مصروف جديد</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة مصروف جديد للنظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للمصروفات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ إضافة مصروف جديد</h2>
        <p>أدخل تفاصيل المصروف</p>
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

    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="title">عنوان المصروف بالإنجليزية *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                       required placeholder="Office Rent" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="title_ar">عنوان المصروف بالعربية *</label>
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" 
                       required placeholder="إيجار المكتب" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="amount">المبلغ *</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" 
                       value="{{ old('amount') }}" required placeholder="0.00" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="category">الفئة *</label>
                <select id="category" name="category" required class="form-control">
                    <option value="">اختر الفئة</option>
                    @foreach(App\Models\Expense::getCategories() as $key => $value)
                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="date">تاريخ المصروف *</label>
                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" 
                       required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="branch_id">الفرع *</label>
                <select id="branch_id" name="branch_id" required class="form-control">
                    <option value="">اختر الفرع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name_ar }} ({{ $branch->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="payment_method_id">طريقة الدفع *</label>
            <select id="payment_method_id" name="payment_method_id" required class="form-control">
                <option value="">اختر طريقة الدفع</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                        {{ $method->name_ar }} ({{ $method->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="description">الوصف</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="وصف تفصيلي للمصروف" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="notes">ملاحظات</label>
            <textarea id="notes" name="notes" rows="2" 
                      placeholder="ملاحظات إضافية" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ المصروف</button>
            <a href="{{ route('expenses.index') }}" class="btn-secondary">❌ إلغاء</a>
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

@media (max-width: 768px) {
    .card-header {
        text-align: center;
    }
    
    form > div:first-child,
    form > div:nth-child(2),
    form > div:nth-child(3) {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
