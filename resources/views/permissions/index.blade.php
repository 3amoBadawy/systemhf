@extends('layouts.app')

@section('title', 'إدارة الصلاحيات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة الصلاحيات</h1>
        <p class="text-gray-600">إدارة صلاحيات المستخدمين والأدوار</p>
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
                <a href="{{ route('permissions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus ml-2"></i>
                    إنشاء صلاحية جديدة
                </a>
                
                <button onclick="createBasicPermissions()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-magic ml-2"></i>
                    إنشاء الصلاحيات الأساسية
                </button>
                
                <button onclick="exportPermissions()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download ml-2"></i>
                    تصدير الصلاحيات
                </button>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <input type="text" id="searchPermissions" placeholder="البحث في الصلاحيات..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select id="filterGroup" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">جميع المجموعات</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}">{{ \App\Helpers\SystemHelper::getGroupArabicName($group) }}</option>
                    @endforeach
                </select>
                <select id="filterType" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">جميع الأنواع</option>
                    <option value="system">صلاحيات نظامية</option>
                    <option value="custom">صلاحيات مخصصة</option>
                </select>
            </div>
        </div>
    </div>

    <!-- عرض الصلاحيات حسب المجموعات -->
    <div class="space-y-6">
        @foreach($permissionsByGroup as $group => $permissions)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ \App\Helpers\SystemHelper::getGroupArabicName($group) }}</h3>
                            <p class="text-sm text-gray-600">{{ \App\Helpers\SystemHelper::getGroupDescription($group) }}</p>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $permissions->count() }} صلاحية
                            </span>
                            <button onclick="toggleGroup('{{ $group }}')" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-chevron-down" id="icon-{{ $group }}"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="group-content" id="group-{{ $group }}">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأدوار المستخدمة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدمون</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($permissions as $permission)
                                    <tr class="permission-row hover:bg-gray-50" data-group="{{ $group }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                        <i class="fas fa-key text-green-600 text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $permission->name_ar }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $permission->description_ar }}</div>
                                            <div class="text-sm text-gray-500">{{ $permission->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $permission->roles_count }}
                                            @if($permission->roles_count > 0)
                                                <button onclick="showRoles('{{ $permission->id }}')" class="text-blue-600 hover:text-blue-800 text-xs mr-2">
                                                    عرض
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $permission->users_count }}
                                            @if($permission->users_count > 0)
                                                <button onclick="showUsers('{{ $permission->id }}')" class="text-blue-600 hover:text-blue-800 text-xs mr-2">
                                                    عرض
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($permission->is_system)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-shield-alt ml-1"></i>
                                                    نظامية
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-user-edit ml-1"></i>
                                                    مخصصة
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <a href="{{ route('permissions.show', $permission) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('permissions.edit', $permission) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if(!$permission->is_system)
                                                    <button onclick="duplicatePermission('{{ $permission->id }}')" class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    @if($permission->canBeDeleted())
                                                        <button onclick="deletePermission('{{ $permission->id }}')" class="text-red-600 hover:text-red-900">
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
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal عرض الأدوار -->
<div id="rolesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-96 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">الأدوار المستخدمة</h3>
            <div id="rolesList" class="space-y-2">
                <!-- سيتم تحميل الأدوار هنا -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="hideRolesModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal عرض المستخدمين -->
<div id="usersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-96 overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">المستخدمون المستخدمون</h3>
            <div id="usersList" class="space-y-2">
                <!-- سيتم تحميل المستخدمين هنا -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="hideUsersModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
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
            <p class="text-gray-600 mb-6">هل أنت متأكد من حذف هذه الصلاحية؟ لا يمكن التراجع عن هذه العملية.</p>
            
            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="hideDeleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    إلغاء
                </button>
                <button onclick="confirmDeletePermission()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedPermissionId = null;

// تبديل عرض المجموعة
function toggleGroup(group) {
    const content = document.getElementById(`group-${group}`);
    const icon = document.getElementById(`icon-${group}`);
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.className = 'fas fa-chevron-down';
    } else {
        content.style.display = 'none';
        icon.className = 'fas fa-chevron-right';
    }
}

// البحث في الصلاحيات
document.getElementById('searchPermissions').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterPermissions();
});

// فلترة حسب المجموعة
document.getElementById('filterGroup').addEventListener('change', function() {
    filterPermissions();
});

// فلترة حسب النوع
document.getElementById('filterType').addEventListener('change', function() {
    filterPermissions();
});

// فلترة الصلاحيات
function filterPermissions() {
    const searchTerm = document.getElementById('searchPermissions').value.toLowerCase();
    const groupFilter = document.getElementById('filterGroup').value;
    const typeFilter = document.getElementById('filterType').value;
    
    const rows = document.querySelectorAll('.permission-row');
    
    rows.forEach(row => {
        let show = true;
        
        // فلترة البحث
        if (searchTerm) {
            const permissionName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const permissionDesc = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            if (!permissionName.includes(searchTerm) && !permissionDesc.includes(searchTerm)) {
                show = false;
            }
        }
        
        // فلترة المجموعة
        if (groupFilter) {
            const rowGroup = row.getAttribute('data-group');
            if (rowGroup !== groupFilter) {
                show = false;
            }
        }
        
        // فلترة النوع
        if (typeFilter) {
            const typeCell = row.querySelector('td:nth-child(5)');
            const isSystem = typeCell.textContent.includes('نظامية');
            
            if (typeFilter === 'system' && !isSystem) {
                show = false;
            } else if (typeFilter === 'custom' && isSystem) {
                show = false;
            }
        }
        
        row.style.display = show ? 'table-row' : 'none';
    });
}

// عرض الأدوار
function showRoles(permissionId) {
    // هنا يمكن إضافة AJAX لجلب الأدوار
    const roles = [
        { name: 'مدير', name_ar: 'مدير', description: 'مدير النظام' },
        { name: 'محاسب', name_ar: 'محاسب', description: 'محاسب النظام' }
    ];
    
    const rolesList = document.getElementById('rolesList');
    rolesList.innerHTML = '';
    
    roles.forEach(role => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        div.innerHTML = `
            <div>
                <div class="font-medium text-gray-900">${role.name_ar}</div>
                <div class="text-sm text-gray-500">${role.name}</div>
                <div class="text-xs text-gray-400">${role.description}</div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-user-tag ml-1"></i>
                دور
            </span>
        `;
        rolesList.appendChild(div);
    });
    
    document.getElementById('rolesModal').classList.remove('hidden');
}

// إخفاء modal الأدوار
function hideRolesModal() {
    document.getElementById('rolesModal').classList.add('hidden');
}

// عرض المستخدمين
function showUsers(permissionId) {
    // هنا يمكن إضافة AJAX لجلب المستخدمين
    const users = [
        { name: 'أحمد محمد', email: 'ahmed@example.com', role: 'مدير' },
        { name: 'فاطمة علي', email: 'fatima@example.com', role: 'محاسب' }
    ];
    
    const usersList = document.getElementById('usersList');
    usersList.innerHTML = '';
    
    users.forEach(user => {
        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        div.innerHTML = `
            <div>
                <div class="font-medium text-gray-900">${user.name}</div>
                <div class="text-sm text-gray-500">${user.email}</div>
                <div class="text-xs text-gray-400">${user.role}</div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-user ml-1"></i>
                مستخدم
            </span>
        `;
        usersList.appendChild(div);
    });
    
    document.getElementById('usersModal').classList.remove('hidden');
}

// إخفاء modal المستخدمين
function hideUsersModal() {
    document.getElementById('usersModal').classList.add('hidden');
}

// إنشاء الصلاحيات الأساسية
function createBasicPermissions() {
    if (confirm('هل تريد إنشاء الصلاحيات الأساسية؟')) {
        fetch('{{ route("permissions.create-basic") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إنشاء الصلاحيات الأساسية بنجاح');
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء إنشاء الصلاحيات الأساسية');
            }
        });
    }
}

// نسخ صلاحية
function duplicatePermission(permissionId) {
    if (confirm('هل تريد نسخ هذه الصلاحية؟')) {
        window.location.href = `/permissions/${permissionId}/duplicate`;
    }
}

// حذف صلاحية
function deletePermission(permissionId) {
    selectedPermissionId = permissionId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// إخفاء modal الحذف
function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    selectedPermissionId = null;
}

// تأكيد حذف الصلاحية
function confirmDeletePermission() {
    if (selectedPermissionId) {
        fetch(`/permissions/${selectedPermissionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الصلاحية');
            }
        });
    }
    hideDeleteModal();
}

// تصدير الصلاحيات
function exportPermissions() {
    window.location.href = '{{ route("permissions.export") }}';
}

// إخفاء جميع المجموعات عند التحميل
document.addEventListener('DOMContentLoaded', function() {
    const groups = document.querySelectorAll('.group-content');
    groups.forEach(group => {
        group.style.display = 'none';
    });
    
    // إظهار المجموعة الأولى فقط
    const firstGroup = document.querySelector('.group-content');
    if (firstGroup) {
        firstGroup.style.display = 'block';
    }
});
</script>
@endpush

@push('styles')
<style>
/* تحسينات للواجهة */
.permission-row {
    transition: all 0.2s ease-in-out;
}

.permission-row:hover {
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
#rolesModal, #usersModal, #deleteModal {
    backdrop-filter: blur(4px);
}

/* تحسينات للفلترة */
select, input {
    transition: border-color 0.2s ease-in-out;
}

/* تحسينات للمجموعات */
.group-content {
    transition: all 0.3s ease-in-out;
}

/* تحسينات للرموز */
.fa-chevron-down, .fa-chevron-right {
    transition: transform 0.2s ease-in-out;
}

/* تحسينات للبطاقات */
.bg-gray-50 {
    transition: all 0.2s ease-in-out;
}

.bg-gray-50:hover {
    background-color: #f3f4f6;
}
</style>
@endpush
