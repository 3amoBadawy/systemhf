@extends('layouts.app')

@section('title', 'تعديل الحساب - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تعديل الحساب</h1>
        <p class="mt-1 text-sm text-gray-500">تعديل تفاصيل الحساب: {{ $account->name_ar }}</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('accounts.show', $account) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للحساب
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">تفاصيل الحساب</h3>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-red-800">يوجد أخطاء في النموذج</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('accounts.update', $account) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب بالإنجليزية <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $account->name) }}" 
                           required placeholder="Cash Account" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">اسم الحساب بالعربية <span class="text-red-500">*</span></label>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $account->name_ar) }}" 
                           required placeholder="حساب النقدية" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label for="type">نوع الحساب *</label>
                <select id="type" name="type" required class="form-control">
                    <option value="">اختر نوع الحساب</option>
                    @foreach(App\Models\Account::getTypes() as $key => $value)
                        <option value="{{ $key }}" {{ old('type', $account->type) == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="branch_id">الفرع *</label>
                <select id="branch_id" name="branch_id" required class="form-control">
                    <option value="">اختر الفرع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $account->branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name_ar }} ({{ $branch->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="balance">الرصيد الافتتاحي *</label>
            <input type="number" id="balance" name="balance" step="0.01" min="0" 
                   value="{{ old('balance', $account->balance) }}" required placeholder="0.00" class="form-control">
            <small class="form-text">أدخل الرصيد الافتتاحي للحساب</small>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="description">الوصف</label>
            <textarea id="description" name="description" rows="3" 
                      placeholder="وصف مختصر للحساب" class="form-control">{{ old('description', $account->description) }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                <span class="checkmark"></span>
                الحساب نشط
            </label>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ التعديلات</button>
            <a href="{{ route('accounts.show', $account) }}" class="btn-secondary">❌ إلغاء</a>
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


