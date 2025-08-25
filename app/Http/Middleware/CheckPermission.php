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

        $user = Auth::guard($guard)->user();

        // التحقق من أن المستخدم نشط
        if (! $user->is_active) {
            Auth::guard($guard)->logout();

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
    private function hasPermission(?\Illuminate\Contracts\Auth\Authenticatable $user, string $permission): bool
    {
        // المدير لديه جميع الصلاحيات
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        // التحقق من الصلاحيات المباشرة للمستخدم
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
        if ($this->checkWildcardPermission($user, $permission)) {
            return true;
        }

        return false;
    }

    /**
     * التحقق من الصلاحيات المجمعة
     */
    private function checkWildcardPermission(?\Illuminate\Contracts\Auth\Authenticatable $user, string $permission): bool
    {
        $parts = explode('.', $permission);
        if (count($parts) < 2) {
            return false;
        }

        $module = $parts[0];
        $parts[1] ?? '*';

        // التحقق من صلاحية الوصول للموديول كاملاً
        $modulePermission = $module.'.*';

        if ($user->permissions && in_array($modulePermission, $user->permissions)) {
            return true;
        }

        if ($user->role && $user->role->hasPermission($modulePermission)) {
            return true;
        }

        return false;
    }
}
