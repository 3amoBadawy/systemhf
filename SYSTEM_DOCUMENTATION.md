# 📚 **وثائق النظام التقنية - SystemHF**

## 📋 **معلومات عامة**

- **اسم النظام:** نظام إدارة معرض الأثاث - SystemHF
- **الإصدار:** 2.1.0
- **تاريخ التحديث:** 24 أغسطس 2025
- **إطار العمل:** Laravel 11
- **قاعدة البيانات:** MySQL 8.0+
- **اللغة الأساسية:** العربية (مع دعم الإنجليزية)

## 🏗️ **البنية التقنية**

### **المكونات الأساسية:**
- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade Templates + Tailwind CSS + Alpine.js
- **قاعدة البيانات:** MySQL 8.0+
- **Cache:** Redis (اختياري)
- **Queue:** Database Queue
- **File Storage:** Local + Public Disk

### **المكتبات والتبعيات:**
- **Laravel Sanctum:** للمصادقة
- **Laravel Excel:** لتصدير البيانات
- **Carbon:** لإدارة التواريخ
- **Spatie Permission:** لإدارة الصلاحيات (محسن)

## 🗄️ **قاعدة البيانات**

### **الجداول الأساسية:**

#### **1. جدول المستخدمين (users):**
```sql
- id (bigint, primary key)
- name (varchar(255))
- name_ar (varchar(255))
- email (varchar(255), unique)
- email_verified_at (timestamp, nullable)
- password (varchar(255))
- role (enum: 'super_admin', 'admin', 'manager', 'employee')
- role_id (bigint, foreign key)
- permissions (json, nullable)
- phone (varchar(255), nullable)
- is_active (tinyint(1), default: 1)
- remember_token (varchar(100), nullable)
- branch_id (bigint, foreign key, nullable)
- employee_number (varchar(255), nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **2. جدول الأدوار (roles):**
```sql
- id (bigint, primary key)
- name (varchar(255), unique)
- name_ar (varchar(255))
- description (text, nullable)
- description_ar (text, nullable)
- permissions (json)
- is_active (tinyint(1), default: 1)
- is_system (tinyint(1), default: 0)
- sort_order (int, default: 0)
- branch_id (bigint, foreign key, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **3. جدول الصلاحيات (permissions):**
```sql
- id (bigint, primary key)
- name (varchar(255), unique)
- name_ar (varchar(255))
- description (text, nullable)
- description_ar (text, nullable)
- module (varchar(255))
- action (varchar(255))
- is_active (tinyint(1), default: 1)
- is_system (tinyint(1), default: 0)
- sort_order (int, default: 0)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **4. جدول إعدادات النظام (system_settings):**
```sql
- id (bigint, primary key)
- key (varchar(255), unique)
- value (text)
- type (enum: 'string', 'integer', 'decimal', 'boolean', 'json')
- name_ar (varchar(255))
- description_ar (text, nullable)
- category (varchar(255))
- default_value (text, nullable)
- validation_rules (text, nullable)
- requires_restart (tinyint(1), default: 0)
- is_editable (tinyint(1), default: 1)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **5. جدول إعدادات الأعمال (business_settings):**
```sql
- id (bigint, primary key)
- business_name (varchar(255))
- business_name_ar (varchar(255))
- description (text, nullable)
- phone (varchar(255), nullable)
- email (varchar(255), nullable)
- address (text, nullable)
- logo (varchar(255), nullable)
- currency (varchar(10), default: 'EGP')
- currency_symbol (varchar(10), default: 'ج.م')
- currency_symbol_placement (enum: 'before', 'after', default: 'after')
- default_profit_percent (decimal(5,2), default: 30.00)
- timezone (varchar(255), default: 'Africa/Cairo')
- date_format (varchar(255), default: 'Y-m-d')
- time_format (varchar(255), default: 'H:i:s')
- created_at (timestamp)
- updated_at (timestamp)
```

#### **6. جدول الفروع (branches):**
```sql
- id (bigint, primary key)
- name (varchar(255))
- name_ar (varchar(255))
- address (text, nullable)
- phone (varchar(255), nullable)
- email (varchar(255), nullable)
- manager_id (bigint, foreign key, nullable)
- is_active (tinyint(1), default: 1)
- is_main (tinyint(1), default: 0)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **7. جدول الموظفين (employees):**
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key, unique)
- employee_number (varchar(255), unique)
- first_name (varchar(255))
- last_name (varchar(255))
- first_name_ar (varchar(255))
- last_name_ar (varchar(255))
- phone (varchar(255), nullable)
- email (varchar(255), nullable)
- address (text, nullable)
- hire_date (date)
- salary (decimal(10,2))
- position (varchar(255))
- position_ar (varchar(255))
- department (varchar(255))
- department_ar (varchar(255))
- branch_id (bigint, foreign key)
- is_active (tinyint(1), default: 1)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **8. جدول العملاء (customers):**
```sql
- id (bigint, primary key)
- name (varchar(255))
- name_ar (varchar(255))
- email (varchar(255), nullable)
- phone (varchar(255), nullable)
- address (text, nullable)
- tax_number (varchar(255), nullable)
- credit_limit (decimal(10,2), default: 0.00)
- branch_id (bigint, foreign key)
- is_active (tinyint(1), default: 1)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **9. جدول المنتجات (products):**
```sql
- id (bigint, primary key)
- name (varchar(255))
- name_ar (varchar(255))
- description (text, nullable)
- description_ar (text, nullable)
- sku (varchar(255), unique)
- barcode (varchar(255), nullable)
- category_id (bigint, foreign key)
- price (decimal(10,2))
- cost_price (decimal(10,2))
- stock_quantity (int, default: 0)
- min_stock_level (int, default: 0)
- max_stock_level (int, default: 0)
- unit (varchar(255), default: 'قطعة')
- unit_ar (varchar(255), default: 'قطعة')
- weight (decimal(8,2), nullable)
- dimensions (json, nullable)
- images (json, nullable)
- video (varchar(255), nullable)
- branch_id (bigint, foreign key)
- is_active (tinyint(1), default: 1)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **10. جدول الفواتير (invoices):**
```sql
- id (bigint, primary key)
- invoice_number (varchar(255), unique)
- customer_id (bigint, foreign key)
- customer_name (varchar(255))
- customer_name_ar (varchar(255))
- invoice_date (date)
- due_date (date, nullable)
- subtotal (decimal(10,2))
- tax_amount (decimal(10,2), default: 0.00)
- discount_amount (decimal(10,2), default: 0.00)
- total_amount (decimal(10,2))
- paid_amount (decimal(10,2), default: 0.00)
- remaining_amount (decimal(10,2))
- status (enum: 'draft', 'sent', 'paid', 'overdue', 'cancelled')
- notes (text, nullable)
- branch_id (bigint, foreign key)
- created_by (bigint, foreign key)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **11. جدول الرواتب (salaries):**
```sql
- id (bigint, primary key)
- employee_id (bigint, foreign key)
- month (int)
- year (int)
- basic_salary (decimal(10,2))
- allowances (decimal(10,2), default: 0.00)
- deductions (decimal(10,2), default: 0.00)
- overtime_hours (decimal(5,2), default: 0.00)
- overtime_rate (decimal(5,2), default: 0.00)
- overtime_amount (decimal(10,2), default: 0.00)
- net_salary (decimal(10,2))
- status (enum: 'pending', 'reviewed', 'approved', 'paid')
- payment_date (date, nullable)
- notes (text, nullable)
- branch_id (bigint, foreign key)
- created_by (bigint, foreign key)
- reviewed_by (bigint, foreign key, nullable)
- approved_by (bigint, foreign key, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

#### **12. جدول سجلات النشاط (activity_logs):**
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key, nullable)
- action (varchar(255))
- model_type (varchar(255), nullable)
- model_id (bigint, nullable)
- old_values (json, nullable)
- new_values (json, nullable)
- ip_address (varchar(45), nullable)
- user_agent (text, nullable)
- branch_id (bigint, foreign key, nullable)
- created_at (timestamp)
```

#### **13. جدول إصدارات النظام (system_versions):**
```sql
- id (bigint, primary key)
- version (varchar(255))
- version_name (varchar(255))
- description (text)
- release_date (timestamp)
- type (enum: 'major', 'minor', 'patch')
- features (json, nullable)
- bug_fixes (json, nullable)
- is_current (tinyint(1), default: 0)
- is_required (tinyint(1), default: 0)
- created_at (timestamp)
- updated_at (timestamp)
```

## 🔗 **العلاقات بين الجداول**

### **العلاقات الأساسية:**

#### **1. المستخدمين والأدوار:**
```php
// User -> Role (Many to One)
public function role()
{
    return $this->belongsTo(Role::class);
}

// Role -> Users (One to Many)
public function users()
{
    return $this->hasMany(User::class);
}
```

#### **2. المستخدمين والموظفين:**
```php
// User -> Employee (One to One)
public function employee()
{
    return $this->hasOne(Employee::class);
}

// Employee -> User (One to One)
public function user()
{
    return $this->belongsTo(User::class);
}
```

#### **3. الفروع والموظفين:**
```php
// Branch -> Employees (One to Many)
public function employees()
{
    return $this->hasMany(Employee::class);
}

// Employee -> Branch (Many to One)
public function branch()
{
    return $this->belongsTo(Branch::class);
}
```

#### **4. العملاء والفواتير:**
```php
// Customer -> Invoices (One to Many)
public function invoices()
{
    return $this->hasMany(Invoice::class);
}

// Invoice -> Customer (Many to One)
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

#### **5. المنتجات والفواتير:**
```php
// Product -> InvoiceItems (One to Many)
public function invoiceItems()
{
    return $this->hasMany(InvoiceItem::class);
}

// Invoice -> Products (Many to Many through InvoiceItem)
public function products()
{
    return $this->belongsToMany(Product::class, 'invoice_items')
                ->withPivot('quantity', 'price', 'total');
}
```

## 🛠️ **الخدمات والمساعدون**

### **1. ConfigurationService:**
```php
class ConfigurationService
{
    // الحصول على إعداد
    public static function get(string $key, $default = null)
    
    // تعيين إعداد
    public static function set(string $key, $value, string $type = 'string')
    
    // التحقق من صحة الإعداد
    public static function validateSetting(string $key, $value): bool
    
    // تصدير الإعدادات
    public static function exportSettings(): array
    
    // استيراد الإعدادات
    public static function importSettings(array $settings): bool
    
    // إعادة تعيين للافتراضي
    public static function resetToDefaults(string $category = null): bool
    
    // مسح الكاش
    public static function clearCache(): void
}
```

### **2. BusinessSettingsService:**
```php
class BusinessSettingsService
{
    // الحصول على الإعدادات
    public static function getSettings()
    
    // الحصول على اسم الأعمال
    public static function getBusinessName(string $locale = 'ar'): string
    
    // تنسيق العملة
    public static function formatCurrency(float $amount): string
    
    // الحصول على رمز العملة
    public static function getCurrencySymbol(): string
    
    // تنسيق التاريخ
    public static function formatDate($date): string
    
    // تنسيق الوقت
    public static function formatTime($time): string
    
    // مسح الكاش
    public static function clearCache(): void
}
```

### **3. ValidationHelper:**
```php
class ValidationHelper
{
    // قواعد التحقق للملفات
    public static function getFileValidationRules(): array
    
    // قواعد التحقق للصور
    public static function getImageValidationRules(): array
    
    // قواعد التحقق للمنتجات
    public static function getProductValidationRules(): array
    
    // قواعد التحقق للعملاء
    public static function getCustomerValidationRules(): array
    
    // قواعد التحقق للفواتير
    public static function getInvoiceValidationRules(): array
    
    // قواعد التحقق للرواتب
    public static function getSalaryValidationRules(): array
}
```

### **4. SystemHelper:**
```php
class SystemHelper
{
    // الحصول على اسم الفئة
    public static function getCategoryName(string $category): string
    
    // الحصول على وصف الفئة
    public static function getCategoryDescription(string $category): string
    
    // الحصول على اسم المجموعة
    public static function getGroupName(string $group): string
    
    // الحصول على اسم المجموعة بالعربية
    public static function getGroupArabicName(string $group): string
    
    // تنسيق التاريخ
    public static function formatDate($date, string $format = null): string
    
    // تنسيق العملة
    public static function formatCurrency(float $amount): string
}
```

### **5. PermissionHelper:**
```php
class PermissionHelper
{
    // فحص الصلاحية
    public static function can(string $permission): bool
    
    // فحص الصلاحية للفرع
    public static function canForBranch(string $permission, int $branchId): bool
    
    // فحص إدارة المستخدمين
    public static function canManageUsers(): bool
    
    // فحص إدارة النظام
    public static function canManageSystem(): bool
    
    // فحص عرض التقارير
    public static function canViewReports(): bool
    
    // فحص إدارة المنتجات
    public static function canManageProducts(): bool
}
```

## 🔐 **نظام الصلاحيات (RBAC)**

### **الأدوار الأساسية:**

#### **1. المدير العام (super_admin):**
- **الصلاحيات:** جميع الصلاحيات (`*`)
- **الوصف:** مدير عام للنظام مع جميع الصلاحيات
- **النوع:** دور نظام (لا يمكن حذفه)

#### **2. المدير (admin):**
- **الصلاحيات:** 
  - `users.*` - إدارة المستخدمين
  - `employees.*` - إدارة الموظفين
  - `products.*` - إدارة المنتجات
  - `customers.*` - إدارة العملاء
  - `invoices.*` - إدارة الفواتير
  - `payments.*` - إدارة المدفوعات
  - `expenses.*` - إدارة المصروفات
  - `reports.*` - عرض التقارير
  - `settings.*` - إدارة الإعدادات
  - `system.maintenance` - صيانة النظام
  - `system.backup` - النسخ الاحتياطي
  - `system.logs` - عرض السجلات

#### **3. المدير (manager):**
- **الصلاحيات:** صلاحيات محدودة للإدارة اليومية
- **النوع:** دور مخصص

#### **4. الموظف (employee):**
- **الصلاحيات:** صلاحيات محدودة للعمليات اليومية
- **النوع:** دور مخصص

### **نظام الصلاحيات البرية:**
- **`module.*`:** جميع صلاحيات الوحدة
- **`module.action`:** صلاحية محددة
- **`*`:** جميع الصلاحيات

## 🌐 **المسارات (Routes)**

### **المسارات المحمية:**
```php
Route::middleware(['web', 'auth'])->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // إعدادات النظام
    Route::prefix('system-settings')->name('system-settings.')->group(function () {
        Route::get('/', [SystemSettingsController::class, 'index'])->name('index');
        Route::post('/update', [SystemSettingsController::class, 'update'])->name('update');
        Route::post('/reset', [SystemSettingsController::class, 'reset'])->name('reset');
        Route::get('/export', [SystemSettingsController::class, 'export'])->name('export');
        Route::post('/import', [SystemSettingsController::class, 'import'])->name('import');
        Route::get('/advanced', [SystemSettingsController::class, 'advanced'])->name('advanced');
        Route::post('/clear-cache', [SystemSettingsController::class, 'clearCache'])->name('clear-cache');
    });
    
    // إعدادات الأعمال
    Route::get('/business-settings', [BusinessSettingsController::class, 'index'])->name('business-settings.index');
    Route::put('/business-settings', [BusinessSettingsController::class, 'update'])->name('business-settings.update');
    
    // إدارة الأدوار والصلاحيات
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    
    // إدارة الرواتب
    Route::prefix('salary')->name('salary.')->group(function () {
        Route::get('/', [SalaryController::class, 'index'])->name('index');
        Route::get('/report', [SalaryController::class, 'report'])->name('report');
        Route::get('/export', [SalaryController::class, 'export'])->name('export');
        Route::post('/generate', [SalaryController::class, 'generate'])->name('generate');
        Route::post('/{salary}/review', [SalaryController::class, 'review'])->name('review');
        Route::post('/{salary}/approve', [SalaryController::class, 'approve'])->name('approve');
        Route::post('/{salary}/pay', [SalaryController::class, 'pay'])->name('pay');
    });
});
```

## 🔧 **الوسائط (Middleware)**

### **1. CheckPermission:**
```php
class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!PermissionHelper::can($permission)) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }
        
        return $next($request);
    }
}
```

### **2. CheckBranchAccess:**
```php
class CheckBranchAccess
{
    public function handle(Request $request, Closure $next)
    {
        $branchId = $request->route('branch') ?? $request->input('branch_id');
        
        if ($branchId && !PermissionHelper::canForBranch('access', $branchId)) {
            abort(403, 'غير مصرح لك بالوصول لهذا الفرع');
        }
        
        return $next($request);
    }
}
```

### **3. ActivityLogger:**
```php
class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'branch_id' => auth()->user()->branch_id,
            ]);
        }
        
        return $response;
    }
}
```

### **4. ErrorLogger:**
```php
class ErrorLogger
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error in request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (config('app.debug')) {
                throw $e;
            }
            
            abort(500, 'حدث خطأ في النظام');
        }
    }
}
```

## 📊 **النماذج (Models)**

### **1. User Model:**
```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'name_ar', 'email', 'password', 'role', 'role_id',
        'permissions', 'phone', 'is_active', 'branch_id', 'employee_number'
    ];
    
    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
    
    // العلاقات
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    // الصلاحيات
    public function hasPermission(string $permission): bool
    {
        if ($this->role && in_array('*', $this->role->permissions)) {
            return true;
        }
        
        if ($this->role && in_array($permission, $this->role->permissions)) {
            return true;
        }
        
        if ($this->permissions && in_array($permission, $this->permissions)) {
            return true;
        }
        
        return false;
    }
}
```

### **2. SystemSetting Model:**
```php
class SystemSetting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'name_ar', 'description_ar',
        'category', 'default_value', 'validation_rules',
        'requires_restart', 'is_editable'
    ];
    
    protected $casts = [
        'requires_restart' => 'boolean',
        'is_editable' => 'boolean',
    ];
    
    // الحصول على الإعدادات القابلة للتعديل
    public static function getEditable()
    {
        return self::where('is_editable', true)
                   ->orderBy('category')
                   ->orderBy('sort_order')
                   ->get()
                   ->groupBy('category');
    }
    
    // تعيين إعداد
    public static function set(string $key, $value, string $type = 'string'): bool
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return false;
        }
        
        $setting->value = $value;
        $setting->type = $type;
        $setting->save();
        
        // مسح الكاش
        Cache::forget('system_setting_' . $key);
        
        return true;
    }
    
    // الحصول على إعداد
    public static function get(string $key, $default = null)
    {
        return Cache::remember('system_setting_' . $key, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}
```

### **3. BusinessSetting Model:**
```php
class BusinessSetting extends Model
{
    protected $fillable = [
        'business_name', 'business_name_ar', 'description', 'phone', 'email',
        'address', 'logo', 'currency', 'currency_symbol', 'currency_symbol_placement',
        'default_profit_percent', 'timezone', 'date_format', 'time_format'
    ];
    
    protected $casts = [
        'default_profit_percent' => 'decimal:2',
    ];
    
    // الحصول على المثيل الوحيد
    public static function getInstance()
    {
        $setting = self::first();
        
        if (!$setting) {
            $setting = self::create([
                'business_name' => 'SystemHF',
                'business_name_ar' => 'نظام إدارة معرض الأثاث',
                'currency' => 'EGP',
                'currency_symbol' => 'ج.م',
                'currency_symbol_placement' => 'after',
                'default_profit_percent' => 30.00,
                'timezone' => 'Africa/Cairo',
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s',
            ]);
        }
        
        return $setting;
    }
    
    // تنسيق العملة
    public function formatCurrency(float $amount): string
    {
        $formatted = number_format($amount, 2);
        
        if ($this->currency_symbol_placement === 'before') {
            return $this->currency_symbol . ' ' . $formatted;
        }
        
        return $formatted . ' ' . $this->currency_symbol;
    }
    
    // الحصول على URL الشعار
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return Storage::disk('public')->url($this->logo);
        }
        
        return asset('images/default-logo.png');
    }
}
```

## 📝 **التحكم (Controllers)**

### **1. SystemSettingsController:**
```php
class SystemSettingsController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $settingsByCategory = SystemSetting::getEditable();
        return view('system-settings.index', compact('settingsByCategory'));
    }
    
    // تحديث الإعدادات
    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $settings = $request->input('settings', []);
                
                foreach ($settings as $key => $value) {
                    if (!ConfigurationService::validateSetting($key, $value)) {
                        throw new \Exception("قيمة غير صالحة للإعداد: {$key}");
                    }
                    
                    $type = $this->determineType($value);
                    SystemSetting::set($key, $value, $type);
                }
            });
            
            ConfigurationService::clearCache();
            
            return redirect()->back()->with('success', 'تم تحديث إعدادات النظام بنجاح');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء تحديث الإعدادات: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    // تحديد نوع القيمة
    private function determineType($value): string
    {
        if (is_bool($value)) return 'boolean';
        if (is_numeric($value)) return is_int($value) ? 'integer' : 'decimal';
        if (is_array($value)) return 'json';
        return 'string';
    }
}
```

### **2. BusinessSettingsController:**
```php
class BusinessSettingsController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $settings = BusinessSetting::getInstance();
        $timezones = BusinessSetting::getTimezones();
        $dateFormats = BusinessSetting::getDateFormats();
        $timeFormats = BusinessSetting::getTimeFormats();
        $currencies = BusinessSetting::getCurrencies();
        
        return view('business-settings.index', compact(
            'settings', 'timezones', 'dateFormats', 'timeFormats', 'currencies'
        ));
    }
    
    // تحديث الإعدادات
    public function update(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_name_ar' => 'required|string|max:255',
            'default_profit_percent' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'currency_symbol_placement' => 'required|in:before,after',
            'timezone' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
        ]);
        
        $settings = BusinessSetting::getInstance();
        
        // معالجة رفع الشعار
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            
            $logoPath = $request->file('logo')->store('business/logos', 'public');
            $settings->logo = $logoPath;
        }
        
        // تحديث الإعدادات
        $settings->update($request->except(['logo']));
        
        // تعيين المنطقة الزمنية
        config(['app.timezone' => $settings->timezone]);
        
        // مسح الكاش
        BusinessSettingsService::clearCache();
        
        return redirect()->route('business-settings.index')
                        ->with('success', 'تم تحديث إعدادات الأعمال بنجاح!');
    }
}
```

## 🎨 **الواجهات (Views)**

### **1. صفحة system-settings:**
- **التبويبات:** منظمة حسب الفئات
- **البحث:** في جميع الإعدادات
- **الحقول:** حسب نوع البيانات
- **الأدوات:** تصدير، استيراد، إعادة تعيين
- **الروابط:** إعدادات الأعمال، الأدوات المتقدمة

### **2. صفحة business-settings:**
- **الواجهة:** تصميم حديث ومتجاوب
- **الحقول:** جميع إعدادات الأعمال
- **إدارة الملفات:** رفع وإدارة الشعار
- **التحقق:** قواعد تحقق شاملة
- **التحديث:** حفظ فوري

## 🔧 **الأدوات المتقدمة**

### **1. إدارة الكاش:**
- مسح جميع أنواع الكاش
- إعادة تشغيل الكاش
- مراقبة استخدام الكاش

### **2. إدارة قوائم الانتظار:**
- إعادة تشغيل قوائم الانتظار
- مراقبة حالة القوائم
- تنظيف القوائم

### **3. وضع الصيانة:**
- تفعيل/إلغاء وضع الصيانة
- رسائل مخصصة
- استثناء عناوين IP

### **4. تحسين قاعدة البيانات:**
- تحليل الأداء
- تحسين الاستعلامات
- تنظيف البيانات

### **5. إدارة السجلات:**
- عرض السجلات
- تصدير السجلات
- مسح السجلات القديمة

## 📊 **المراقبة والتتبع**

### **1. سجلات النشاط:**
- تتبع جميع العمليات
- تسجيل المستخدمين
- تسجيل التغييرات

### **2. مقاييس الأداء:**
- استخدام الموارد
- وقت الاستجابة
- استخدام قاعدة البيانات

### **3. التقارير:**
- تقارير شاملة
- تصدير البيانات
- رسوم بيانية

## 🚀 **الأداء والتحسين**

### **1. التخزين المؤقت:**
- كاش الإعدادات
- كاش الأعمال
- كاش الصلاحيات

### **2. تحسين قاعدة البيانات:**
- فهارس محسنة
- استعلامات محسنة
- علاقات محسنة

### **3. تحسين الواجهات:**
- تحميل تدريجي
- صور محسنة
- أصول محسنة

## 🔒 **الأمان**

### **1. المصادقة:**
- Laravel Sanctum
- رموز آمنة
- إدارة الجلسات

### **2. الصلاحيات:**
- فحص دقيق
- عزل البيانات
- سجلات النشاط

### **3. الحماية:**
- CSRF protection
- XSS protection
- SQL injection protection

## 📱 **التوافق**

### **1. المتصفحات:**
- Chrome (أحدث إصدار)
- Firefox (أحدث إصدار)
- Safari (أحدث إصدار)
- Edge (أحدث إصدار)

### **2. الأجهزة:**
- أجهزة سطح المكتب
- الأجهزة اللوحية
- الهواتف الذكية

### **3. الشاشات:**
- شاشات عالية الدقة
- شاشات متوسطة
- شاشات صغيرة

## 🔄 **التحديثات والصيانة**

### **1. التحديثات التلقائية:**
- فحص التحديثات
- تنزيل التحديثات
- تثبيت التحديثات

### **2. النسخ الاحتياطي:**
- نسخ احتياطي تلقائي
- استعادة البيانات
- تشفير النسخ

### **3. الصيانة:**
- صيانة دورية
- تنظيف البيانات
- تحسين الأداء

---

**تم التطوير بواسطة فريق SystemHF** 🚀✨

**آخر تحديث:** 24 أغسطس 2025
**الإصدار:** 2.1.0
