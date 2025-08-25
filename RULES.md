# 📋 **قواعد التطوير الإلزامية - SystemHF**

## 🎯 **معلومات عامة**

- **اسم النظام:** نظام إدارة معرض الأثاث - SystemHF
- **الإصدار:** 2.1.0
- **تاريخ التحديث:** 24 أغسطس 2025
- **إطار العمل:** Laravel 11
- **اللغة الأساسية:** العربية (مع دعم الإنجليزية)

## 🚀 **الإصدار 2.1.0 - إصلاح شامل للنظام**

### ✅ **الإصلاحات الرئيسية:**

#### **1. إصلاح جميع أخطاء 500 Server Error:**
- ✅ إصلاح جميع أخطاء قاعدة البيانات
- ✅ إصلاح جميع العلاقات بين النماذج
- ✅ إصلاح جميع متغيرات `$this` في Views
- ✅ إصلاح جميع Routes والمسارات

#### **2. إصلاح نظام الصلاحيات والأدوار:**
- ✅ إصلاح نظام RBAC (Role-Based Access Control)
- ✅ إضافة نظام الصلاحيات المتقدم
- ✅ إصلاح إدارة المستخدمين والموظفين
- ✅ إضافة نظام الأدوار المخصصة

#### **3. إضافة صفحات مفقودة:**
- ✅ إضافة صفحة system-settings محسنة
- ✅ إضافة صفحة business-settings محسنة
- ✅ إضافة صفحة الأدوات المتقدمة
- ✅ إضافة صفحة إدارة النشاطات

#### **4. إصلاح قاعدة البيانات:**
- ✅ إصلاح جميع الجداول والعلاقات
- ✅ إضافة الأعمدة المفقودة
- ✅ إصلاح البيانات المخزنة
- ✅ تحسين الأداء

#### **5. إضافة SystemHelper:**
- ✅ إضافة دوال مفقودة للنظام
- ✅ إضافة ValidationHelper
- ✅ إضافة PermissionHelper
- ✅ إضافة ConfigurationService

#### **6. إصلاح جميع العلاقات:**
- ✅ إصلاح علاقات User-Employee
- ✅ إصلاح علاقات Invoice-Product
- ✅ إصلاح علاقات Salary-Employee
- ✅ إصلاح علاقات Branch-Settings

#### **7. إضافة middleware متقدم:**
- ✅ إضافة ActivityLogger
- ✅ إضافة ErrorLogger
- ✅ إضافة PermissionMiddleware
- ✅ إضافة BranchMiddleware

#### **8. إصلاح جميع Routes:**
- ✅ إصلاح route naming conventions
- ✅ إصلاح جميع المسارات
- ✅ إضافة المسارات المفقودة
- ✅ تنظيم المسارات

## 📋 **القواعد الإلزامية للتطوير**

### **1. قواعد التطوير العامة:**

#### **أ. التوثيق الإلزامي:**
- ✅ **يجب** توثيق جميع الدوال والكلاسات
- ✅ **يجب** تحديث ملفات MD مع كل تغيير
- ✅ **يجب** كتابة تعليقات باللغة العربية
- ✅ **يجب** توثيق جميع المتغيرات والمعاملات

#### **ب. معايير الكود:**
- ✅ **يجب** اتباع معايير PSR-12
- ✅ **يجب** استخدام أسماء واضحة ومفهومة
- ✅ **يجب** تجنب الكود المكرر
- ✅ **يجب** استخدام Type Hints

#### **ج. إدارة الأخطاء:**
- ✅ **يجب** استخدام try-catch blocks
- ✅ **يجب** تسجيل جميع الأخطاء
- ✅ **يجب** إرجاع رسائل خطأ واضحة
- ✅ **يجب** عدم عرض أخطاء النظام للمستخدم

### **2. قواعد قاعدة البيانات:**

#### **أ. تصميم الجداول:**
- ✅ **يجب** استخدام أسماء واضحة للجداول
- ✅ **يجب** إضافة timestamps لجميع الجداول
- ✅ **يجب** استخدام soft deletes عند الحاجة
- ✅ **يجب** إضافة فهارس للعمود المستخدم في البحث

#### **ب. العلاقات:**
- ✅ **يجب** تعريف العلاقات بشكل صحيح
- ✅ **يجب** استخدام foreign keys
- ✅ **يجب** إضافة cascade delete عند الحاجة
- ✅ **يجب** تجنب العلاقات الدائرية

#### **ج. الهجرات:**
- ✅ **يجب** كتابة هجرات قابلة للتراجع
- ✅ **يجب** اختبار الهجرات قبل النشر
- ✅ **يجب** استخدام seeders للبيانات الأساسية
- ✅ **يجب** نسخ احتياطي قبل الهجرات

### **3. قواعد الأمان:**

#### **أ. المصادقة والتفويض:**
- ✅ **يجب** التحقق من الصلاحيات في كل طلب
- ✅ **يجب** استخدام middleware للصلاحيات
- ✅ **يجب** تشفير كلمات المرور
- ✅ **يجب** حماية من CSRF attacks

#### **ب. حماية البيانات:**
- ✅ **يجب** التحقق من صحة المدخلات
- ✅ **يجب** استخدام prepared statements
- ✅ **يجب** تشفير البيانات الحساسة
- ✅ **يجب** عدم عرض معلومات النظام

#### **ج. إدارة الجلسات:**
- ✅ **يجب** تسجيل جميع العمليات
- ✅ **يجب** تسجيل خروج المستخدمين
- ✅ **يجب** حماية من session hijacking
- ✅ **يجب** تنظيف الجلسات القديمة

### **4. قواعد الأداء:**

#### **أ. قاعدة البيانات:**
- ✅ **يجب** استخدام فهارس مناسبة
- ✅ **يجب** تجنب N+1 queries
- ✅ **يجب** استخدام eager loading
- ✅ **يجب** تحسين الاستعلامات المعقدة

#### **ب. التخزين المؤقت:**
- ✅ **يجب** استخدام cache للبيانات الثابتة
- ✅ **يجب** مسح الكاش عند التحديث
- ✅ **يجب** استخدام cache tags
- ✅ **يجب** مراقبة استخدام الكاش

#### **ج. تحميل الصفحات:**
- ✅ **يجب** تقليل حجم الصور
- ✅ **يجب** استخدام lazy loading
- ✅ **يجب** ضغط الملفات
- ✅ **يجب** استخدام CDN عند الحاجة

### **5. قواعد الواجهات:**

#### **أ. التصميم:**
- ✅ **يجب** استخدام Tailwind CSS
- ✅ **يجب** دعم اللغة العربية (RTL)
- ✅ **يجب** تصميم متجاوب
- ✅ **يجب** استخدام الألوان المحددة

#### **ب. التفاعل:**
- ✅ **يجب** استخدام Alpine.js
- ✅ **يجب** إضافة رسائل تأكيد
- ✅ **يجب** عرض مؤشرات التحميل
- ✅ **يجب** معالجة الأخطاء في الواجهة

#### **ج. إمكانية الوصول:**
- ✅ **يجب** إضافة alt text للصور
- ✅ **يجب** استخدام semantic HTML
- ✅ **يجب** دعم لوحة المفاتيح
- ✅ **يجب** اختبار مع قارئات الشاشة

### **6. قواعد الاختبار:**

#### **أ. اختبار الوحدات:**
- ✅ **يجب** كتابة tests للدوال
- ✅ **يجب** اختبار جميع الحالات
- ✅ **يجب** استخدام factories للبيانات
- ✅ **يجب** اختبار الأخطاء

#### **ب. اختبار التكامل:**
- ✅ **يجب** اختبار المسارات
- ✅ **يجب** اختبار النماذج
- ✅ **يجب** اختبار قاعدة البيانات
- ✅ **يجب** اختبار الواجهات

#### **ج. اختبار الأداء:**
- ✅ **يجب** اختبار وقت الاستجابة
- ✅ **يجب** اختبار استخدام الذاكرة
- ✅ **يجب** اختبار قاعدة البيانات
- ✅ **يجب** اختبار التحميل

### **7. قواعد النشر:**

#### **أ. التحضير للنشر:**
- ✅ **يجب** اختبار النظام بالكامل
- ✅ **يجب** نسخ احتياطي للبيانات
- ✅ **يجب** تحديث ملفات التوثيق
- ✅ **يجب** إعداد البيئة الإنتاجية

#### **ب. النشر:**
- ✅ **يجب** استخدام deployment tools
- ✅ **يجب** نشر تدريجي
- ✅ **يجب** مراقبة النظام
- ✅ **يجب** إعداد التنبيهات

#### **ج. ما بعد النشر:**
- ✅ **يجب** مراقبة الأداء
- ✅ **يجب** مراقبة الأخطاء
- ✅ **يجب** نسخ احتياطي منتظم
- ✅ **يجب** تحديث النظام

## 🔧 **أدوات التطوير المطلوبة**

### **1. أدوات PHP:**
- ✅ **Composer:** لإدارة التبعيات
- ✅ **PHPUnit:** للاختبار
- ✅ **PHPStan:** لفحص الكود
- ✅ **PHP CS Fixer:** لتنسيق الكود

### **2. أدوات قاعدة البيانات:**
- ✅ **MySQL Workbench:** لإدارة قاعدة البيانات
- ✅ **phpMyAdmin:** لإدارة قاعدة البيانات
- ✅ **Sequel Pro:** لإدارة قاعدة البيانات (Mac)
- ✅ **HeidiSQL:** لإدارة قاعدة البيانات (Windows)

### **3. أدوات الواجهة:**
- ✅ **Node.js & NPM:** لإدارة الأصول
- ✅ **Tailwind CSS:** للتصميم
- ✅ **Alpine.js:** للتفاعل
- ✅ **Vite:** لبناء الأصول

### **4. أدوات المراقبة:**
- ✅ **Laravel Telescope:** لمراقبة النظام
- ✅ **Laravel Debugbar:** لتصحيح الأخطاء
- ✅ **Laravel Log Viewer:** لعرض السجلات
- ✅ **Laravel Horizon:** لمراقبة القوائم

## 📝 **قوالب الكود المطلوبة**

### **1. قالب Controller:**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExampleController extends Controller
{
    /**
     * عرض قائمة العناصر
     */
    public function index()
    {
        try {
            $items = Example::paginate(20);
            return view('examples.index', compact('items'));
        } catch (\Exception $e) {
            Log::error('Error in ExampleController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ في النظام');
        }
    }

    /**
     * حفظ عنصر جديد
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            DB::transaction(function () use ($request) {
                Example::create($request->validated());
            });

            return redirect()->route('examples.index')
                           ->with('success', 'تم إنشاء العنصر بنجاح');
        } catch (\Exception $e) {
            Log::error('Error in ExampleController@store: ' . $e->getMessage());
            return redirect()->back()
                           ->withErrors(['error' => 'حدث خطأ أثناء حفظ العنصر'])
                           ->withInput();
        }
    }
}
```

### **2. قالب Model:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Example extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * العلاقة مع الجداول الأخرى
     */
    public function relatedItems()
    {
        return $this->hasMany(RelatedItem::class);
    }

    /**
     * نطاق العناصر النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق البحث
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }
}
```

### **3. قالب View:**
```blade
@extends('layouts.app')

@section('title', 'العناصر')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- رأس الصفحة -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">العناصر</h1>
        <p class="text-gray-600">إدارة جميع العناصر في النظام</p>
    </div>

    <!-- رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- شريط الأدوات -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('examples.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة عنصر جديد
                </a>
            </div>
            
            <div class="flex items-center space-x-4 space-x-reverse">
                <input type="text" id="search" placeholder="البحث في العناصر..." 
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- جدول العناصر -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الاسم
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الوصف
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الحالة
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            الإجراءات
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($examples as $example)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $example->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ Str::limit($example->description, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $example->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $example->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('examples.edit', $example) }}" 
                                   class="text-blue-600 hover:text-blue-900 ml-4">
                                    تعديل
                                </a>
                                <form action="{{ route('examples.destroy', $example) }}" 
                                      method="POST" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا العنصر؟')">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                لا توجد عناصر لعرضها
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- ترقيم الصفحات -->
        @if($examples->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $examples->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// البحث في العناصر
document.getElementById('search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
```

## 🚫 **المحظورات المطلقة**

### **1. أمان النظام:**
- ❌ **ممنوع** تخزين كلمات المرور كنص عادي
- ❌ **ممنوع** عرض معلومات النظام للمستخدمين
- ❌ **ممنوع** تجاهل التحقق من الصلاحيات
- ❌ **ممنوع** استخدام SQL queries مباشرة

### **2. الأداء:**
- ❌ **ممنوع** استخدام N+1 queries
- ❌ **ممنوع** تحميل بيانات غير ضرورية
- ❌ **ممنوع** تجاهل التخزين المؤقت
- ❌ **ممنوع** استخدام loops بدلاً من collections

### **3. الكود:**
- ❌ **ممنوع** نسخ الكود
- ❌ **ممنوع** تجاهل معايير الترميز
- ❌ **ممنوع** كتابة كود معقد بدون تعليقات
- ❌ **ممنوع** تجاهل إدارة الأخطاء

### **4. قاعدة البيانات:**
- ❌ **ممنوع** حذف البيانات بدون نسخ احتياطي
- ❌ **ممنوع** تجاهل العلاقات بين الجداول
- ❌ **ممنوع** استخدام أسماء غير واضحة
- ❌ **ممنوع** تجاهل الفهارس

## ✅ **قائمة التحقق قبل النشر**

### **1. اختبار النظام:**
- [ ] اختبار جميع الوظائف
- [ ] اختبار جميع المسارات
- [ ] اختبار جميع النماذج
- [ ] اختبار جميع الواجهات

### **2. اختبار قاعدة البيانات:**
- [ ] اختبار جميع الهجرات
- [ ] اختبار جميع العلاقات
- [ ] اختبار جميع الاستعلامات
- [ ] اختبار جميع البيانات

### **3. اختبار الأمان:**
- [ ] اختبار نظام الصلاحيات
- [ ] اختبار حماية CSRF
- [ ] اختبار التحقق من المدخلات
- [ ] اختبار تسجيل النشاطات

### **4. اختبار الأداء:**
- [ ] اختبار وقت الاستجابة
- [ ] اختبار استخدام الذاكرة
- [ ] اختبار قاعدة البيانات
- [ ] اختبار التحميل

### **5. التوثيق:**
- [ ] تحديث README.md
- [ ] تحديث SYSTEM_DOCUMENTATION.md
- [ ] تحديث RULES.md
- [ ] تحديث CHANGELOG.md

## 📞 **الدعم والمساعدة**

### **معلومات الاتصال:**
- **المطور:** SystemHF Team
- **البريد الإلكتروني:** support@systemhf.com
- **الموقع:** https://systemhf.com

### **المستندات الإضافية:**
- [دليل المستخدم](USER_GUIDE.md)
- [دليل المطور](DEVELOPER_GUIDE.md)
- [وثائق النظام](SYSTEM_DOCUMENTATION.md)
- [سجل التغييرات](CHANGELOG.md)

---

**تم التطوير بواسطة فريق SystemHF** 🚀✨

**آخر تحديث:** 24 أغسطس 2025
**الإصدار:** 2.1.0
