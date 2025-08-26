<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class CodeStructureHealthService
{
    /**
     * Check if all controllers exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkControllers(): array
    {
        $controllers = self::getControllerList();
        $results = [];

        foreach ($controllers as $name => $path) {
            $results[$name] = self::checkControllerStatus($name, $path);
        }

        return $results;
    }

    /**
     * Get controller list
     */
    private static function getControllerList(): array
    {
        return [
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
    }

    /**
     * Check controller status
     */
    private static function checkControllerStatus(string $name, string $path): array
    {
        $fileExists = File::exists(base_path($path));
        $classExists = class_exists("App\\Http\\Controllers\\{$name}");

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
     * Check if all models exist and are working
     *
     * @return array<string, array{status: string, message: string}>
     */
    public static function checkModels(): array
    {
        $models = self::getModelList();
        $results = [];

        foreach ($models as $name => $path) {
            $results[$name] = self::checkModelStatus($name, $path);
        }

        return $results;
    }

    /**
     * Get model list
     */
    private static function getModelList(): array
    {
        return [
            'User' => 'app/Models/User.php',
            'Customer' => 'app/Models/Customer.php',
            'Product' => 'app/Models/Product.php',
            'Invoice' => 'app/Models/Invoice.php',
            'Payment' => 'app/Models/Payment.php',
            'Category' => 'app/Models/Category.php',
            'Branch' => 'app/Models/Branch.php',
            'Employee' => 'app/Models/Employee.php',
            'Account' => 'app/Models/Account.php',
            'Expense' => 'app/Models/Expense.php',
        ];
    }

    /**
     * Check model status
     */
    private static function checkModelStatus(string $name, string $path): array
    {
        $fileExists = File::exists(base_path($path));
        $classExists = class_exists("App\\Models\\{$name}");

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
}
