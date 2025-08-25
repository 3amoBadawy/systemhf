@extends('layouts.app')

@section('title', 'تعديل المصروف - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل المصروف</h1>
        <p class="mt-1 text-sm text-gray-500">تعديل تفاصيل المصروف: {{ $expense->title_ar }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('expenses.show', $expense) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            عرض المصروف
        </a>
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
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">بيانات المصروف</h3>
        </div>

        @if ($errors->any())
            <div class="mx-6 mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في النموذج</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pr-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('expenses.update', $expense) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        عنوان المصروف بالإنجليزية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $expense->title) }}" 
                           required placeholder="Office Rent" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="title_ar" class="block text-sm font-medium text-gray-700 mb-2">
                        عنوان المصروف بالعربية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $expense->title_ar) }}" 
                           required placeholder="إيجار المكتب" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="amount">المبلغ *</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" 
                       value="{{ old('amount', $expense->amount) }}" required placeholder="0.00" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="category">الفئة *</label>
                <select id="category" name="category" required class="form-control">
                    <option value="">اختر الفئة</option>
                    @foreach(App\Models\Expense::getCategories() as $key => $value)
                        <option value="{{ $key }}" {{ old('category', $expense->category) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="date">تاريخ المصروف *</label>
                <input type="date" id="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" 
                       required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="branch_id">الفرع *</label>
                <select id="branch_id" name="branch_id" required class="form-control">
                    <option value="">اختر الفرع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $expense->branch_id) == $branch->id ? 'selected' : '' }}>
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
                    <option value="{{ $method->id }}" {{ old('payment_method_id', $expense->payment_method_id) == $method->id ? 'selected' : '' }}>
                        {{ $method->name_ar }} ({{ $method->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="description">الوصف</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="وصف تفصيلي للمصروف" class="form-control">{{ old('description', $expense->description) }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="notes">ملاحظات</label>
            <textarea id="notes" name="notes" rows="2" 
                      placeholder="ملاحظات إضافية" class="form-control">{{ old('notes', $expense->notes) }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ التعديلات</button>
            <a href="{{ route('expenses.show', $expense) }}" class="btn-secondary">❌ إلغاء</a>
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


