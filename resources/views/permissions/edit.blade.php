@extends('layouts.app')

@section('title', 'تعديل الصلاحية')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">تعديل الصلاحية: {{ $permission->name_ar }}</h1>
                    <p class="mt-2 text-gray-600">قم بتعديل معلومات الصلاحية</p>
                </div>
                <a href="{{ route('permissions.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للصلاحيات
                </a>
            </div>
        </div>

        <!-- نموذج تعديل الصلاحية -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('permissions.update', $permission) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- معلومات الصلاحية الأساسية -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الصلاحية (إنجليزي) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثال: users.create, products.edit">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الصلاحية (عربي) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $permission->name_ar) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثال: إنشاء مستخدم، تعديل منتج">
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-2">
                            المجموعة <span class="text-red-500">*</span>
                        </label>
                        <select id="group" name="group" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر المجموعة</option>
                            <option value="users" {{ old('group', $permission->group) == 'users' ? 'selected' : '' }}>المستخدمون</option>
                            <option value="roles" {{ old('group', $permission->group) == 'roles' ? 'selected' : '' }}>الأدوار</option>
                            <option value="permissions" {{ old('group', $permission->group) == 'permissions' ? 'selected' : '' }}>الصلاحيات</option>
                            <option value="customers" {{ old('group', $permission->group) == 'customers' ? 'selected' : '' }}>العملاء</option>
                            <option value="products" {{ old('group', $permission->group) == 'products' ? 'selected' : '' }}>المنتجات</option>
                            <option value="employees" {{ old('group', $permission->group) == 'employees' ? 'selected' : '' }}>الموظفون</option>
                            <option value="invoices" {{ old('group', $permission->group) == 'invoices' ? 'selected' : '' }}>الفواتير</option>
                            <option value="expenses" {{ old('group', $permission->group) == 'expenses' ? 'selected' : '' }}>المصروفات</option>
                            <option value="reports" {{ old('group', $permission->group) == 'reports' ? 'selected' : '' }}>التقارير</option>
                            <option value="settings" {{ old('group', $permission->group) == 'settings' ? 'selected' : '' }}>الإعدادات</option>
                            <option value="system" {{ old('group', $permission->group) == 'system' ? 'selected' : '' }}>النظام</option>
                        </select>
                        @error('group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="module" class="block text-sm font-medium text-gray-700 mb-2">
                            الوحدة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="module" name="module" value="{{ old('module', $permission->module) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثال: User, Product, Invoice">
                        @error('module')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        وصف الصلاحية
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="وصف مختصر للصلاحية وما تسمح به">{{ old('description', $permission->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- خيارات إضافية -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            ترتيب العرض
                        </label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $permission->sort_order) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_system" name="is_system" value="1" {{ old('is_system', $permission->is_system) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_system" class="mr-2 text-sm font-medium text-gray-700">
                            صلاحية نظامية (لا يمكن حذفها)
                        </label>
                    </div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex items-center justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('permissions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save ml-2"></i>
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث الوحدة تلقائياً عند اختيار المجموعة
    const groupSelect = document.getElementById('group');
    const moduleInput = document.getElementById('module');
    
    groupSelect.addEventListener('change', function() {
        const group = this.value;
        const moduleMap = {
            'users': 'User',
            'roles': 'Role',
            'permissions': 'Permission',
            'customers': 'Customer',
            'products': 'Product',
            'employees': 'Employee',
            'invoices': 'Invoice',
            'expenses': 'Expense',
            'reports': 'Report',
            'settings': 'Setting',
            'system': 'System'
        };
        
        if (moduleMap[group]) {
            moduleInput.value = moduleMap[group];
        }
    });
    
    // التحقق من صحة اسم الصلاحية
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const value = this.value;
        if (value && !value.includes('.')) {
            this.setCustomValidity('يجب أن يحتوي اسم الصلاحية على نقطة (مثال: users.create)');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endpush
@endsection
