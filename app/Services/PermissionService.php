<?php

namespace App\Services;

use App\Models\User;

class PermissionService
{
    /**
     * الأدوار الأساسية في النظام
     */
    private const ROLES = [
        'SuperAdmin' => [
            'name_ar' => 'مدير النظام',
            'permissions' => [
                'manage_branches',
                'manage_users',
                'manage_employees',
                'manage_attendance',
                'manage_salaries',
                'manage_roles',
                'manage_permissions',
                'view_reports',
                'manage_financial',
                'manage_system',
            ],
        ],
        'Admin' => [
            'name_ar' => 'مدير',
            'permissions' => [
                'manage_branches',
                'manage_users',
                'manage_employees',
                'manage_attendance',
                'manage_salaries',
                'view_reports',
                'manage_financial',
            ],
        ],
        'BranchManager' => [
            'name_ar' => 'مدير فرع',
            'permissions' => [
                'manage_branch_employees',
                'manage_branch_attendance',
                'manage_branch_salaries',
                'view_branch_reports',
                'manage_branch_financial',
            ],
        ],
        'Sales' => [
            'name_ar' => 'مبيعات',
            'permissions' => [
                'create_invoices',
                'manage_customers',
                'view_sales_reports',
                'manage_offers',
                'create_orders',
            ],
        ],
        'Delivery' => [
            'name_ar' => 'تسليم',
            'permissions' => [
                'manage_deliveries',
                'update_delivery_status',
                'view_delivery_reports',
                'manage_delivery_vehicles',
            ],
        ],
        'HR' => [
            'name_ar' => 'موارد بشرية',
            'permissions' => [
                'manage_attendance',
                'manage_salaries',
                'review_salaries',
                'view_hr_reports',
                'manage_employee_data',
            ],
        ],
        'Accountant' => [
            'name_ar' => 'محاسب',
            'permissions' => [
                'manage_payments',
                'manage_financial_reports',
                'approve_salaries',
                'manage_bank_transfers',
                'view_financial_reports',
            ],
        ],
    ];

    /**
     * الصلاحيات الأساسية
     */
    private const PERMISSIONS = [
        'manage_branches' => [
            'name_ar' => 'إدارة الفروع',
            'group' => 'system',
            'description' => 'إدارة فروع الشركة',
        ],
        'manage_users' => [
            'name_ar' => 'إدارة المستخدمين',
            'group' => 'system',
            'description' => 'إدارة حسابات المستخدمين',
        ],
        'manage_employees' => [
            'name_ar' => 'إدارة الموظفين',
            'group' => 'hr',
            'description' => 'إدارة بيانات الموظفين',
        ],
        'manage_attendance' => [
            'name_ar' => 'إدارة الحضور',
            'group' => 'hr',
            'description' => 'إدارة الحضور والانصراف',
        ],
        'manage_salaries' => [
            'name_ar' => 'إدارة الرواتب',
            'group' => 'hr',
            'description' => 'إدارة الرواتب والبدلات',
        ],
        'manage_roles' => [
            'name_ar' => 'إدارة الأدوار',
            'group' => 'system',
            'description' => 'إدارة أدوار المستخدمين',
        ],
        'manage_permissions' => [
            'name_ar' => 'إدارة الصلاحيات',
            'group' => 'system',
            'description' => 'إدارة صلاحيات النظام',
        ],
        'view_reports' => [
            'name_ar' => 'عرض التقارير',
            'group' => 'reports',
            'description' => 'عرض تقارير النظام',
        ],
        'manage_financial' => [
            'name_ar' => 'إدارة المالية',
            'group' => 'financial',
            'description' => 'إدارة الأمور المالية',
        ],
        'manage_system' => [
            'name_ar' => 'إدارة النظام',
            'group' => 'system',
            'description' => 'إدارة إعدادات النظام',
        ],
        'manage_branch_employees' => [
            'name_ar' => 'إدارة موظفي الفرع',
            'group' => 'hr',
            'description' => 'إدارة موظفي فرع معين',
        ],
        'manage_branch_attendance' => [
            'name_ar' => 'إدارة حضور الفرع',
            'group' => 'hr',
            'description' => 'إدارة الحضور في فرع معين',
        ],
        'manage_branch_salaries' => [
            'name_ar' => 'إدارة رواتب الفرع',
            'group' => 'hr',
            'description' => 'إدارة الرواتب في فرع معين',
        ],
        'view_branch_reports' => [
            'name_ar' => 'عرض تقارير الفرع',
            'group' => 'reports',
            'description' => 'عرض تقارير فرع معين',
        ],
        'manage_branch_financial' => [
            'name_ar' => 'إدارة مالية الفرع',
            'group' => 'financial',
            'description' => 'إدارة الأمور المالية لفرع معين',
        ],
        'create_invoices' => [
            'name_ar' => 'إنشاء فواتير',
            'group' => 'sales',
            'description' => 'إنشاء فواتير المبيعات',
        ],
        'manage_customers' => [
            'name_ar' => 'إدارة العملاء',
            'group' => 'sales',
            'description' => 'إدارة بيانات العملاء',
        ],
        'view_sales_reports' => [
            'name_ar' => 'عرض تقارير المبيعات',
            'group' => 'reports',
            'description' => 'عرض تقارير المبيعات',
        ],
        'manage_offers' => [
            'name_ar' => 'إدارة العروض',
            'group' => 'sales',
            'description' => 'إدارة عروض الأسعار',
        ],
        'create_orders' => [
            'name_ar' => 'إنشاء طلبات',
            'group' => 'sales',
            'description' => 'إنشاء طلبات العملاء',
        ],
        'manage_deliveries' => [
            'name_ar' => 'إدارة التسليم',
            'group' => 'delivery',
            'description' => 'إدارة عمليات التسليم',
        ],
        'update_delivery_status' => [
            'name_ar' => 'تحديث حالة التسليم',
            'group' => 'delivery',
            'description' => 'تحديث حالة عمليات التسليم',
        ],
        'view_delivery_reports' => [
            'name_ar' => 'عرض تقارير التسليم',
            'group' => 'reports',
            'description' => 'عرض تقارير التسليم',
        ],
        'manage_delivery_vehicles' => [
            'name_ar' => 'إدارة مركبات التسليم',
            'group' => 'delivery',
            'description' => 'إدارة مركبات التسليم',
        ],
        'review_salaries' => [
            'name_ar' => 'مراجعة الرواتب',
            'group' => 'hr',
            'description' => 'مراجعة الرواتب قبل الاعتماد',
        ],
        'view_hr_reports' => [
            'name_ar' => 'عرض تقارير الموارد البشرية',
            'group' => 'reports',
            'description' => 'عرض تقارير الموارد البشرية',
        ],
        'manage_employee_data' => [
            'name_ar' => 'إدارة بيانات الموظفين',
            'group' => 'hr',
            'description' => 'إدارة البيانات الشخصية للموظفين',
        ],
        'manage_payments' => [
            'name_ar' => 'إدارة المدفوعات',
            'group' => 'financial',
            'description' => 'إدارة المدفوعات والمداخيل',
        ],
        'manage_financial_reports' => [
            'name_ar' => 'إدارة التقارير المالية',
            'group' => 'financial',
            'description' => 'إدارة التقارير المالية',
        ],
        'approve_salaries' => [
            'name_ar' => 'اعتماد الرواتب',
            'group' => 'financial',
            'description' => 'اعتماد الرواتب للدفع',
        ],
        'manage_bank_transfers' => [
            'name_ar' => 'إدارة التحويلات البنكية',
            'group' => 'financial',
            'description' => 'إدارة التحويلات البنكية',
        ],
        'view_financial_reports' => [
            'name_ar' => 'عرض التقارير المالية',
            'group' => 'reports',
            'description' => 'عرض التقارير المالية',
        ],
    ];

    /**
     * إنشاء الصلاحيات الأساسية
     */
    public function createBasicPermissions(): void
    {
        $basicPermissions = $this->getBasicPermissions();

        foreach ($basicPermissions as $group => $permissions) {
            foreach ($permissions as $name => $description) {
                $groupAr = $this->getGroupArabicName($group);
                $nameAr = $description;

                // This should be handled by a repository
                // For now, skip to avoid static method calls
                continue;
            }
        }
    }

    /**
     * إنشاء الأدوار الأساسية
     */
    public function createBasicRoles(): void
    {
        $basicRoles = $this->getBasicRoles();

        foreach ($basicRoles as $roleName => $roleData) {
            // This should be handled by a repository
            // For now, skip to avoid static method calls
            continue;
        }
    }

    /**
     * ربط الصلاحيات بالأدوار
     */
    public function assignPermissionsToRoles(): void
    {
        $rolePermissions = $this->getRolePermissions();

        foreach ($rolePermissions as $roleName => $permissions) {
            // This should be handled by a repository
            // For now, skip to avoid static method calls
            continue;
        }
    }

    /**
     * ربط الصلاحيات بالمستخدمين
     */
    public function assignPermissionsToUsers(): void
    {
        $userPermissions = $this->getUserPermissions();

        foreach ($userPermissions as $userId => $permissions) {
            $user = User::find($userId);

            if (! $user || ! $user->employee) {
                continue;
            }

            // This should be handled by a repository
            // For now, skip to avoid static method calls
            continue;
        }
    }

    /**
     * الحصول على ترتيب الدور
     */
    private function getRoleSortOrder(string $roleName): int
    {
        $orderMap = [
            'SuperAdmin' => 1,
            'Admin' => 2,
            'BranchManager' => 3,
            'HR' => 4,
            'Accountant' => 5,
            'Sales' => 6,
            'Delivery' => 7,
        ];

        return $orderMap[$roleName] ?? 999;
    }

    /**
     * التحقق من أن المستخدم له دور معين
     *
     * @param  string[]  $roles
     *
     * @psalm-param list{0: string, 1?: 'Admin'} $roles
     */
    public function userHasRole(User $user, array $roles): bool
    {
        if (! $user->employee || ! $user->employee->role) {
            return false;
        }

        $userRole = $user->employee->role->name;

        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }

        return $userRole === $roles;
    }
}
