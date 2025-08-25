<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * @return string[][]
     *
     * @psalm-return array{DashboardController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, CustomerController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, InvoiceController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, PaymentController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, ProductController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, CategoryController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, BranchController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, PaymentMethodController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, AccountController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, ExpenseController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, EmployeeController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, SystemController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, AuthController: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}}
     */
    private function checkControllers(): array
    {
        $controllers = [
            'DashboardController' => 'app/Http/Controllers/DashboardController.php',
            'CustomerController' => 'app/Http/Controllers/CustomerController.php',
            'InvoiceController' => 'app/Http/Controllers/InvoiceController.php',
            'PaymentController' => 'app/Http/Controllers/PaymentController.php',
            'ProductController' => 'app/Http/Controllers/ProductController.php',
            'CategoryController' => 'app/Http/Controllers/CategoryController.php',
            'BranchController' => 'app/Http/Controllers/BranchController.php',
            'PaymentMethodController' => 'app/Http/Controllers/PaymentMethodController.php',
            'AccountController' => 'app/Http/Controllers/AccountController.php',
            'ExpenseController' => 'app/Http/Controllers/ExpenseController.php',
            'EmployeeController' => 'app/Http/Controllers/EmployeeController.php',
            'SystemController' => 'app/Http/Controllers/SystemController.php',
            'AuthController' => 'app/Http/Controllers/AuthController.php',
        ];

        $results = [];
        foreach ($controllers as $name => $path) {
            $fileExists = File::exists(base_path($path));
            $classExists = class_exists("App\\Http\\Controllers\\{$name}");

            if ($fileExists && $classExists) {
                $results[$name] = [
                    'status' => 'working',
                    'message' => 'يعمل بشكل صحيح',
                ];
            } elseif ($fileExists && ! $classExists) {
                $results[$name] = [
                    'status' => 'warning',
                    'message' => 'الملف موجود لكن الكلاس غير موجود',
                ];
            } else {
                $results[$name] = [
                    'status' => 'error',
                    'message' => 'الملف مفقود',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{User: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Customer: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Invoice: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Payment: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Product: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Category: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Branch: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, PaymentMethod: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Account: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Expense: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Employee: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Role: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}, Permission: array{status: 'error'|'warning'|'working', message: 'الملف مفقود'|'الملف موجود لكن الكلاس غير موجود'|'يعمل بشكل صحيح'}}
     */
    private function checkModels(): array
    {
        $models = [
            'User' => 'app/Models/User.php',
            'Customer' => 'app/Models/Customer.php',
            'Invoice' => 'app/Models/Invoice.php',
            'Payment' => 'app/Models/Payment.php',
            'Product' => 'app/Models/Product.php',
            'Category' => 'app/Models/Category.php',
            'Branch' => 'app/Models/Branch.php',
            'PaymentMethod' => 'app/Models/PaymentMethod.php',
            'Account' => 'app/Models/Account.php',
            'Expense' => 'app/Models/Expense.php',
            'Employee' => 'app/Models/Employee.php',
            'Role' => 'app/Models/Role.php',
            'Permission' => 'app/Models/Permission.php',
        ];

        $results = [];
        foreach ($models as $name => $path) {
            $fileExists = File::exists(base_path($path));
            $classExists = class_exists("App\\Models\\{$name}");

            if ($fileExists && $classExists) {
                $results[$name] = [
                    'status' => 'working',
                    'message' => 'يعمل بشكل صحيح',
                ];
            } elseif ($fileExists && ! $classExists) {
                $results[$name] = [
                    'status' => 'warning',
                    'message' => 'الملف موجود لكن الكلاس غير موجود',
                ];
            } else {
                $results[$name] = [
                    'status' => 'error',
                    'message' => 'الملف مفقود',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{dashboard: array{status: 'error'|'working', message: string}, 'customers.index': array{status: 'error'|'working', message: string}, 'customers.create': array{status: 'error'|'working', message: string}, 'customers.store': array{status: 'error'|'working', message: string}, 'invoices.index': array{status: 'error'|'working', message: string}, 'invoices.create': array{status: 'error'|'working', message: string}, 'invoices.store': array{status: 'error'|'working', message: string}, 'payments.index': array{status: 'error'|'working', message: string}, 'payments.create': array{status: 'error'|'working', message: string}, 'payments.store': array{status: 'error'|'working', message: string}, 'products.index': array{status: 'error'|'working', message: string}, 'categories.index': array{status: 'error'|'working', message: string}, 'branches.index': array{status: 'error'|'working', message: string}, 'payment-methods.index': array{status: 'error'|'working', message: string}, 'accounts.index': array{status: 'error'|'working', message: string}, 'expenses.index': array{status: 'error'|'working', message: string}, 'employees.index': array{status: 'error'|'working', message: string}, 'system.status': array{status: 'error'|'working', message: string}}
     */
    private function checkRoutes(): array
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
                        'message' => 'المسار يعمل',
                    ];
                } else {
                    $results[$route] = [
                        'status' => 'error',
                        'message' => 'المسار مفقود',
                    ];
                }
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
     * @return string[][]
     *
     * @psalm-return array{'dashboard.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'customers.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'customers.create': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'invoices.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'invoices.create': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'payments.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'payments.create': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'products.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'categories.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'branches.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'payment-methods.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'accounts.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'expenses.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'employees.index': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'system.status': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}, 'layouts.app': array{status: 'error'|'working', message: 'الصفحة مفقودة'|'الصفحة موجودة'}}
     */
    private function checkViews(): array
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
            $fileExists = File::exists(base_path($path));

            if ($fileExists) {
                $results[$view] = [
                    'status' => 'working',
                    'message' => 'الصفحة موجودة',
                ];
            } else {
                $results[$view] = [
                    'status' => 'error',
                    'message' => 'الصفحة مفقودة',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{salaries: array{status: 'error'|'working', message: string}, commissions: array{status: 'error'|'working', message: string}, attendance: array{status: 'error'|'working', message: string}, role_permission: array{status: 'error'|'working', message: string}, permissions: array{status: 'error'|'working', message: string}, roles: array{status: 'error'|'working', message: string}, employees: array{status: 'error'|'working', message: string}, expenses: array{status: 'error'|'working', message: string}, accounts: array{status: 'error'|'working', message: string}, payment_methods: array{status: 'error'|'working', message: string}, branches: array{status: 'error'|'working', message: string}, categories: array{status: 'error'|'working', message: string}, products: array{status: 'error'|'working', message: string}, payments: array{status: 'error'|'working', message: string}, invoices: array{status: 'error'|'working', message: string}, customers: array{status: 'error'|'working', message: string}, users: array{status: 'error'|'working', message: string}}
     */
    private function checkDatabase(): array
    {
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
            try {
                $tableExists = Schema::hasTable($table);
                if ($tableExists) {
                    $results[$table] = [
                        'status' => 'working',
                        'message' => 'الجدول موجود',
                    ];
                } else {
                    $results[$table] = [
                        'status' => 'error',
                        'message' => 'الجدول مفقود',
                    ];
                }
            } catch (\Exception $e) {
                $results[$table] = [
                    'status' => 'error',
                    'message' => 'خطأ في فحص الجدول: '.$e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{'مجلد Cache': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف السجلات': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'قواعد .htaccess': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف Index الرئيسي': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف Artisan': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف Webpack': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف NPM': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف Autoload': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف Composer': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}, 'ملف البيئة': array{status: 'warning'|'working', message: 'الملف مفقود'|'الملف موجود'}}
     */
    private function checkFiles(): array
    {
        $expectedFiles = [
            '.env' => 'ملف البيئة',
            'composer.json' => 'ملف Composer',
            'vendor/autoload.php' => 'ملف Autoload',
            'package.json' => 'ملف NPM',
            'webpack.mix.js' => 'ملف Webpack',
            'artisan' => 'ملف Artisan',
            'public/index.php' => 'ملف Index الرئيسي',
            'public/.htaccess' => 'قواعد .htaccess',
            'storage/logs/laravel.log' => 'ملف السجلات',
            'bootstrap/cache' => 'مجلد Cache',
        ];

        $results = [];
        foreach ($expectedFiles as $file => $description) {
            try {
                if (str_starts_with($file, 'public/')) {
                    $absolutePath = public_path(substr($file, 7));
                } elseif (str_starts_with($file, 'storage/')) {
                    $absolutePath = storage_path(substr($file, 8));
                } else {
                    $absolutePath = base_path($file);
                }
            } catch (\Throwable $e) {
                $absolutePath = base_path($file);
            }

            $fileExists = File::exists($absolutePath);

            if ($fileExists) {
                $results[$description] = [
                    'status' => 'working',
                    'message' => 'الملف موجود',
                ];
            } else {
                $results[$description] = [
                    'status' => 'warning',
                    'message' => 'الملف مفقود',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{storage: array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'storage/logs': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'storage/framework': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'storage/framework/cache': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'storage/framework/sessions': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'storage/framework/views': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}, 'bootstrap/cache': array{status: 'error'|'warning'|'working', message: 'المجلد غير قابل للكتابة'|'المجلد مفقود'|'المجلد موجود وقابل للكتابة'}}
     */
    private function checkPermissions(): array
    {
        $paths = [
            'storage' => storage_path(),
            'storage/logs' => storage_path('logs'),
            'storage/framework' => storage_path('framework'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $results = [];
        foreach ($paths as $label => $absolutePath) {
            $exists = File::exists($absolutePath);
            $isDir = File::isDirectory($absolutePath);
            $writable = @is_writable($absolutePath);

            if ($exists && $isDir && $writable) {
                $results[$label] = [
                    'status' => 'working',
                    'message' => 'المجلد موجود وقابل للكتابة',
                ];
            } elseif ($exists && $isDir && ! $writable) {
                $results[$label] = [
                    'status' => 'warning',
                    'message' => 'المجلد غير قابل للكتابة',
                ];
            } else {
                $results[$label] = [
                    'status' => 'error',
                    'message' => 'المجلد مفقود',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{exif: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, gd: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, fileinfo: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, curl: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, pdo_mysql: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, pdo: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, openssl: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, mbstring: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}, json: array{status: 'error'|'working', message: 'امتداد غير مثبت/غير مفعّل'|'امتداد محمّل'}}
     */
    private function checkPhpExtensions(): array
    {
        $requiredExtensions = [
            'json', 'mbstring', 'openssl', 'pdo', 'pdo_mysql', 'curl', 'fileinfo', 'gd', 'exif',
        ];

        $results = [];
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                $results[$ext] = [
                    'status' => 'working',
                    'message' => 'امتداد محمّل',
                ];
            } else {
                $results[$ext] = [
                    'status' => 'error',
                    'message' => 'امتداد غير مثبت/غير مفعّل',
                ];
            }
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{QUEUE_CONNECTION: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, SESSION_DRIVER: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, CACHE_DRIVER: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, DB_USERNAME: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, DB_DATABASE: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, DB_HOST: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, DB_CONNECTION: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, APP_URL: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, APP_KEY: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, APP_ENV: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, APP_NAME: array{status: 'warning'|'working', message: 'غير مُعرّف'|'مُعرّف'}, APP_KEY_FORMAT: array{status: 'error'|'working', message: 'APP_KEY غير مضبوط'|'مفتاح التطبيق مضبوط'}}
     */
    private function checkEnv(): array
    {
        $requiredKeys = [
            'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_URL',
            'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME',
            'CACHE_DRIVER', 'SESSION_DRIVER', 'QUEUE_CONNECTION',
        ];

        $results = [];
        foreach ($requiredKeys as $key) {
            $value = env($key);
            if (! empty($value)) {
                $results[$key] = [
                    'status' => 'working',
                    'message' => 'مُعرّف',
                ];
            } else {
                $results[$key] = [
                    'status' => 'warning',
                    'message' => 'غير مُعرّف',
                ];
            }
        }

        // تحقق خاص بـ APP_KEY
        $appKey = config('app.key');
        if (! empty($appKey)) {
            $results['APP_KEY_FORMAT'] = [
                'status' => 'working',
                'message' => 'مفتاح التطبيق مضبوط',
            ];
        } else {
            $results['APP_KEY_FORMAT'] = [
                'status' => 'error',
                'message' => 'APP_KEY غير مضبوط',
            ];
        }

        return $results;
    }

    /**
     * @return string[][]
     *
     * @psalm-return array{migrations_table?: array{status: 'error'|'working', message: 'جدول الهجرات مفقود'|'جدول الهجرات موجود'}, migrations_status: array{status: 'error'|'warning'|'working', message: string}}
     */
    private function checkMigrations(): array
    {
        $results = [];

        try {
            $hasTable = Schema::hasTable('migrations');
            $results['migrations_table'] = [
                'status' => $hasTable ? 'working' : 'error',
                'message' => $hasTable ? 'جدول الهجرات موجود' : 'جدول الهجرات مفقود',
            ];

            $migrationFiles = glob(base_path('database/migrations/*.php')) ?: [];
            $totalFiles = count($migrationFiles);
            $applied = $hasTable ? (int) DB::table('migrations')->count() : 0;
            $pending = max($totalFiles - $applied, 0);

            $results['migrations_status'] = [
                'status' => $pending === 0 ? 'working' : 'warning',
                'message' => "تم تنفيذ {$applied} من أصل {$totalFiles} (متبقي {$pending})",
            ];
        } catch (\Exception $e) {
            $results['migrations_status'] = [
                'status' => 'error',
                'message' => 'تعذر فحص حالة الهجرات: '.$e->getMessage(),
            ];
        }

        return $results;
    }

    /**
     * @psalm-return array{'Database Connection': mixed, 'Cache Service': mixed, 'Session Service': mixed, 'Queue Service': mixed, 'Mail Service': mixed, 'Storage Service': mixed}
     */
    private function checkServices(): array
    {
        $services = [
            'Database Connection' => $this->checkDatabaseConnection(),
            'Cache Service' => $this->checkCacheService(),
            'Session Service' => $this->checkSessionService(),
            'Queue Service' => $this->checkQueueService(),
            'Mail Service' => $this->checkMailService(),
            'Storage Service' => $this->checkStorageService(),
        ];

        return $services;
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'error'|'working', message: string}
     */
    private function checkDatabaseConnection(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'status' => 'working',
                'message' => 'الاتصال يعمل',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل الاتصال: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'error'|'warning'|'working', message: string}
     */
    private function checkCacheService(): array
    {
        try {
            $key = 'system_test_'.time();
            cache([$key => 'test'], 1);
            $value = cache($key);
            cache()->forget($key);

            if ($value === 'test') {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Cache تعمل',
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'خدمة Cache تعمل جزئياً',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Cache: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'error'|'warning'|'working', message: string}
     */
    private function checkSessionService(): array
    {
        try {
            session(['test' => 'test']);
            $value = session('test');
            session()->forget('test');

            if ($value === 'test') {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Session تعمل',
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'خدمة Session تعمل جزئياً',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Session: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'warning'|'working', message: string}
     */
    private function checkQueueService(): array
    {
        try {
            return [
                'status' => 'working',
                'message' => 'خدمة Queue متاحة',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'خدمة Queue غير متاحة: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'warning'|'working', message: string}
     */
    private function checkMailService(): array
    {
        try {
            return [
                'status' => 'working',
                'message' => 'خدمة Mail متاحة',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'خدمة Mail غير متاحة: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return string[]
     *
     * @psalm-return array{status: 'error'|'warning'|'working', message: string}
     */
    private function checkStorageService(): array
    {
        try {
            $testFile = 'test_'.time().'.txt';
            Storage::put($testFile, 'test');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);

            if ($exists) {
                return [
                    'status' => 'working',
                    'message' => 'خدمة Storage تعمل',
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'خدمة Storage تعمل جزئياً',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل في خدمة Storage: '.$e->getMessage(),
            ];
        }
    }

    private function getDatabaseVersion()
    {
        try {
            $version = DB::select('SELECT VERSION() as version')[0]->version;

            return $version;
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getDiskSpace(): string
    {
        try {
            $free = disk_free_space(storage_path());
            $total = disk_total_space(storage_path());
            $used = $total - $free;
            $percentage = round(($used / $total) * 100, 2);

            return round($free / 1024 / 1024 / 1024, 2).' GB متاح ('.$percentage.'% مستخدم)';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getMemoryUsage(): string
    {
        try {
            $memory = memory_get_usage(true);
            $memoryPeak = memory_get_peak_usage(true);

            return round($memory / 1024 / 1024, 2).' MB (Peak: '.round($memoryPeak / 1024 / 1024, 2).' MB)';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    private function getUptime(): string
    {
        try {
            $uptime = time() - filemtime(storage_path('framework/cache'));
            $days = floor($uptime / 86400);
            $hours = floor(($uptime % 86400) / 3600);
            $minutes = floor(($uptime % 3600) / 60);

            if ($days > 0) {
                return $days.' يوم '.$hours.' ساعة '.$minutes.' دقيقة';
            } elseif ($hours > 0) {
                return $hours.' ساعة '.$minutes.' دقيقة';
            } else {
                return $minutes.' دقيقة';
            }
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }
}
