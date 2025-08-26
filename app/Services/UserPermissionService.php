<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserPermissionService
{
    /**
     * التحقق من أن المستخدم مدير
     */
    public static function isAdminUser(\App\Models\User $user): bool
    {
        return $user->role_id === 1 || $user->role_id === 2; // admin or super_admin
    }

    /**
     * التحقق من الصلاحيات المباشرة
     */
    public static function hasDirectPermission(\App\Models\User $user, string $permission): bool
    {
        if (! $user->permissions || ! is_array($user->permissions)) {
            return false;
        }

        return in_array($permission, $user->permissions) || in_array('*', $user->permissions);
    }

    /**
     * التحقق من صلاحيات الدور
     */
    public static function hasRolePermission(\App\Models\User $user, string $permission): bool
    {
        if (! $user->role_id) {
            return false;
        }

        /** @var \App\Models\Role|null $role */
        $role = $user->role()->first();

        return $role && $role->hasPermission($permission);
    }

    /**
     * التحقق من الصلاحيات المجمعة
     */
    public static function checkWildcardPermission(\App\Models\User $user, string $permission): bool
    {
        $modulePermission = self::getModulePermission($permission);
        if (! $modulePermission) {
            return false;
        }

        return self::hasModulePermission($user, $modulePermission);
    }

    /**
     * الحصول على صلاحية الموديول
     */
    private static function getModulePermission(string $permission): ?string
    {
        $parts = explode('.', $permission);
        if (count($parts) < 2) {
            return null;
        }

        return $parts[0].'.*';
    }

    /**
     * التحقق من صلاحية الموديول
     */
    private static function hasModulePermission(\App\Models\User $user, string $modulePermission): bool
    {
        if (self::hasUserModulePermission($user, $modulePermission)) {
            return true;
        }

        return self::hasRoleModulePermission($user, $modulePermission);
    }

    /**
     * التحقق من صلاحية المستخدم للموديول
     */
    private static function hasUserModulePermission(\App\Models\User $user, string $modulePermission): bool
    {
        return $user->permissions && in_array($modulePermission, $user->permissions);
    }

    /**
     * التحقق من صلاحية الدور للموديول
     */
    private static function hasRoleModulePermission(\App\Models\User $user, string $modulePermission): bool
    {
        $role = $user->role()->first();

        return $role && $role->hasPermission($modulePermission);
    }

    /**
     * الحصول على الصلاحيات المتاحة للمستخدم
     */
    public static function getUserPermissions(): array
    {
        return UserInfoService::getUserPermissions();
    }

    /**
     * الحصول على الصلاحيات المجمعة حسب الموديول
     */
    public static function getGroupedPermissions(): array
    {
        return UserInfoService::getGroupedPermissions();
    }

    /**
     * التحقق من صلاحية الوصول للفرع
     */
    public static function canAccessBranch(int $branchId): bool
    {
        return UserInfoService::canAccessBranch($branchId);
    }

    /**
     * الحصول على معرف الفرع الحالي
     */
    public static function getCurrentBranchId(): ?int
    {
        return UserInfoService::getCurrentBranchId();
    }

    /**
     * التحقق من أن المستخدم نشط
     */
    public static function isUserActive(): bool
    {
        return UserInfoService::isUserActive();
    }

    /**
     * الحصول على دور المستخدم
     */
    public static function getUserRole(): ?string
    {
        return UserInfoService::getUserRole();
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
        return UserInfoService::getUserName();
    }

    /**
     * الحصول على معرف المستخدم
     */
    public static function getUserId(): ?int
    {
        return UserInfoService::getUserId();
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
     * التحقق من وجود صلاحية معينة
     */
    public static function hasPermission(string $permission): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (self::isAdminUser($user)) {
            return true;
        }

        if (self::hasDirectPermission($user, $permission)) {
            return true;
        }

        if (self::hasRolePermission($user, $permission)) {
            return true;
        }

        return self::checkWildcardPermission($user, $permission);
    }
}
