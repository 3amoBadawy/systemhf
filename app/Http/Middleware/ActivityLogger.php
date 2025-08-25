<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // تسجيل النشاط فقط للطلبات المُرسلة للـ Controllers
        if ($this->shouldLog($request, $response)) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    /**
     * تحديد ما إذا كان يجب تسجيل النشاط
     */
    protected function shouldLog(Request $request, Response $response): bool
    {
        // لا تسجل طلبات GET للموارد الثابتة
        if ($request->isMethod('GET') && $this->isStaticResource($request)) {
            return false;
        }

        // لا تسجل طلبات AJAX للبحث أو التحديث المباشر
        if ($request->ajax() && $request->isMethod('GET')) {
            return false;
        }

        // لا تسجل الأخطاء 404 و 500
        if ($response->getStatusCode() >= 400) {
            return false;
        }

        return auth()->check();
    }

    /**
     * التحقق من أن الطلب لمورد ثابت
     */
    protected function isStaticResource(Request $request): bool
    {
        $staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        $path = $request->path();

        foreach ($staticExtensions as $extension) {
            if (str_ends_with($path, '.'.$extension)) {
                return true;
            }
        }

        return false;
    }

    /**
     * تسجيل النشاط
     */
    protected function logActivity(Request $request, Response $response): void
    {
        try {
            $action = $this->getActionFromRequest($request);
            $description = $this->getDescriptionFromRequest($request);

            ActivityLog::log($action, null, $description);

        } catch (\Exception $e) {
            // تجاهل أخطاء تسجيل النشاط لتجنب تعطيل التطبيق
            \Log::error('Failed to log activity: '.$e->getMessage());
        }
    }

    /**
     * الحصول على نوع الإجراء من الطلب
     */
    protected function getActionFromRequest(Request $request): string
    {
        $method = $request->method();
        $route = $request->route();

        if (! $route) {
            return strtolower($method);
        }

        $routeName = $route->getName();
        $action = $route->getActionMethod();

        // تحديد الإجراء بناءً على اسم الـ Route
        if ($routeName) {
            if (str_contains($routeName, '.create') || str_contains($routeName, '.store')) {
                return 'created';
            }
            if (str_contains($routeName, '.edit') || str_contains($routeName, '.update')) {
                return 'updated';
            }
            if (str_contains($routeName, '.destroy') || str_contains($routeName, '.delete')) {
                return 'deleted';
            }
            if (str_contains($routeName, '.show') || str_contains($routeName, '.index')) {
                return 'viewed';
            }
        }

        // تحديد الإجراء بناءً على method الـ Controller
        switch ($action) {
            case 'store':
                return 'created';
            case 'update':
                return 'updated';
            case 'destroy':
                return 'deleted';
            case 'show':
            case 'index':
                return 'viewed';
            default:
                return strtolower($method);
        }
    }

    /**
     * الحصول على وصف الإجراء
     */
    protected function getDescriptionFromRequest(Request $request): string
    {
        $route = $request->route();

        if (! $route) {
            return 'طلب '.$request->method().' إلى '.$request->path();
        }

        $routeName = $route->getName();
        $action = $this->getActionFromRequest($request);

        // أسماء النماذج بالعربية
        $modelNames = [
            'customers' => 'العملاء',
            'products' => 'المنتجات',
            'invoices' => 'الفواتير',
            'payments' => 'المدفوعات',
            'employees' => 'الموظفين',
            'suppliers' => 'الموردين',
            'categories' => 'الفئات',
            'branches' => 'الفروع',
            'roles' => 'الأدوار',
            'permissions' => 'الصلاحيات',
            'expenses' => 'المصروفات',
            'accounts' => 'الحسابات',
            'shifts' => 'الشِفتات',
            'attendance' => 'الحضور والانصراف',
            'salaries' => 'الرواتب',
            'media' => 'الوسائط',
        ];

        // أسماء الإجراءات بالعربية
        $actionNames = [
            'created' => 'إنشاء',
            'updated' => 'تحديث',
            'deleted' => 'حذف',
            'viewed' => 'عرض',
        ];

        // استخراج اسم النموذج من الـ Route
        $modelName = '';
        foreach ($modelNames as $key => $arabicName) {
            if (str_contains($request->path(), $key) || ($routeName && str_contains($routeName, $key))) {
                $modelName = $arabicName;
                break;
            }
        }

        $actionName = $actionNames[$action] ?? $action;

        if ($modelName) {
            return $actionName.' '.$modelName;
        }

        return $actionName.' في النظام';
    }
}
