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

        if (! $this->isUserAuthenticated($guard)) {
            return $this->handleUnauthenticated($request);
        }

        $user = Auth::guard($guard)->user();

        // المدير لديه وصول لجميع الفروع
        if ($this->isAdminUser($user)) {
            return $next($request);
        }

        // التحقق من أن المستخدم مرتبط بفرع
        if (! $this->hasUserBranch($user)) {
            return $this->handleNoBranchAssigned($request);
        }

        // التحقق من أن الفرع نشط
        if (! $this->isUserBranchActive($user)) {
            return $this->handleInactiveBranch($request);
        }

        // تعيين الفرع في الجلسة
        session(['current_branch_id' => $user->branch_id]);

        // التحقق من أن المستخدم يطلب بيانات من فرعه فقط
        if (! $this->canAccessRequestedBranch($request, $user->branch_id)) {
            return $this->handleBranchAccessDenied($request);
        }

        return $next($request);
    }

    /**
     * التحقق من أن المستخدم مسجل دخول
     */
    private function isUserAuthenticated(string $guard): bool
    {
        return Auth::guard($guard)->check();
    }

    /**
     * معالجة المستخدم غير المسجل دخول
     */
    private function handleUnauthenticated(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'غير مصرح لك بالوصول'], 403);
        }

        return redirect()->route('login');
    }

    /**
     * التحقق من أن المستخدم مدير
     */
    private function isAdminUser($user): bool
    {
        return $user && property_exists($user, 'role') && ($user->role === 'admin' || $user->role === 'super_admin');
    }

    /**
     * التحقق من أن المستخدم مرتبط بفرع
     */
    private function hasUserBranch($user): bool
    {
        return $user && property_exists($user, 'branch_id') && $user->branch_id;
    }

    /**
     * معالجة المستخدم بدون فرع
     */
    private function handleNoBranchAssigned(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'لم يتم تعيين فرع لك'], 403);
        }

        abort(403, 'لم يتم تعيين فرع لك');
    }

    /**
     * التحقق من أن فرع المستخدم نشط
     */
    private function isUserBranchActive($user): bool
    {
        return $user && property_exists($user, 'branch') && $user->branch && property_exists($user->branch, 'is_active') && $user->branch->is_active;
    }

    /**
     * معالجة الفرع غير النشط
     */
    private function handleInactiveBranch(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'الفرع غير نشط'], 403);
        }

        abort(403, 'الفرع غير نشط');
    }

    /**
     * التحقق من إمكانية الوصول للفرع المطلوب
     */
    private function canAccessRequestedBranch(Request $request, int $userBranchId): bool
    {
        $requestedBranchId = $this->getRequestedBranchId($request);

        return $requestedBranchId === null || $requestedBranchId === $userBranchId;
    }

    /**
     * معالجة رفض الوصول للفرع
     */
    private function handleBranchAccessDenied(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'لا يمكنك الوصول لبيانات فرع آخر'], 403);
        }

        abort(403, 'لا يمكنك الوصول لبيانات فرع آخر');
    }

    /**
     * الحصول على معرف الفرع المطلوب من الطلب
     */
    private function getRequestedBranchId(Request $request): ?int
    {
        $branchSources = [
            'route_branch' => $request->route('branch'),
            'route_branchId' => $request->route('branchId'),
            'query_branch_id' => $request->get('branch_id'),
            'body_branch_id' => $request->input('branch_id'),
            'header_branch_id' => $request->header('X-Branch-ID'),
        ];

        foreach ($branchSources as $value) {
            if ($this->isValidBranchId($value)) {
                return (int) $value;
            }
        }

        return null;
    }

    /**
     * التحقق من صحة معرف الفرع
     */
    private function isValidBranchId($value): bool
    {
        return $value && is_numeric($value);
    }
}
