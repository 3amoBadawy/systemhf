<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $guard = $guard ?: config('auth.defaults.guard');

        if (! Auth::guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'غير مصرح لك بالوصول'], 403);
            }

            return redirect()->route('login');
        }

        $user = Auth::guard($guard)->user();

        // المدير لديه وصول لجميع الفروع
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return $next($request);
        }

        // التحقق من أن المستخدم مرتبط بفرع
        if (! $user->branch_id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'لم يتم تعيين فرع لك'], 403);
            }

            abort(403, 'لم يتم تعيين فرع لك');
        }

        // التحقق من أن الفرع نشط
        if (! $user->branch || ! $user->branch->is_active) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'الفرع غير نشط'], 403);
            }

            abort(403, 'الفرع غير نشط');
        }

        // تعيين الفرع في الجلسة
        session(['current_branch_id' => $user->branch_id]);

        // التحقق من أن المستخدم يطلب بيانات من فرعه فقط
        $requestedBranchId = $this->getRequestedBranchId($request);

        if ($requestedBranchId && $requestedBranchId != $user->branch_id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'لا يمكنك الوصول لبيانات فرع آخر'], 403);
            }

            abort(403, 'لا يمكنك الوصول لبيانات فرع آخر');
        }

        return $next($request);
    }

    /**
     * الحصول على معرف الفرع المطلوب من الطلب
     */
    private function getRequestedBranchId(Request $request): ?int
    {
        // من URL parameters
        if ($request->route('branch')) {
            return (int) $request->route('branch');
        }

        if ($request->route('branchId')) {
            return (int) $request->route('branchId');
        }

        // من query parameters
        if ($request->has('branch_id')) {
            return (int) $request->get('branch_id');
        }

        // من request body
        if ($request->has('branch_id')) {
            return (int) $request->input('branch_id');
        }

        // من headers
        if ($request->header('X-Branch-ID')) {
            return (int) $request->header('X-Branch-ID');
        }

        return null;
    }
}
