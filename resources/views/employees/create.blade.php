@extends('layouts.app')

@section('title', 'إضافة موظف جديد - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة موظف جديد</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة موظف جديد للنظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للموظفين
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">👥 إضافة موظف جديد</h2>
                <p>أدخل بيانات الموظف الجديد في النظام</p>
            </div>
            <a href="{{ route('employees.index') }}" class="btn-secondary">🔙 رجوع للموظفين</a>
        </div>
    </div>

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label for="first_name">👤 الاسم الأول <span style="color: #e53e3e;">*</span></label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" 
                           placeholder="أدخل الاسم الأول" 
                           class="form-control @error('first_name') error @enderror" required>
                    @error('first_name')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">👤 الاسم الأخير <span style="color: #e53e3e;">*</span></label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                           placeholder="أدخل الاسم الأخير" 
                           class="form-control @error('last_name') error @enderror" required>
                    @error('last_name')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">📱 رقم الهاتف <span style="color: #e53e3e;">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                           placeholder="أدخل رقم الهاتف" 
                           class="form-control @error('phone') error @enderror" required>
                    @error('phone')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">📧 البريد الإلكتروني <span style="color: #e53e3e;">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           placeholder="أدخل البريد الإلكتروني" 
                           class="form-control @error('email') error @enderror" required>
                    @error('email')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="form-group">
                    <label for="branch_id">🏢 الفرع <span style="color: #e53e3e;">*</span></label>
                    <select id="branch_id" name="branch_id" 
                            class="form-control @error('branch_id') error @enderror" required>
                        <option value="">اختر الفرع</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role_id">🎭 الدور <span style="color: #e53e3e;">*</span></label>
                    <select id="role_id" name="role_id" 
                            class="form-control @error('role_id') error @enderror" required>
                        <option value="">اختر الدور</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="employee_id">🆔 رقم الموظف</label>
                    <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" 
                           placeholder="أدخل رقم الموظف (اختياري)" 
                           class="form-control @error('employee_id') error @enderror">
                    @error('employee_id')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hire_date">📅 تاريخ التعيين</label>
                    <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" 
                           class="form-control @error('hire_date') error @enderror">
                    @error('hire_date')
                        <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('employees.index') }}" class="btn-secondary">
                🔙 رجوع
            </a>
            <button type="submit" class="btn-primary">
                💾 حفظ الموظف
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
@endsection
