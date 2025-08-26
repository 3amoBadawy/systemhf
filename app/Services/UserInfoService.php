<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserInfoService
{
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
        if ($user->permissions) {
            $permissions = array_merge($permissions, $user->permissions);
        }

        // إضافة صلاحيات الدور
        if ($user->role_id) {
            $role = $user->role()->first();
            $rolePermissions = $role && property_exists($role, 'permissions') ? $role->permissions : [];
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
        if ($user->role_id === 1 || $user->role_id === 2) { // admin or super_admin
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
        if ($user->role_id === 1 || $user->role_id === 2) { // admin or super_admin
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

        /** @var \App\Models\Role|null $role */
        $role = $user->role()->first();

        return $role && property_exists($role, 'name') ? $role->name : null;
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
}
