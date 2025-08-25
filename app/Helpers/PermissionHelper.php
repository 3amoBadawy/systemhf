<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * التحقق من وجود صلاحية معينة
     */
    public static function hasPermission(string $permission): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // المدير لديه جميع الصلاحيات
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        // التحقق من الصلاحيات المباشرة
        if ($user->permissions && is_array($user->permissions)) {
            if (in_array($permission, $user->permissions) || in_array('*', $user->permissions)) {
                return true;
            }
        }

        // التحقق من صلاحيات الدور
        if ($user->role_id && $user->role) {
            if ($user->role->hasPermission($permission)) {
                return true;
            }
        }

        // التحقق من الصلاحيات المجمعة
        return self::checkWildcardPermission($user, $permission);
    }

    /**
     * التحقق من وجود أي من الصلاحيات
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من وجود جميع الصلاحيات
     */
    public static function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! self::hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * التحقق من الصلاحيات المجمعة
     */
    private static function checkWildcardPermission(\Illuminate\Contracts\Auth\Authenticatable $user, string $permission): bool
    {
        $parts = explode('.', $permission);
        if (count($parts) < 2) {
            return false;
        }

        $module = $parts[0];
        $modulePermission = $module.'.*';

        // التحقق من الصلاحيات المباشرة
        if ($user->permissions && in_array($modulePermission, $user->permissions)) {
            return true;
        }

        // التحقق من صلاحيات الدور
        if ($user->role && $user->role->hasPermission($modulePermission)) {
            return true;
        }

        return false;
    }

    /**
     * الحصول على الصلاحيات المتاحة للمستخدم
     */
    public static function getUserPermissions(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $permissions = [];

        // إضافة الصلاحيات المباشرة
        if ($user->permissions && is_array($user->permissions)) {
            $permissions = array_merge($permissions, $user->permissions);
        }

        // إضافة صلاحيات الدور
        if ($user->role_id && $user->role) {
            $rolePermissions = $user->role->permissions ?? [];
            $permissions = array_merge($permissions, $rolePermissions);
        }

        return array_unique($permissions);
    }

    /**
     * الحصول على الصلاحيات المجمعة حسب الموديول
     */
    public static function getGroupedPermissions(): array
    {
        $permissions = self::getUserPermissions();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $group = $parts[0] ?? 'general';
            $grouped[$group][] = $permission;
        }

        return $grouped;
    }

    /**
     * التحقق من صلاحية الوصول للفرع
     */
    public static function canAccessBranch(int $branchId): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // المدير لديه وصول لجميع الفروع
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        // المستخدم يمكنه الوصول لفرعه فقط
        return $user->branch_id === $branchId;
    }

    /**
     * الحصول على معرف الفرع الحالي
     */
    public static function getCurrentBranchId(): ?int
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        // المدير يمكنه الوصول لجميع الفروع
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return session('current_branch_id', $user->branch_id);
        }

        return $user->branch_id;
    }

    /**
     * التحقق من أن المستخدم نشط
     */
    public static function isUserActive(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->is_active;
    }

    /**
     * الحصول على دور المستخدم
     */
    public static function getUserRole(): ?string
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->role;
    }

    /**
     * التحقق من أن المستخدم مدير
     */
    public static function isAdmin(): bool
    {
        $role = self::getUserRole();

        return $role === 'admin' || $role === 'super_admin';
    }

    /**
     * التحقق من أن المستخدم مدير عام
     */
    public static function isSuperAdmin(): bool
    {
        return self::getUserRole() === 'super_admin';
    }

    /**
     * الحصول على اسم المستخدم
     */
    public static function getUserName(): ?string
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->name;
    }

    /**
     * الحصول على معرف المستخدم
     */
    public static function getUserId(): ?int
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->id;
    }

    /**
     * التحقق من صلاحية إدارة المستخدمين
     */
    public static function canManageUsers(): bool
    {
        return self::hasAnyPermission([
            'users.create',
            'users.edit',
            'users.delete',
            'users.roles',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة الموظفين
     */
    public static function canManageEmployees(): bool
    {
        return self::hasAnyPermission([
            'employees.create',
            'employees.edit',
            'employees.delete',
            'employees.attendance',
            'employees.salary',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة المنتجات
     */
    public static function canManageProducts(): bool
    {
        return self::hasAnyPermission([
            'products.create',
            'products.edit',
            'products.delete',
            'products.inventory',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة العملاء
     */
    public static function canManageCustomers(): bool
    {
        return self::hasAnyPermission([
            'customers.create',
            'customers.edit',
            'customers.delete',
            'customers.credit',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة الفواتير
     */
    public static function canManageInvoices(): bool
    {
        return self::hasAnyPermission([
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            'invoices.approve',
            'invoices.cancel',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة المدفوعات
     */
    public static function canManagePayments(): bool
    {
        return self::hasAnyPermission([
            'payments.create',
            'payments.edit',
            'payments.delete',
            'payments.refund',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة المصروفات
     */
    public static function canManageExpenses(): bool
    {
        return self::hasAnyPermission([
            'expenses.create',
            'expenses.edit',
            'expenses.delete',
            'expenses.approve',
        ]);
    }

    /**
     * التحقق من صلاحية عرض التقارير
     */
    public static function canViewReports(): bool
    {
        return self::hasPermission('reports.view');
    }

    /**
     * التحقق من صلاحية تصدير التقارير
     */
    public static function canExportReports(): bool
    {
        return self::hasPermission('reports.export');
    }

    /**
     * التحقق من صلاحية إدارة الإعدادات
     */
    public static function canManageSettings(): bool
    {
        return self::hasAnyPermission([
            'settings.edit',
            'settings.business',
            'settings.system',
            'settings.branches',
        ]);
    }

    /**
     * التحقق من صلاحية إدارة النظام
     */
    public static function canManageSystem(): bool
    {
        return self::hasAnyPermission([
            'system.admin',
            'system.maintenance',
            'system.backup',
            'system.logs',
        ]);
    }
}
