# نظام إدارة معرض الأثاث - الوثائق الشاملة

## نظرة عامة
نظام متكامل لإدارة معرض الأثاث مبني على Laravel 12 مع واجهة مستخدم حديثة ومتجاوبة.

## الإصدارات والتحديثات

### الإصدار 1.6.0 - إصلاح زر إضافة مورد
**تاريخ الإصدار:** 2025-08-23  
**نوع التحديث:** إصلاح  
**الحالة:** الإصدار الحالي ✅

#### الإصلاحات:
- إصلاح زر إضافة مورد جديد في modal
- تغيير @click إلى onclick للتوافق مع JavaScript
- تأكد من عمل modal إضافة المورد بشكل صحيح

#### الملفات المحدثة:
- `resources/views/products/create.blade.php`

---

### الإصدار 1.5.0 - ComboBox قابل للكتابة
**تاريخ الإصدار:** 2025-08-23  
**نوع التحديث:** تحديث فرعي  
**الحالة:** مكتمل ✅

#### الميزات الجديدة:
- ComboBox قابل للكتابة بحرية
- فلترة فورية أثناء الكتابة
- البحث بالاسم أو رقم الهاتف
- عرض النتائج مباشرة أثناء الكتابة
- تحسين تجربة المستخدم للبحث السريع

#### التحسينات:
- إزالة readonly من الحقل
- فلترة ذكية للنتائج
- تحسين وظيفة مسح الاختيار
- إيقاف البحث مؤقتاً عند الاختيار
- التركيز التلقائي على الحقل بعد المسح

#### الملفات المحدثة:
- `resources/views/products/create.blade.php`

---

### الإصدار 1.4.0 - تطوير ComboBox للموردين
**تاريخ الإصدار:** 2025-08-23  
**نوع التحديث:** تحديث فرعي  
**الحالة:** مكتمل ✅

#### الميزات الجديدة:
- تحويل search dropdown إلى ComboBox حقيقي
- إضافة أيقونة dropdown مع تأثير دوران
- إضافة زر مسح للاختيار
- عرض المورد المحدد بتصميم جميل
- تحسين تجربة المستخدم مع readonly/editable

#### التحسينات:
- تصميم ComboBox احترافي
- دعم كامل للتنقل بلوحة المفاتيح
- عرض معلومات المورد المحدد
- إمكانية تغيير الاختيار بسهولة

#### الملفات المحدثة:
- `resources/views/products/create.blade.php`

---

### الإصدار 1.3.0 - إصلاح نظام اختيار المورد
**تاريخ الإصدار:** 2025-08-22  
**نوع التحديث:** إصلاح  
**الحالة:** مكتمل ✅

#### الإصلاحات:
- إصلاح search dropdown للموردين ليعمل بشكل صحيح
- إصلاح نافذة إضافة مورد جديد
- استبدال Alpine.js بـ JavaScript عادي لضمان التوافق
- إصلاح مشاكل العرض والتفاعل

#### التحسينات:
- كود JavaScript أكثر استقراراً
- معالجة أفضل للأحداث
- دعم كامل للبحث والتنقل بلوحة المفاتيح

#### الملفات المحدثة:
- `resources/views/products/create.blade.php`

---

### الإصدار 1.2.0 - تطوير نظام اختيار المورد
**تاريخ الإصدار:** 2025-08-22  
**نوع التحديث:** تحديث فرعي  
**الحالة:** مكتمل ✅

#### الميزات الجديدة:
- تحويل حقل المورد إلى search dropdown متطور
- إضافة نافذة منبثقة لإضافة مورد جديد مباشرة من صفحة المنتج
- البحث في الموردين بالاسم أو رقم الهاتف
- دعم التنقل بلوحة المفاتيح (Arrow keys, Enter, Escape)
- تحديث قائمة الموردين تلقائياً بعد إضافة مورد جديد

#### التحسينات:
- واجهة مستخدم محسنة لاختيار المورد
- تجربة مستخدم أفضل في إدارة الموردين
- دعم JSON response في SupplierController

#### الملفات المحدثة:
- `resources/views/products/create.blade.php`
- `app/Http/Controllers/SupplierController.php`

---

### الإصدار 1.1.0 - تبسيط نظام الموردين
**تاريخ الإصدار:** 2025-08-22  
**نوع التحديث:** تحديث فرعي  
**الحالة:** مكتمل ✅

#### الميزات الجديدة:
- تبسيط نظام الموردين وإزالة الحقول غير المطلوبة
- إزالة الحقول التالية:
  - اسم المورد بالإنجليزية
  - الفرع
  - المدينة
  - الرقم الضريبي
  - السجل التجاري
  - حد الائتمان
  - الرصيد الحالي
  - شروط الدفع

#### التغييرات التقنية:
- تحديث migration `simplify_suppliers_table`
- تحديث `Supplier` Model
- تحديث `SupplierController`
- تحديث جميع صفحات الموردين (create, edit, show, index)

#### الملفات المحدثة:
- `database/migrations/2025_08_22_220954_simplify_suppliers_table.php`
- `app/Models/Supplier.php`
- `app/Http/Controllers/SupplierController.php`
- `resources/views/suppliers/create.blade.php`
- `resources/views/suppliers/edit.blade.php`
- `resources/views/suppliers/show.blade.php`
- `resources/views/suppliers/index.blade.php`

---

### الإصدار 1.0.0 - الإصدار الأساسي
**تاريخ الإصدار:** 2025-08-22  
**نوع التحديث:** إصدار رئيسي  
**الحالة:** مكتمل ✅

#### الميزات الأساسية:
- نظام إدارة العملاء الكامل
- نظام إدارة المنتجات مع نظام وسائط متطور
- نظام إدارة الفواتير
- نظام إدارة المدفوعات
- نظام إدارة الفئات
- نظام إدارة الموظفين
- نظام إدارة الفروع
- نظام إدارة الحسابات
- نظام إدارة المصروفات
- نظام إدارة طرق الدفع
- نظام إدارة الموردين
- إعدادات الأعمال المركزية
- نظام إدارة الإصدارات

#### الميزات المتقدمة:
- نظام أسعار تلقائي مع حساب نسبة الربح
- نظام وسائط متطور للصور والفيديوهات
- معاينة مباشرة للملفات
- إمكانية إعادة ترتيب الصور
- نظام تلقائي لتوليد كود المنتج
- دعم كامل للغة العربية (RTL)
- تصميم متجاوب للهواتف والحواسيب

#### التقنيات المستخدمة:
- **Backend:** Laravel 12, PHP 8.3
- **Frontend:** Tailwind CSS 4.0, Alpine.js
- **Database:** MySQL
- **Architecture:** Repository Pattern, Service Layer
- **Validation:** Form Requests
- **API:** API Resources

---

## الملفات الرئيسية

### النماذج (Models)
- `app/Models/Customer.php` - نموذج العملاء
- `app/Models/Product.php` - نموذج المنتجات
- `app/Models/Supplier.php` - نموذج الموردين
- `app/Models/BusinessSetting.php` - إعدادات الأعمال
- `app/Models/SystemVersion.php` - إدارة الإصدارات

### المتحكمات (Controllers)
- `app/Http/Controllers/CustomerController.php` - إدارة العملاء
- `app/Http/Controllers/ProductController.php` - إدارة المنتجات
- `app/Http/Controllers/SupplierController.php` - إدارة الموردين
- `app/Http/Controllers/BusinessSettingsController.php` - إعدادات الأعمال

### الخدمات (Services)
- `app/Services/CustomerService.php` - خدمة العملاء
- `app/Services/BusinessSettingsService.php` - خدمة إعدادات الأعمال

### المستودعات (Repositories)
- `app/Repositories/CustomerRepository.php` - مستودع العملاء
- `app/Repositories/Contracts/CustomerRepositoryInterface.php` - واجهة مستودع العملاء

### الطلبات (Requests)
- `app/Http/Requests/CustomerRequest.php` - طلب العميل

### الموارد (Resources)
- `app/Http/Resources/CustomerResource.php` - مورد العميل

### الاستثناءات (Exceptions)
- `app/Exceptions/CustomerException.php` - استثناء العميل

---

## قاعدة البيانات

### الجداول الرئيسية
- `customers` - العملاء
- `products` - المنتجات
- `suppliers` - الموردين
- `categories` - الفئات
- `invoices` - الفواتير
- `payments` - المدفوعات
- `employees` - الموظفين
- `branches` - الفروع
- `accounts` - الحسابات
- `expenses` - المصروفات
- `payment_methods` - طرق الدفع
- `business_settings` - إعدادات الأعمال
- `system_versions` - إدارة الإصدارات

### العلاقات
- المنتج ينتمي لفئة ومورد
- العميل ينتمي لفرع
- المورد ينتمي لفرع
- الفاتورة تنتمي لعميل وموظف

---

## الواجهات (Views)

### التخطيطات
- `resources/views/layouts/app.blade.php` - التخطيط الرئيسي

### الصفحات الرئيسية
- `resources/views/dashboard.blade.php` - لوحة التحكم
- `resources/views/customers/` - صفحات العملاء
- `resources/views/products/` - صفحات المنتجات
- `resources/views/suppliers/` - صفحات الموردين
- `resources/views/business-settings/` - إعدادات الأعمال

### المكونات
- `resources/views/components/footer.blade.php` - تذييل الصفحة الموحد

---

## الأوامر (Commands)
- `php artisan system:version` - إدارة إصدارات النظام

---

## التكوين (Configuration)

### Tailwind CSS
- `tailwind.config.js` - تكوين Tailwind مع دعم RTL
- `resources/css/app.css` - الأنماط المخصصة

### JavaScript
- `resources/js/app.js` - الوظائف الرئيسية مع Alpine.js

---

## الملفات المحذوفة
- `items` - تم حذف نظام العناصر
- `collections` - تم حذف نظام المجموعات

---

## ملاحظات التطوير
- جميع الصفحات تستخدم التصميم الجديد الموحد
- دعم كامل للغة العربية والتصميم RTL
- نظام متجاوب للهواتف والحواسيب
- استخدام Repository Pattern و Service Layer
- معالجة شاملة للأخطاء والاستثناءات

---

## التحديثات المستقبلية
- إضافة المزيد من التقارير والإحصائيات
- تطوير نظام إشعارات متقدم
- إضافة نظام نسخ احتياطي تلقائي
- تطوير واجهة API متقدمة

---

*آخر تحديث: 2025-08-22*
