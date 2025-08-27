<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// صفحة تسجيل الدخول
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// معالجة تسجيل الدخول
Route::post('/login', [AuthController::class, 'login']);

// تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// الصفحة الرئيسية - توجيه للوحة التحكم
Route::get('/', function () {
    return redirect('/dashboard');
});

// جميع المسارات المحمية (تتطلب تسجيل دخول)
Route::middleware(['web', 'auth'])->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // مسارات العملاء
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/quick-search', [CustomerController::class, 'quickSearch'])->name('customers.quick-search');
    Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])->name('customers.ajax-search');
    Route::get('/customers/{customer}/invoices', [CustomerController::class, 'getInvoices'])->name('customers.invoices');

    // مسارات البلدان
    Route::get('/countries/search', [CountryController::class, 'search'])->name('countries.search');

    // مسارات المنتجات
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/category/{category}', [ProductController::class, 'getByCategory'])->name('products.byCategory');
    Route::delete('/products/{product}/gallery-image', [ProductController::class, 'removeGalleryImage'])->name('products.remove-gallery-image');
    Route::delete('/products/{product}/video', [ProductController::class, 'removeVideo'])->name('products.remove-video');

    // مسارات الفواتير
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // مسارات المدفوعات
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/search', [PaymentController::class, 'search'])->name('payments.search');
    Route::get('/payments/invoice/{invoiceId}/info', [PaymentController::class, 'getInvoiceInfo'])->name('payments.invoice-info');
    Route::get('/payments/stats', [PaymentController::class, 'getStats'])->name('payments.stats');

    // مسارات طرق الدفع
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::post('/payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    Route::get('/payment-methods/{paymentMethod}/account', [PaymentMethodController::class, 'showAccount'])->name('payment-methods.account');
    Route::post('/payment-methods/{paymentMethod}/create-account', [PaymentMethodController::class, 'createAccount'])->name('payment-methods.create-account');

    // مسارات الموردين
    Route::resource('suppliers', SupplierController::class);
    Route::post('/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');

    // مسارات الفروع
    Route::resource('branches', BranchController::class);
    Route::post('/branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])->name('branches.toggle-status');
    Route::get('/branches/{branch}/stats', [BranchController::class, 'stats'])->name('branches.stats');

    // مسارات الحسابات المالية
    Route::resource('accounts', AccountController::class);
    Route::post('/accounts/{account}/toggle-status', [AccountController::class, 'toggleStatus'])->name('accounts.toggle-status');
    Route::post('/accounts/{account}/update-balance', [AccountController::class, 'updateBalance'])->name('accounts.update-balance');
    Route::get('/accounts/{account}/transactions/branch/{branchId}', [AccountController::class, 'showTransactionsByBranch'])->name('accounts.transactions-by-branch');
    Route::get('/accounts/{account}/transactions/all', [AccountController::class, 'showAllTransactions'])->name('accounts.all-transactions');
    Route::get('/accounts/{account}/report', [AccountController::class, 'report'])->name('accounts.report');

    // مسارات الصفحة الموحدة للحسابات وطرق الدفع
    Route::get('/financial', [App\Http\Controllers\FinancialController::class, 'index'])->name('financial.index');
    Route::post('/financial/accounts', [App\Http\Controllers\FinancialController::class, 'storeAccount'])->name('financial.store-account');
    Route::post('/financial/payment-methods', [App\Http\Controllers\FinancialController::class, 'storePaymentMethod'])->name('financial.store-payment-method');
    Route::post('/financial/accounts/{account}/toggle-status', [App\Http\Controllers\FinancialController::class, 'toggleAccountStatus'])->name('financial.toggle-account-status');
    Route::post('/financial/payment-methods/{paymentMethod}/toggle-status', [App\Http\Controllers\FinancialController::class, 'togglePaymentMethodStatus'])->name('financial.toggle-payment-method-status');
    Route::delete('/financial/accounts/{account}', [App\Http\Controllers\FinancialController::class, 'destroyAccount'])->name('financial.destroy-account');
    Route::delete('/financial/payment-methods/{paymentMethod}', [App\Http\Controllers\FinancialController::class, 'destroyPaymentMethod'])->name('financial.destroy-payment-method');
    Route::post('/financial/accounts/{account}/update-balance', [App\Http\Controllers\FinancialController::class, 'updateAccountBalance'])->name('financial.update-account-balance');
    Route::post('/financial/payment-methods/{paymentMethod}/create-account', [App\Http\Controllers\FinancialController::class, 'createLinkedAccount'])->name('financial.create-linked-account');
    Route::get('/financial/branches/{branchId}/stats', [App\Http\Controllers\FinancialController::class, 'getBranchStats'])->name('financial.branch-stats');

    // مسارات المصروفات
    Route::resource('expenses', ExpenseController::class);
    Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/unapprove', [ExpenseController::class, 'unapprove'])->name('expenses.unapprove');
    Route::get('/expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');

    // مسار حالة النظام
    Route::get('/system/status', [App\Http\Controllers\SystemController::class, 'status'])->name('system.status');
    Route::post('/system/diagnostics', [App\Http\Controllers\SystemController::class, 'diagnostics'])->name('system.diagnostics');
    Route::post('/system/maintenance', [App\Http\Controllers\SystemController::class, 'maintenance'])->name('system.maintenance');
    Route::get('/system/logs/latest', [App\Http\Controllers\SystemController::class, 'downloadLatestLog'])->name('system.logs.latest');

    // مسارات الموظفين
    Route::resource('employees', EmployeeController::class);
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::post('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::get('/employees/{employee}/report', [EmployeeController::class, 'report'])->name('employees.report');

    // مسارات الحضور والانصراف
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/report', [AttendanceController::class, 'report'])->name('report');
        Route::get('/export', [AttendanceController::class, 'export'])->name('export');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::post('/start-break', [AttendanceController::class, 'startBreak'])->name('start-break');
        Route::post('/end-break', [AttendanceController::class, 'endBreak'])->name('end-break');
        Route::get('/kiosk', [AttendanceController::class, 'kiosk'])->name('kiosk');
    });

    // مسارات إدارة الأدوار والصلاحيات
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // مسارات إدارة الشِفتات
    Route::resource('shifts', ShiftController::class);
    Route::get('/shifts/{shift}/assign-employees', [ShiftController::class, 'assignEmployees'])->name('shifts.assign-employees');
    Route::post('/shifts/{shift}/assign-employees', [ShiftController::class, 'updateEmployeeAssignment'])->name('shifts.update-employee-assignment');

    // مسارات الرواتب
    Route::prefix('salary')->name('salary.')->group(function () {
        Route::get('/', [SalaryController::class, 'index'])->name('index');
        Route::get('/create', [SalaryController::class, 'create'])->name('create');
        Route::post('/', [SalaryController::class, 'store'])->name('store');
        Route::get('/{salary}', [SalaryController::class, 'show'])->name('show');
        Route::get('/{salary}/edit', [SalaryController::class, 'edit'])->name('edit');
        Route::put('/{salary}', [SalaryController::class, 'update'])->name('update');
        Route::delete('/{salary}', [SalaryController::class, 'destroy'])->name('destroy');
        Route::get('/report', [SalaryController::class, 'report'])->name('report');
        Route::get('/export', [SalaryController::class, 'export'])->name('export');
        Route::post('/generate', [SalaryController::class, 'generate'])->name('generate');
        Route::post('/{salary}/review', [SalaryController::class, 'review'])->name('review');
        Route::post('/{salary}/approve', [SalaryController::class, 'approve'])->name('approve');
        Route::post('/{salary}/pay', [SalaryController::class, 'pay'])->name('pay');
        Route::post('/export-to-bank', [SalaryController::class, 'exportToBank'])->name('export-to-bank');
    });

    // الفئات
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // إعدادات النظام (تشمل إعدادات الأعمال)
    Route::prefix('system-settings')->name('system-settings.')->group(function () {
        Route::get('/', [App\Http\Controllers\SystemSettingsController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\SystemSettingsController::class, 'update'])->name('update');
        Route::post('/update-business', [App\Http\Controllers\SystemSettingsController::class, 'updateBusiness'])->name('update-business');
        Route::get('/remove-logo', [App\Http\Controllers\SystemSettingsController::class, 'removeLogo'])->name('remove-logo');
        Route::post('/reset', [App\Http\Controllers\SystemSettingsController::class, 'reset'])->name('reset');
        Route::get('/export', [App\Http\Controllers\SystemSettingsController::class, 'export'])->name('export');
        Route::post('/import', [App\Http\Controllers\SystemSettingsController::class, 'import'])->name('import');
        Route::get('/advanced', [App\Http\Controllers\SystemSettingsController::class, 'advanced'])->name('advanced');
        Route::post('/clear-cache', [App\Http\Controllers\SystemSettingsController::class, 'clearCache'])->name('clear-cache');
        Route::get('/category/{category}', [App\Http\Controllers\SystemSettingsController::class, 'getCategory'])->name('category');
        Route::post('/update-single', [App\Http\Controllers\SystemSettingsController::class, 'updateSingle'])->name('update-single');
        Route::get('/search', [App\Http\Controllers\SystemSettingsController::class, 'search'])->name('search');

        // المسارات المتقدمة
        Route::post('/restart-queue', [App\Http\Controllers\SystemSettingsController::class, 'restartQueue'])->name('restart-queue');
        Route::post('/restart-cache', [App\Http\Controllers\SystemSettingsController::class, 'restartCache'])->name('restart-cache');
        Route::post('/restart-config', [App\Http\Controllers\SystemSettingsController::class, 'restartConfig'])->name('restart-config');
        Route::post('/toggle-maintenance', [App\Http\Controllers\SystemSettingsController::class, 'toggleMaintenance'])->name('toggle-maintenance');
        Route::post('/optimize-database', [App\Http\Controllers\SystemSettingsController::class, 'optimizeDatabase'])->name('optimize-database');
        Route::post('/clear-logs', [App\Http\Controllers\SystemSettingsController::class, 'clearLogs'])->name('clear-logs');
        Route::get('/metrics', [App\Http\Controllers\SystemSettingsController::class, 'getMetrics'])->name('metrics');
        Route::get('/activity-logs', [App\Http\Controllers\SystemSettingsController::class, 'getActivityLogs'])->name('activity-logs');
        Route::get('/export-logs', [App\Http\Controllers\SystemSettingsController::class, 'exportLogs'])->name('export-logs');
    });

    // Log Viewer - محمي بالصلاحيات
    Route::get('/logs', function () {
        return app(\Opcodes\LogViewer\Http\Controllers\IndexController::class)();
    })->name('logs.index')->middleware('permission:system.logs');

    // Test route for error tracking - محمي بالصلاحيات
    Route::get('/oops', function () {
        throw new RuntimeException('This is a test error for Sentry, Slack logging, and error tracking systems.');
    })->name('oops')->middleware('permission:system.logs');
});

// Media Routes - نظام الوسائط المتقدم
Route::prefix('media')->name('media.')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::get('/gallery', [MediaController::class, 'gallery'])->name('gallery');
    Route::post('/upload', [MediaController::class, 'store'])->name('store');
    Route::post('/bulk-upload', [MediaController::class, 'bulkUpload'])->name('bulk-upload');
    Route::get('/{media}', [MediaController::class, 'show'])->name('show');
    Route::put('/{media}', [MediaController::class, 'update'])->name('update');
    Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [MediaController::class, 'reorder'])->name('reorder');
    Route::post('/optimize-images', [MediaController::class, 'optimizeImages'])->name('optimize-images');
    Route::post('/download-from-url', [MediaController::class, 'downloadFromUrl'])->name('download-from-url');
});

// Media API Routes
Route::prefix('api/media')->name('api.media.')->group(function () {
    Route::get('/', [MediaController::class, 'list'])->name('list');
    Route::post('/upload', [MediaController::class, 'store'])->name('store');
    Route::post('/bulk-upload', [MediaController::class, 'bulkUpload'])->name('bulk-upload');
    Route::get('/{media}', [MediaController::class, 'show'])->name('show');
    Route::put('/{media}', [MediaController::class, 'update'])->name('update');
    Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [MediaController::class, 'reorder'])->name('reorder');
    Route::post('/optimize-images', [MediaController::class, 'optimizeImages'])->name('optimize-images');
    Route::post('/download-from-url', [MediaController::class, 'downloadFromUrl'])->name('download-from-url');
});

// توجيه جميع الطلبات الأخرى لصفحة تسجيل الدخول
Route::fallback(function () {
    return redirect('/login');
});
