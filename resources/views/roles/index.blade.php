@extends('layouts.app')

@section('title', 'إدارة الأدوار')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة الأدوار</h1>
        <p class="text-gray-600">إدارة أدوار المستخدمين والصلاحيات</p>
    </div>

    <!-- رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- شريط الأدوات -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('roles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء دور جديد
                </a>
                
                <button onclick="duplicateSelectedRole()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-copy ml-2"></i>
                    نسخ الدور المحدد
                </button>
                
                <button onclick="exportRoles()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download ml-2"></i>
                    تصدير الأدوار
                </button>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <input type="text" id="searchRoles" placeholder="البحث في الأدوار..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select id="filterBranch" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">جميع الفروع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name_ar }}</option>
                    @endforeach
                </select>
                <select id="filterType" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">جميع الأنواع</option>
                    <option value="system">أدوار نظامية</option>
                    <option value="custom">أدوار مخصصة</option>
                </select>
            </div>
        </div>
    </div>

    <!-- جدول الأدوار -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600">
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفرع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحيات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدمون</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="rolesTableBody">
                    @foreach($roles as $role)
                        <tr class="role-row hover:bg-gray-50" data-role-id="{{ $role->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="role-checkbox rounded border-gray-300 text-blue-600" value="{{ $role->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-user-tag text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $role->name_ar }}</div>
                                        <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $role->description_ar }}</div>
                                <div class="text-sm text-gray-500">{{ $role->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $role->branch ? $role->branch->name_ar : 'غير محدد' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($role->permissions && is_array($role->permissions))
                                        @if(in_array('*', $role->permissions))
                                            <span class="text-green-600 font-medium">جميع الصلاحيات</span>
                                        @else
                                            <span class="text-blue-600">{{ count($role->permissions) }} صلاحية</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">لا توجد صلاحيات</span>
                                    @endif
                                </div>
                                @if($role->permissions && is_array($role->permissions) && !in_array('*', $role->permissions))
                                    <button onclick="showPermissions('{{ $role->id }}')" class="text-blue-600 hover:text-blue-800 text-xs">
                                        عرض التفاصيل
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $role->users_count + $role->employees_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($role->is_system)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-shield-alt ml-1"></i>
                                        نظامي
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-user-edit ml-1"></i>
                                        مخصص
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($role->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check ml-1"></i>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times ml-1"></i>
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('roles.show', $role) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$role->is_system)
                                        <button onclick="duplicateRole({{ $role->id }})" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        @if($role->canBeDeleted())
                                            <button onclick="deleteRole({{ $role->id }})" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($roles->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal عرض الصلاحيات -->
<div id="permissionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-96 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">صلاحيات الدور</h3>
            <div id="permissionsList" class="space-y-2">
                <!-- سيتم تحميل الصلاحيات هنا -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="hidePermissionsModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">تأكيد الحذف</h3>
            <p class="text-gray-600 mb-6">هل أنت متأكد من حذف هذا الدور؟ لا يمكن التراجع عن هذه العملية.</p>
            
            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="hideDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
                <button onclick="confirmDeleteRole()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedRoleId = null;

// تحديد جميع الأدوار
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.role-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// البحث في الأدوار
document.getElementById('searchRoles').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterRoles();
});

// فلترة حسب الفرع
document.getElementById('filterBranch').addEventListener('change', function() {
    filterRoles();
});

// فلترة حسب النوع
document.getElementById('filterType').addEventListener('change', function() {
    filterRoles();
});

// فلترة الأدوار
function filterRoles() {
    const searchTerm = document.getElementById('searchRoles').value.toLowerCase();
    const branchFilter = document.getElementById('filterBranch').value;
    const typeFilter = document.getElementById('filterType').value;
    
    const rows = document.querySelectorAll('.role-row');
    
    rows.forEach(row => {
        let show = true;
        
        // فلترة البحث
        if (searchTerm) {
            const roleName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const roleDesc = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            if (!roleName.includes(searchTerm) && !roleDesc.includes(searchTerm)) {
                show = false;
            }
        }
        
        // فلترة الفرع
        if (branchFilter) {
            const branchCell = row.querySelector('td:nth-child(4)');
            const branchId = branchCell.getAttribute('data-branch-id');
            if (branchId !== branchFilter) {
                show = false;
            }
        }
        
        // فلترة النوع
        if (typeFilter) {
            const typeCell = row.querySelector('td:nth-child(7)');
            const isSystem = typeCell.textContent.includes('نظامي');
            
            if (typeFilter === 'system' && !isSystem) {
                show = false;
            } else if (typeFilter === 'custom' && isSystem) {
                show = false;
            }
        }
        
        row.style.display = show ? 'table-row' : 'none';
    });
}

// عرض الصلاحيات
function showPermissions(roleId) {
    // هنا يمكن إضافة AJAX لجلب الصلاحيات
    const permissions = [
        'users.view',
        'users.create',
        'users.edit',
        'products.view',
        'products.create'
    ];
    
    const permissionsList = document.getElementById('permissionsList');
    permissionsList.innerHTML = '';
    
    permissions.forEach(permission => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        div.innerHTML = `
            <span class="text-sm text-gray-900">${permission}</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-check ml-1"></i>
                مفعل
            </span>
        `;
        permissionsList.appendChild(div);
    });
    
    document.getElementById('permissionsModal').classList.remove('hidden');
}

// إخفاء modal الصلاحيات
function hidePermissionsModal() {
    document.getElementById('permissionsModal').classList.add('hidden');
}

// نسخ دور
function duplicateRole(roleId) {
    if (confirm('هل تريد نسخ هذا الدور؟')) {
        window.location.href = `/roles/${roleId}/duplicate`;
    }
}

// نسخ الدور المحدد
function duplicateSelectedRole() {
    const selectedCheckboxes = document.querySelectorAll('.role-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        alert('يرجى تحديد دور واحد على الأقل');
        return;
    }
    
    if (selectedCheckboxes.length > 1) {
        alert('يرجى تحديد دور واحد فقط للنسخ');
        return;
    }
    
    const roleId = selectedCheckboxes[0].value;
    duplicateRole(roleId);
}

// حذف دور
function deleteRole(roleId) {
    selectedRoleId = roleId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// إخفاء modal الحذف
function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    selectedRoleId = null;
}

// تأكيد حذف الدور
function confirmDeleteRole() {
    if (selectedRoleId) {
        fetch(`/roles/${selectedRoleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الدور');
            }
        });
    }
    hideDeleteModal();
}

// تصدير الأدوار
function exportRoles() {
    const selectedCheckboxes = document.querySelectorAll('.role-checkbox:checked');
    let roleIds = [];
    
    if (selectedCheckboxes.length > 0) {
        selectedCheckboxes.forEach(checkbox => {
            roleIds.push(checkbox.value);
        });
    }
    
    const params = new URLSearchParams();
    if (roleIds.length > 0) {
        params.append('roles', roleIds.join(','));
    }
    
    window.location.href = `/roles/export?${params.toString()}`;
}
</script>
@endpush

@push('styles')
<style>
/* تحسينات للواجهة */
.role-row {
    transition: all 0.2s ease-in-out;
}

.role-row:hover {
    background-color: #f9fafb;
}

/* تحسينات للأزرار */
button, a {
    transition: all 0.2s ease-in-out;
}

button:hover, a:hover {
    transform: translateY(-1px);
}

/* تحسينات للجداول */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* تحسينات للـ Modal */
#permissionsModal, #deleteModal {
    backdrop-filter: blur(4px);
}

/* تحسينات للفلترة */
select, input {
    transition: border-color 0.2s ease-in-out;
}

/* تحسينات للـ Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}

.pagination a, .pagination span {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    text-decoration: none;
    color: #374151;
    transition: all 0.2s ease-in-out;
}

.pagination a:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.pagination .current {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: white;
}
</style>
@endpush
