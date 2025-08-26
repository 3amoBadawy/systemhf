<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission, ?string $guard = null): Response
    {
        $guard = $guard ?: config('auth.defaults.guard');

        if (! Auth::guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'غير مصرح لك بالوصول'], 403);
            }

            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::guard($guard)->user();

        // التحقق من أن المستخدم نشط
        if (! $user->is_active) {
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json(['error' => 'حسابك معطل'], 403);
            }

            return redirect()->route('login')->withErrors(['error' => 'حسابك معطل']);
        }

        // التحقق من الصلاحية
        if (! $this->hasPermission($user, $permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'ليس لديك صلاحية للوصول لهذا المورد'], 403);
            }

            abort(403, 'ليس لديك صلاحية للوصول لهذا المورد');
        }

        return $next($request);
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    private function hasPermission(?\App\Models\User $user, string $permission): bool
    {
        // المدير لديه جميع الصلاحيات
        if ($this->isAdminUser($user)) {
            return true;
        }

        // التحقق من الصلاحيات المباشرة للمستخدم
        if ($this->hasDirectPermission($user, $permission)) {
            return true;
        }

        // التحقق من صلاحيات الدور
        if ($this->hasRolePermission($user, $permission)) {
            return true;
        }

        // التحقق من الصلاحيات المجمعة
        return $this->checkWildcardPermission($user, $permission);
    }

    /**
     * التحقق من أن المستخدم مدير
     */
    private function isAdminUser(?\App\Models\User $user): bool
    {
        return $user && property_exists($user, 'role_id') && ($user->role_id === 1 || $user->role_id === 2);
    }

    /**
     * التحقق من الصلاحيات المباشرة للمستخدم
     */
    private function hasDirectPermission(?\App\Models\User $user, string $permission): bool
    {
        if (! $user || ! property_exists($user, 'permissions') || ! $user->permissions || ! is_array($user->permissions)) {
            return false;
        }

        return in_array($permission, $user->permissions) || in_array('*', $user->permissions);
    }

    /**
     * التحقق من صلاحيات الدور
     */
    private function hasRolePermission(?\App\Models\User $user, string $permission): bool
    {
        if (! $user || ! property_exists($user, 'role_id') || ! $user->role_id) {
            return false;
        }

        $role = $user->role()->first();

        return $role && method_exists($role, 'hasPermission') && $role->hasPermission($permission);
    }

    /**
     * التحقق من الصلاحيات المجمعة
     */
    private function checkWildcardPermission(?\App\Models\User $user, string $permission): bool
    {
        $parts = explode('.', $permission);
        if (count($parts) < 2) {
            return false;
        }

        $module = $parts[0];
        $modulePermission = $module.'.*';

        return $this->hasModulePermission($user, $modulePermission);
    }

    /**
     * التحقق من صلاحية الوصول للموديول
     */
    private function hasModulePermission(?\App\Models\User $user, string $modulePermission): bool
    {
        if ($this->hasUserModulePermission($user, $modulePermission)) {
            return true;
        }

        return $this->hasRoleModulePermission($user, $modulePermission);
    }

    /**
     * التحقق من صلاحية المستخدم للموديول
     */
    private function hasUserModulePermission(?\App\Models\User $user, string $modulePermission): bool
    {
        if (! $user || ! property_exists($user, 'permissions') || ! $user->permissions) {
            return false;
        }

        return in_array($modulePermission, $user->permissions);
    }

    /**
     * التحقق من صلاحية الدور للموديول
     */
    private function hasRoleModulePermission(?\App\Models\User $user, string $modulePermission): bool
    {
        if (! $user) {
            return false;
        }

        $role = $user->role()->first();

        return $role && method_exists($role, 'hasPermission') && $role->hasPermission($modulePermission);
    }
}
