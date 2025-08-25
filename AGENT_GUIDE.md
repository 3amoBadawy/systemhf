# 🤖 دليل الـ Agent الشامل - SystemHF

## 🚨 **تحذير مهم: اقرأ هذا الدليل أولاً قبل أي عمل!**

هذا الدليل يحتوي على **كل المعلومات المطلوبة** لفهم النظام والعمل عليه. **ممنوع** البدء في أي تطوير بدون قراءة هذا الدليل بالكامل.

---

## 📚 **الملفات المطلوب قراءتها (إلزامي)**

### **1. [README.md](README.md)** - نظرة عامة
> **اقرأ أولاً:** فهم شامل للنظام والميزات
> **المحتوى:** نظرة عامة، الميزات، التثبيت، الاستخدام
> **الوقت المطلوب:** 10-15 دقيقة

### **2. [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md)** - تفاصيل تقنية
> **اقرأ ثانياً:** فهم البنية التقنية
> **المحتوى:** النماذج، المسارات، الخدمات، قاعدة البيانات
> **الوقت المطلوب:** 20-30 دقيقة

### **3. [RULES.md](RULES.md)** - قواعد النظام
> **اقرأ ثالثاً:** فهم القواعد الإلزامية
> **المحتوى:** قواعد التطوير، الأمان، الأداء، الاختبار
> **الوقت المطلوب:** 15-20 دقيقة

### **4. [DOCS_INDEX.md](DOCS_INDEX.md)** - فهرس التوثيق
> **اقرأ رابعاً:** فهم جميع الملفات المتاحة
> **المحتوى:** فهرس شامل، أدلة حسب الدور، أوقات القراءة
> **الوقت المطلوب:** 5-10 دقائق

---

## 🏗️ **هيكل النظام الحالي**

### **📊 النماذج الأساسية:**
```
✅ موجودة:
- User (المستخدمين)
- Employee (الموظفين)
- Branch (الفروع)
- Product (المنتجات)
- Category (الفئات)
- Customer (العملاء)
- Invoice (الفواتير)
- Shift (المناوبات)
- Attendance (الحضور)
- Salary (الرواتب)
- Commission (العمولات)
- Role (الأدوار)
- Permission (الصلاحيات)
```

### **🔗 العلاقات الأساسية:**
```
✅ موجودة:
- User -> Employee (one-to-one)
- Employee -> Branch (many-to-one)
- Employee -> Shift (many-to-many)
- Employee -> Attendance (one-to-many)
- Employee -> Salary (one-to-many)
- Employee -> Commission (one-to-many)
- User -> Role (many-to-many)
- Role -> Permission (many-to-many)
```

### **🌐 المسارات الأساسية:**
```
✅ موجودة:
- /attendance/* (نظام الحضور)
- /salaries/* (نظام الرواتب)
- /roles/* (إدارة الأدوار)
- /permissions/* (إدارة الصلاحيات)
- /shifts/* (إدارة المناوبات)
- /branches/* (إدارة الفروع)
- /employees/* (إدارة الموظفين)
- /products/* (إدارة المنتجات)
- /customers/* (إدارة العملاء)
- /invoices/* (إدارة الفواتير)
```

---

## 🚨 **قواعد إلزامية (لا يمكن مخالفتها)**

### **❌ ممنوع تماماً:**
1. **بدء التطوير بدون قراءة التوثيق**
2. **إضافة ميزات منفصلة عن النظام**
3. **استخدام قيم ثابتة في الكود**
4. **ترك كود غير موثق**
5. **عدم ربط الميزات الجديدة بالنظام**

### **✅ مطلوب إلزامياً:**
1. **قراءة التوثيق الشامل أولاً**
2. **ربط كل ميزة جديدة بالنظام**
3. **استخدام متغيرات من Admin Panel فقط**
4. **كتابة توثيق شامل لكل ميزة**
5. **اختبار شامل قبل النشر**

---

## 🔧 **إجراءات التطوير الإلزامية**

### **📋 قبل التطوير:**
```
□ قراءة README.md
□ قراءة SYSTEM_DOCUMENTATION.md
□ قراءة RULES.md
□ قراءة DOCS_INDEX.md
□ فهم المتطلبات بالكامل
□ تصميم ربط الميزة بالنظام
□ تصميم قاعدة البيانات
□ كتابة الاختبارات
□ كتابة التوثيق
```

### **⚙️ أثناء التطوير:**
```
□ استخدام متغيرات من Admin Panel
□ ربط كل شيء بالنظام
□ كتابة تعليقات واضحة
□ اختبار كل خطوة
□ تحديث التوثيق مع التطوير
□ عدم ترك كود غير موثق
```

### **✅ بعد التطوير:**
```
□ تحديث README.md
□ تحديث SYSTEM_DOCUMENTATION.md
□ تحديث CHANGELOG.md
□ تحديث DOCS_INDEX.md
□ اختبار الروابط
□ مراجعة شاملة
□ تسليم التوثيق المحدث
```

---

## 🔗 **ربط النظام (إلزامي)**

### **كل ميزة جديدة يجب أن تكون مرتبطة بـ:**
```
✅ نظام الصلاحيات (Role & Permission)
✅ نظام المستخدمين (User & Employee)
✅ نظام الفروع (Branch)
✅ نظام الإعدادات (Settings)
✅ نظام التقارير (Reports)
✅ نظام السجلات (Logs)
✅ نظام الإشعارات (Notifications)
```

### **أمثلة على الربط:**
```php
// ✅ صحيح - ميزة مربوطة
class NewFeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:new-feature-access');
    }
    
    public function index()
    {
        // فحص الصلاحيات
        if (!auth()->user()->can('new-feature-view')) {
            abort(403);
        }
        
        // ربط بالفرع
        $data = NewFeature::where('branch_id', auth()->user()->employee->branch_id)->get();
        
        // تسجيل العملية
        Log::info('User accessed new feature', [
            'user_id' => auth()->id(),
            'feature' => 'new-feature',
            'action' => 'view'
        ]);
        
        return view('new-feature.index', compact('data'));
    }
}

// ❌ خطأ - ميزة منفصلة
class NewFeatureController extends Controller
{
    public function index()
    {
        // لا فحص صلاحيات
        // لا ربط بالفرع
        // لا تسجيل
        return view('new-feature.index');
    }
}
```

---

## ⚙️ **متغيرات النظام (إلزامي)**

### **كل إعداد يجب أن يكون متغير من Admin Panel:**
```php
// ✅ صحيح - متغير من Admin Panel
class SettingsController extends Controller
{
    public function getSetting($key)
    {
        return Setting::where('key', $key)->value('value') ?? config("defaults.{$key}");
    }
}

// في الكود
$maxFileSize = $this->getSetting('max_file_size');
$allowedTypes = $this->getSetting('allowed_file_types');

// ❌ خطأ - قيمة ثابتة
$maxFileSize = 1024 * 1024; // 1MB
$allowedTypes = ['jpg', 'png', 'pdf'];
```

### **أمثلة على المتغيرات المطلوبة:**
```
✅ إعدادات الأعمال:
- اسم الشركة
- العملة
- المنطقة الزمنية
- لغة النظام

✅ إعدادات النظام:
- حجم الملفات المسموح
- أنواع الملفات المدعومة
- عدد المحاولات المسموحة
- مهلة الجلسة

✅ إعدادات الميزات:
- عدد العناصر في الصفحة
- مهلة التحديث
- إعدادات الإشعارات
- إعدادات التقارير
```

---

## 📝 **توثيق الميزات الجديدة (إلزامي)**

### **كل ميزة جديدة تحتاج:**
```
✅ ملف Blade View
✅ Controller مع تعليقات
✅ Model مع العلاقات
✅ Migration لقاعدة البيانات
✅ Seeder للبيانات الأولية
✅ Route في web.php
✅ Middleware للصلاحيات
✅ Service للمنطق التجاري
✅ Test للاختبارات
✅ توثيق في README.md
✅ توثيق في SYSTEM_DOCUMENTATION.md
✅ إضافة في CHANGELOG.md
✅ إضافة في DOCS_INDEX.md
```

### **مثال على التوثيق:**
```php
/**
 * نظام إدارة المهام الجديد
 * 
 * @package App\Http\Controllers
 * @author Agent Name
 * @version 1.0.0
 * @since 2025-08-23
 */
class TaskController extends Controller
{
    /**
     * عرض قائمة المهام
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     * 
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        // فحص الصلاحيات
        $this->authorize('view-tasks');
        
        // ربط بالفرع
        $tasks = Task::where('branch_id', auth()->user()->employee->branch_id)
                    ->with(['employee', 'category'])
                    ->paginate($this->getSetting('items_per_page'));
        
        // تسجيل العملية
        Log::info('User viewed tasks', [
            'user_id' => auth()->id(),
            'branch_id' => auth()->user()->employee->branch_id
        ]);
        
        return view('tasks.index', compact('tasks'));
    }
}
```

---

## 🧪 **اختبار الميزات الجديدة (إلزامي)**

### **كل ميزة تحتاج اختبارات:**
```php
// ✅ اختبارات شاملة
class TaskTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_view_tasks_with_permission()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'task-manager']);
        $permission = Permission::create(['name' => 'view-tasks']);
        $role->permissions()->attach($permission);
        $user->roles()->attach($role);
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }
    
    public function test_user_cannot_view_tasks_without_permission()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/tasks');
        
        $response->assertStatus(403);
    }
}
```

---

## 🔄 **تحديث التوثيق التلقائي (إلزامي)**

### **مع كل ميزة جديدة:**
```
□ تحديث README.md (إضافة الميزة)
□ تحديث SYSTEM_DOCUMENTATION.md (تفاصيل تقنية)
□ تحديث CHANGELOG.md (سجل التغييرات)
□ تحديث DOCS_INDEX.md (فهرس جديد)
□ إنشاء ملفات توثيق إضافية
□ اختبار الروابط والمراجع
□ مراجعة شاملة للتوثيق
```

---

## 🚨 **عقوبات المخالفات**

### **مخالفات خطيرة:**
```
🚨 عدم قراءة التوثيق:
- إيقاف فوري من العمل
- مراجعة شاملة للكود
- تدريب إجباري

🚨 عدم تحديث التوثيق:
- تحذير كتابي
- مراجعة إضافية
- تعليق مؤقت

🚨 ميزات منفصلة:
- إيقاف فوري
- إعادة كتابة كاملة
- إجراءات تأديبية
```

---

## ⭐ **مكافآت الالتزام**

### **مكافآت فورية:**
```
✅ شكر وتقدير
✅ نقاط إضافية
✅ مزايا خاصة
✅ اعتراف بالإنجاز
```

### **مكافآت طويلة المدى:**
```
🏆 ترقية في المشروع
🏆 مسؤوليات إضافية
🏆 تدريب متقدم
🏆 مشاركة في القرارات
🏆 اعتراف دولي
```

---

## 📞 **التواصل والدعم**

### **للمساعدة:**
```
📧 البريد الإلكتروني: agent-support@systemhf.com
🐛 GitHub Issues: [Agent Support](https://github.com/your-username/systemhf/issues)
💬 Discord: [Agent Support Channel](https://discord.gg/systemhf)
```

### **للتقييم:**
```
📊 تقييم القواعد: feedback@systemhf.com
🤖 تقييم الـ Agent: agent-feedback@systemhf.com
🚨 الإبلاغ عن مخالفات: violations@systemhf.com
```

---

## 🎯 **خلاصة سريعة**

### **قبل البدء:**
1. **اقرأ التوثيق بالكامل** (4 ملفات رئيسية)
2. **افهم هيكل النظام** (النماذج، العلاقات، المسارات)
3. **تعرف على القواعد** (إلزامية، لا يمكن مخالفتها)

### **أثناء العمل:**
1. **اربط كل شيء بالنظام** (صلاحيات، مستخدمين، فروع)
2. **استخدم متغيرات من Admin Panel** (لا قيم ثابتة)
3. **حدث التوثيق مع التطوير** (تحديث تلقائي)

### **عند الانتهاء:**
1. **حدث جميع ملفات التوثيق**
2. **اختبر الروابط والمراجع**
3. **راجع شاملة**
4. **سلم التوثيق المحدث**

---

## ⚠️ **تذكير أخير**

**هذا الدليل إلزامي للقراءة!**  
**لا يمكن البدء في أي عمل بدون قراءته!**  
**مخالفة القواعد تؤدي لعقوبات صارمة!**  
**الالتزام يؤدي لمكافآت كبيرة!**

---

*آخر تحديث:* 23 أغسطس 2025  
*الإصدار:* 1.0.0  
*الحالة:* إلزامي ومُطبق  
*المؤلف:* SystemHF Team
