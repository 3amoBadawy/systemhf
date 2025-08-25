@extends('layouts.app')

@section('title', 'تفاصيل الموظف - نظام إدارة معرض الأثاث')

@section('navbar-title', '👥 تفاصيل الموظف')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">👥 تفاصيل الموظف: {{ $employee->first_name }} {{ $employee->last_name }}</h2>
                <p>معلومات مفصلة عن الموظف المحدد</p>
            </div>
            <a href="{{ route('employees.index') }}" class="btn-secondary">🔙 رجوع للموظفين</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <!-- معلومات الموظف -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📋 معلومات الموظف</h3>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">👤 الاسم الأول:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->first_name }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">👤 الاسم الأخير:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->last_name }}</p>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📧 البريد الإلكتروني:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->email }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📱 رقم الهاتف:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->phone }}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">🏢 الفرع:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">
                            @if($employee->branch)
                                {{ $employee->branch->name }}
                            @else
                                <span style="color: #a0aec0;">غير محدد</span>
                            @endif
                        </p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">🎭 الدور:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">
                            @if($employee->role)
                                {{ $employee->role->name_ar }}
                            @else
                                <span style="color: #a0aec0;">غير محدد</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">🆔 رقم الموظف:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">
                            {{ $employee->employee_id ?: 'غير محدد' }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📅 تاريخ التعيين:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">
                            {{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'غير محدد' }}
                        </p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📊 الحالة:</label>
                        <div style="margin-top: 0.5rem;">
                            @if($employee->is_active)
                                <span style="background: #c6f6d5; color: #22543d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">✅ نشط</span>
                            @else
                                <span style="background: #fed7d7; color: #742a2a; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">❌ غير نشط</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📅 تاريخ الإنشاء:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label style="font-weight: 600; color: #2d3748;">🔄 آخر تحديث:</label>
                    <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $employee->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>

        <div>
            <!-- الإجراءات -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">⚙️ الإجراءات</h3>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn-primary" style="text-align: center;">
                        ✏️ تعديل الموظف
                    </a>
                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width: 100%;">
                            🗑️ حذف الموظف
                        </button>
                    </form>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📊 معلومات إضافية</h3>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="color: #4a5568;">مدة العمل:</span>
                    <span style="background: #667eea; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">
                        @if($employee->hire_date)
                            {{ $employee->hire_date->diffForHumans() }}
                        @else
                            غير محدد
                        @endif
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #4a5568;">حالة الحساب:</span>
                    <span style="background: #38b2ac; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">
                        @if($employee->user)
                            ✅ مرتبط بحساب
                        @else
                            ❌ غير مرتبط
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
