# 🚀 **دليل التطوير - SystemHF**

## 📋 **متطلبات النظام**

- **PHP:** 8.2+
- **Laravel:** 11.x
- **Composer:** 2.x
- **Node.js:** 18+ (لـ Vite)
- **Database:** MySQL 8.0+ أو PostgreSQL 13+

---

## 🛠️ **أدوات التطوير المثبتة**

### **1. أدوات التحليل الثابت**
```bash
# PHPStan - تحليل ثابت متقدم
./vendor/bin/phpstan analyse

# Larastan - تكامل PHPStan مع Laravel
./vendor/bin/phpstan analyse --configuration=phpstan.neon

# Psalm - تحليل ثابت متقدم مع دعم TypeScript
./vendor/bin/psalm
```

### **2. أدوات جودة الكود**
```bash
# Laravel Pint - تنسيق الكود
./vendor/bin/pint

# PHPMD - كاشف رائحة الكود
./vendor/bin/phpmd app text phpmd.xml

# Deptrac - فحص التبعيات والهندسة المعمارية
./vendor/bin/deptrac analyse deptrac.yaml
```

### **3. أوامر Composer السريعة**
```bash
# فحص شامل للجودة
composer quality

# فحص سريع
composer check

# تشغيل التحليل الثابت
composer analyse

# تشغيل Psalm
composer psalm

# تشغيل PHPMD
composer phpmd

# تشغيل Deptrac
composer deptrac

# تشغيل الاختبارات
composer test
```

---

## 🏗️ **الهندسة المعمارية**

### **1. طبقات النظام**
```
┌─────────────────────────────────────┐
│              HTTP Layer             │
│        (Controllers, Middleware)    │
├─────────────────────────────────────┤
│           Application Layer         │
│         (Services, Use Cases)       │
├─────────────────────────────────────┤
│             Domain Layer            │
│           (Models, Entities)        │
├─────────────────────────────────────┤
│         Infrastructure Layer        │
│      (Repositories, Providers)      │
└─────────────────────────────────────┘
```

### **2. قواعد التبعيات**
- **Domain** → لا يعتمد على أي طبقة أخرى
- **Application** → يعتمد على Domain فقط
- **Infrastructure** → يعتمد على Domain و Application
- **HTTP** → يعتمد على جميع الطبقات

### **3. نمط التصميم**
- **Controllers** → تستدعي Services فقط
- **Services** → تستدعي Repositories أو Models
- **Models** → تحتوي على العلاقات والمنطق الأساسي
- **Repositories** → تتعامل مع قاعدة البيانات

---

## 📝 **معايير الكود**

### **1. تسمية الملفات والفئات**
```php
// Controllers
UserController.php
UserProfileController.php

// Services
UserService.php
AuthenticationService.php

// Models
User.php
UserProfile.php

// Repositories
UserRepository.php
UserProfileRepository.php
```

### **2. تسمية الدوال**
```php
// Controllers
public function index()      // عرض القائمة
public function show($id)    // عرض عنصر واحد
public function create()     // نموذج الإنشاء
public function store()      // حفظ العنصر
public function edit($id)    // نموذج التعديل
public function update($id)  // تحديث العنصر
public function destroy($id) // حذف العنصر

// Services
public function createUser(array $data): User
public function updateUser(int $id, array $data): User
public function deleteUser(int $id): bool
public function getUserById(int $id): ?User
```

### **3. تسمية المتغيرات**
```php
// متغيرات قاعدة البيانات
$user = User::find($id);
$users = User::all();
$userCount = User::count();

// متغيرات الخدمات
$userService = app(UserService::class);
$result = $userService->createUser($data);

// متغيرات الطلبات
$requestData = $request->validated();
$userId = $request->route('user');
```

---

## 🔒 **نظام الصلاحيات والأدوار**

### **1. الأدوار الأساسية**
- **Super Admin** - صلاحيات كاملة
- **Admin** - إدارة الفرع
- **Manager** - إدارة الفريق
- **Employee** - صلاحيات محدودة
- **Customer** - صلاحيات العميل

### **2. الصلاحيات المتاحة**
```php
// إدارة المستخدمين
'users.view'
'users.create'
'users.edit'
'users.delete'

// إدارة المنتجات
'products.view'
'products.create'
'products.edit'
'products.delete'

// إدارة الفواتير
'invoices.view'
'invoices.create'
'invoices.edit'
'invoices.delete'
'invoices.approve'
```

### **3. فحص الصلاحيات**
```php
// في Controllers
if (!auth()->user()->can('users.create')) {
    abort(403, 'غير مصرح لك بإنشاء مستخدمين');
}

// في Views
@can('users.edit')
    <a href="{{ route('users.edit', $user) }}">تعديل</a>
@endcan

// في Middleware
Route::middleware(['auth', 'can:users.view'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
```

---

## 🗄️ **قاعدة البيانات**

### **1. العلاقات الأساسية**
```php
// User - Employee (One-to-One)
class User extends Authenticatable
{
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}

// Branch - Employees (One-to-Many)
class Branch extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

// Invoice - Products (Many-to-Many)
class Invoice extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_products')
                    ->withPivot('quantity', 'price', 'total');
    }
}
```

### **2. Migrations**
```php
// إنشاء جدول جديد
php artisan make:migration create_table_name_table

// تشغيل Migrations
php artisan migrate

// التراجع عن Migration
php artisan migrate:rollback

// إعادة تشغيل Migrations
php artisan migrate:refresh

// إعادة تشغيل مع Seeders
php artisan migrate:refresh --seed
```

### **3. Seeders**
```php
// إنشاء Seeder
php artisan make:seeder TableNameSeeder

// تشغيل Seeder
php artisan db:seed --class=TableNameSeeder

// تشغيل جميع Seeders
php artisan db:seed
```

---

## 🧪 **الاختبارات**

### **1. أنواع الاختبارات**
```bash
# اختبارات الوحدات
./vendor/bin/phpunit --testsuite=Unit

# اختبارات الميزات
./vendor/bin/phpunit --testsuite=Feature

# اختبارات الدخان
./vendor/bin/phpunit --testsuite=Smoke

# جميع الاختبارات
./vendor/bin/phpunit
```

### **2. إنشاء الاختبارات**
```bash
# اختبار Controller
php artisan make:test UserControllerTest

# اختبار Service
php artisan make:test UserServiceTest

# اختبار Feature
php artisan make:test CreateUserTest
```

### **3. أمثلة الاختبارات**
```php
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_users_list()
    {
        $user = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($user)
                        ->get(route('users.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_cannot_create_user_without_permission()
    {
        $user = User::factory()->create(['role' => 'employee']);
        
        $response = $this->actingAs($user)
                        ->post(route('users.store'), []);
        
        $response->assertStatus(403);
    }
}
```

---

## 🚀 **النشر والإنتاج**

### **1. إعدادات الإنتاج**
```bash
# تنظيف الكاش
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إعادة تشغيل الخدمات
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
```

### **2. مراقبة الأداء**
```bash
# مراقبة السجلات
tail -f storage/logs/laravel.log

# مراقبة الأداء
php artisan queue:work --verbose

# تنظيف الملفات المؤقتة
php artisan storage:clear
```

---

## 📚 **الموارد المفيدة**

### **1. وثائق Laravel**
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel API Reference](https://laravel.com/api)
- [Laravel GitHub](https://github.com/laravel/laravel)

### **2. أدوات التطوير**
- [PHPStan Documentation](https://phpstan.org/)
- [Psalm Documentation](https://psalm.dev/)
- [PHPMD Documentation](https://phpmd.org/)
- [Deptrac Documentation](https://deptrac.readthedocs.io/)

### **3. أفضل الممارسات**
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP The Right Way](https://phptherightway.com/)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

---

## 🆘 **استكشاف الأخطاء**

### **1. أخطاء شائعة**
```bash
# خطأ في قاعدة البيانات
php artisan migrate:status
php artisan db:show

# خطأ في الكاش
php artisan cache:clear
php artisan config:clear

# خطأ في الصلاحيات
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### **2. فحص الجودة**
```bash
# فحص شامل
composer quality

# فحص محدد
composer analyse
composer psalm
composer phpmd
```

### **3. إصلاح الأخطاء**
```bash
# إصلاح تنسيق الكود
composer pint

# إصلاح التبعيات
composer install --no-dev
composer dump-autoload

# إصلاح قاعدة البيانات
php artisan migrate:fresh --seed
```

---

## 📞 **الدعم والمساعدة**

### **1. فريق التطوير**
- **Lead Developer:** [اسم المطور]
- **Backend Developer:** [اسم المطور]
- **Frontend Developer:** [اسم المطور]
- **QA Engineer:** [اسم المطور]

### **2. قنوات التواصل**
- **Email:** [البريد الإلكتروني]
- **Slack:** [رابط Slack]
- **GitHub Issues:** [رابط GitHub]
- **Documentation:** [رابط الوثائق]

---

**آخر تحديث:** 25 أغسطس 2025  
**الإصدار:** 2.1.0  
**الحالة:** مستقر ومُختبر ✅



