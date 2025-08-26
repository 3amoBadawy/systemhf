<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class SystemHealthService
{
    /**
     * Check if all controllers exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkControllers(): array
    {
        return CodeStructureHealthService::checkControllers();
    }

    /**
     * Check if all models exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkModels(): array
    {
        return CodeStructureHealthService::checkModels();
    }

    /**
     * Check if all expected routes exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkRoutes(): array
    {
        $expectedRoutes = [
            'dashboard' => 'GET',
            'customers.index' => 'GET',
            'customers.create' => 'GET',
            'customers.store' => 'POST',
            'invoices.index' => 'GET',
            'invoices.create' => 'GET',
            'invoices.store' => 'POST',
            'payments.index' => 'GET',
            'payments.create' => 'GET',
            'payments.store' => 'POST',
            'products.index' => 'GET',
            'categories.index' => 'GET',
            'branches.index' => 'GET',
            'payment-methods.index' => 'GET',
            'accounts.index' => 'GET',
            'expenses.index' => 'GET',
            'employees.index' => 'GET',
            'system.status' => 'GET',
        ];

        $results = [];
        foreach ($expectedRoutes as $route => $method) {
            try {
                $routeExists = Route::has($route);
                if ($routeExists) {
                    $results[$route] = [
                        'status' => 'working',
                        'message' => "المسار يعمل ({$method})",
                    ];

                    continue;
                }

                $results[$route] = [
                    'status' => 'error',
                    'message' => 'المسار مفقود',
                ];
            } catch (\Exception $e) {
                $results[$route] = [
                    'status' => 'error',
                    'message' => 'خطأ في المسار: '.$e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Check if all expected views exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkViews(): array
    {
        $expectedViews = [
            'dashboard.index' => 'resources/views/dashboard/index.blade.php',
            'customers.index' => 'resources/views/customers/index.blade.php',
            'customers.create' => 'resources/views/customers/create.blade.php',
            'invoices.index' => 'resources/views/invoices/index.blade.php',
            'invoices.create' => 'resources/views/invoices/create.blade.php',
            'payments.index' => 'resources/views/payments/index.blade.php',
            'payments.create' => 'resources/views/payments/create.blade.php',
            'products.index' => 'resources/views/products/index.blade.php',
            'categories.index' => 'resources/views/categories/index.blade.php',
            'branches.index' => 'resources/views/branches/index.blade.php',
            'payment-methods.index' => 'resources/views/payment-methods/index.blade.php',
            'accounts.index' => 'resources/views/accounts/index.blade.php',
            'expenses.index' => 'resources/views/expenses/index.blade.php',
            'employees.index' => 'resources/views/employees/index.blade.php',
            'system.status' => 'resources/views/system/status.blade.php',
            'layouts.app' => 'resources/views/layouts/app.blade.php',
        ];

        $results = [];
        foreach ($expectedViews as $view => $path) {
            $results[$view] = self::checkViewStatus($path);
        }

        return $results;
    }

    /**
     * Check view status
     */
    private static function checkViewStatus(string $path): array
    {
        $fileExists = File::exists(base_path($path));

        if ($fileExists) {
            return [
                'status' => 'working',
                'message' => 'الصفحة موجودة',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'الصفحة مفقودة',
        ];
    }

    /**
     * Check database connection and tables
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            $expectedTables = [
                'users',
                'customers',
                'invoices',
                'payments',
                'products',
                'categories',
                'branches',
                'payment_methods',
                'accounts',
                'expenses',
                'employees',
                'roles',
                'permissions',
                'role_permission',
                'attendance',
                'commissions',
                'salaries',
            ];

            $results = [];
            foreach ($expectedTables as $table) {
                $results[$table] = self::checkTableStatus($table);
            }

            return $results;
        } catch (\Exception $e) {
            return [
                'connection' => [
                    'status' => 'error',
                    'message' => 'فشل الاتصال بقاعدة البيانات: '.$e->getMessage(),
                ],
            ];
        }
    }

    /**
     * Check table status
     */
    private static function checkTableStatus(string $table): array
    {
        if (Schema::hasTable($table)) {
            return [
                'status' => 'working',
                'message' => 'الجدول موجود',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'الجدول غير موجود',
        ];
    }

    /**
     * Check file system permissions
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkFiles(): array
    {
        return FileSystemHealthService::checkFiles();
    }

    /**
     * Check PHP extensions
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkPhpExtensions(): array
    {
        $requiredExtensions = self::getRequiredExtensions();
        $results = [];

        foreach ($requiredExtensions as $ext) {
            $results[$ext] = self::checkExtensionStatus($ext);
        }

        return $results;
    }

    /**
     * Get required extensions list
     */
    private static function getRequiredExtensions(): array
    {
        return [
            'json', 'mbstring', 'openssl', 'pdo', 'pdo_mysql', 'curl', 'fileinfo', 'gd', 'exif',
        ];
    }

    /**
     * Check extension status
     */
    private static function checkExtensionStatus(string $ext): array
    {
        if (extension_loaded($ext)) {
            return [
                'status' => 'working',
                'message' => 'امتداد محمّل',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'امتداد غير مثبت/غير مفعّل',
        ];
    }

    /**
     * Check environment variables
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkEnv(): array
    {
        $requiredKeys = self::getRequiredEnvKeys();
        $results = [];

        foreach ($requiredKeys as $key) {
            $results[$key] = self::checkEnvKeyStatus($key);
        }

        return $results;
    }

    /**
     * Get required environment keys
     */
    private static function getRequiredEnvKeys(): array
    {
        return [
            'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_URL',
            'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME',
            'CACHE_DRIVER', 'SESSION_DRIVER', 'QUEUE_CONNECTION',
        ];
    }

    /**
     * Check environment key status
     */
    private static function checkEnvKeyStatus(string $key): array
    {
        $value = env($key);

        if ($value) {
            return [
                'status' => 'working',
                'message' => 'متغير موجود',
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'متغير مفقود',
        ];
    }

    /**
     * Check if all expected services exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkServices(): array
    {
        $expectedServices = [
            'ConfigurationService' => 'app/Services/ConfigurationService.php',
            'BusinessLogicService' => 'app/Services/BusinessLogicService.php',
            'PermissionHelper' => 'app/Helpers/PermissionHelper.php',
            'ValidationHelper' => 'app/Helpers/ValidationHelper.php',
        ];

        $results = [];
        foreach ($expectedServices as $name => $path) {
            $results[$name] = self::checkServiceStatus($name, $path);
        }

        return $results;
    }

    /**
     * Check service status
     */
    private static function checkServiceStatus(string $name, string $path): array
    {
        $fileExists = File::exists(base_path($path));
        $classExists = class_exists("App\\Services\\{$name}") || class_exists("App\\Helpers\\{$name}");

        if ($fileExists && $classExists) {
            return [
                'status' => 'working',
                'message' => 'يعمل بشكل صحيح',
            ];
        }

        if ($fileExists && ! $classExists) {
            return [
                'status' => 'warning',
                'message' => 'الملف موجود لكن الكلاس غير موجود',
            ];
        }

        return [
            'status' => 'error',
            'message' => 'الملف مفقود',
        ];
    }

    /**
     * Check if all expected migrations exist
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkMigrations(): array
    {
        return FileSystemHealthService::checkMigrations();
    }

    /**
     * Check if all required directories have proper permissions
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkPermissions(): array
    {
        return FileSystemHealthService::checkPermissions();
    }
}
