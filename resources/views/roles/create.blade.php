@extends('layouts.app')

@section('title', 'إنشاء دور جديد')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- رأس الصفحة -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إنشاء دور جديد</h1>
                    <p class="mt-2 text-gray-600">أضف دور جديد للنظام مع تحديد الصلاحيات</p>
                </div>
                <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للأدوار
                </a>
            </div>
        </div>

        <!-- نموذج إنشاء الدور -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('roles.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- معلومات الدور الأساسية -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الدور (إنجليزي) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثال: admin, manager, user">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الدور (عربي) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثال: مدير، موظف، مستخدم">
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        وصف الدور
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="وصف مختصر للدور والصلاحيات الممنوحة">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- اختيار الفرع -->
                <div class="mb-8">
                    <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">
                        الفرع
                    </label>
                    <select id="branch_id" name="branch_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الفرع (اختياري)</option>
                        @foreach($branches ?? [] as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- اختيار الصلاحيات -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        الصلاحيات <span class="text-red-500">*</span>
                    </label>
                    
                    @if(isset($permissions) && $permissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permissions as $permission)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $permission->name_ar }}</div>
                                        <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                                        @if($permission->description)
                                            <div class="text-xs text-gray-400 mt-1">{{ $permission->description }}</div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>لا توجد صلاحيات متاحة</p>
                            <a href="{{ route('permissions.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                إنشاء صلاحية جديدة
                            </a>
                        </div>
                    @endif
                    
                    @error('permissions')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex items-center justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
                    <a href="{{ route('roles.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save ml-2"></i>
                        إنشاء الدور
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من اختيار صلاحية واحدة على الأقل
    const form = document.querySelector('form');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    form.addEventListener('submit', function(e) {
        const checkedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked');
        
        if (checkedPermissions.length === 0) {
            e.preventDefault();
            alert('يجب اختيار صلاحية واحدة على الأقل');
            return false;
        }
    });
    
    // البحث في الصلاحيات
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'البحث في الصلاحيات...';
    searchInput.className = 'w-full border border-gray-300 rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent';
    
    const permissionsContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
    if (permissionsContainer) {
        permissionsContainer.parentNode.insertBefore(searchInput, permissionsContainer);
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const permissionLabels = permissionsContainer.querySelectorAll('label');
            
            permissionLabels.forEach(label => {
                const permissionName = label.querySelector('.text-sm.font-medium').textContent.toLowerCase();
                const permissionDesc = label.querySelector('.text-xs.text-gray-500').textContent.toLowerCase();
                
                if (permissionName.includes(searchTerm) || permissionDesc.includes(searchTerm)) {
                    label.style.display = 'block';
                } else {
                    label.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
@endsection





