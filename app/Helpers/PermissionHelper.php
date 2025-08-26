<?php

namespace App\Helpers;

use App\Services\BusinessPermissionService;
use App\Services\UserPermissionService;

class PermissionHelper
{
    /**
     * التحقق من وجود صلاحية معينة
     */
    public static function hasPermission(string $permission): bool
    {
        return UserPermissionService::hasPermission($permission);
    }

    /**
     * التحقق من وجود أي من الصلاحيات
     */
    public static function hasAnyPermission(array $permissions): bool
    {
        return UserPermissionService::hasAnyPermission($permissions);
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
     * الحصول على الصلاحيات المتاحة للمستخدم
     */
    public static function getUserPermissions(): array
    {
        return UserPermissionService::getUserPermissions();
    }

    /**
     * الحصول على الصلاحيات المجمعة حسب الموديول
     */
    public static function getGroupedPermissions(): array
    {
        return UserPermissionService::getGroupedPermissions();
    }

    /**
     * التحقق من صلاحية الوصول للفرع
     */
    public static function canAccessBranch(int $branchId): bool
    {
        return UserPermissionService::canAccessBranch($branchId);
    }

    /**
     * الحصول على معرف الفرع الحالي
     */
    public static function getCurrentBranchId(): ?int
    {
        return UserPermissionService::getCurrentBranchId();
    }

    /**
     * التحقق من أن المستخدم نشط
     */
    public static function isUserActive(): bool
    {
        return UserPermissionService::isUserActive();
    }

    /**
     * الحصول على دور المستخدم
     */
    public static function getUserRole(): ?string
    {
        return UserPermissionService::getUserRole();
    }

    /**
     * التحقق من أن المستخدم مدير
     */
    public static function isAdmin(): bool
    {
        return UserPermissionService::isAdmin();
    }

    /**
     * التحقق من أن المستخدم مدير عام
     */
    public static function isSuperAdmin(): bool
    {
        return UserPermissionService::isSuperAdmin();
    }

    /**
     * الحصول على اسم المستخدم
     */
    public static function getUserName(): ?string
    {
        return UserPermissionService::getUserName();
    }

    /**
     * الحصول على معرف المستخدم
     */
    public static function getUserId(): ?int
    {
        return UserPermissionService::getUserId();
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
        return BusinessPermissionService::canManageProducts();
    }

    /**
     * التحقق من صلاحية إدارة العملاء
     */
    public static function canManageCustomers(): bool
    {
        return BusinessPermissionService::canManageCustomers();
    }

    /**
     * التحقق من صلاحية إدارة الفواتير
     */
    public static function canManageInvoices(): bool
    {
        return BusinessPermissionService::canManageInvoices();
    }

    /**
     * التحقق من صلاحية إدارة المدفوعات
     */
    public static function canManagePayments(): bool
    {
        return BusinessPermissionService::canManagePayments();
    }

    /**
     * التحقق من صلاحية إدارة المصروفات
     */
    public static function canManageExpenses(): bool
    {
        return BusinessPermissionService::canManageExpenses();
    }

    /**
     * التحقق من صلاحية عرض التقارير
     */
    public static function canViewReports(): bool
    {
        return BusinessPermissionService::canViewReports();
    }

    /**
     * التحقق من صلاحية تصدير التقارير
     */
    public static function canExportReports(): bool
    {
        return BusinessPermissionService::canExportReports();
    }

    /**
     * التحقق من صلاحية إدارة الإعدادات
     */
    public static function canManageSettings(): bool
    {
        return BusinessPermissionService::canManageSettings();
    }

    /**
     * التحقق من صلاحية إدارة النظام
     */
    public static function canManageSystem(): bool
    {
        return BusinessPermissionService::canManageSystem();
    }
}
