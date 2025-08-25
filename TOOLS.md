# 🛠️ **أدوات التطوير - SystemHF**

## 📋 **نظرة عامة**

هذا الدليل يوضح جميع أدوات التطوير المثبتة في المشروع وكيفية استخدامها لضمان جودة الكود والهندسة المعمارية.

---

## 🔍 **أدوات التحليل الثابت**

### **1. PHPStan**

**الوصف:** أداة تحليل ثابت متقدمة للكود PHP تكتشف الأخطاء المحتملة قبل تشغيل الكود.

**الإصدار:** 2.1.22

**الاستخدام:**
```bash
# تحليل شامل
./vendor/bin/phpstan analyse

# تحليل مع إعدادات مخصصة
./vendor/bin/phpstan analyse --configuration=phpstan.neon

# تحليل مجلد محدد
./vendor/bin/phpstan analyse app/Services

# تحليل مع مستوى خطأ محدد
./vendor/bin/phpstan analyse --level=5
```

**ملف التكوين:** `phpstan.neon`
```yaml
parameters:
    level: 8                    # مستوى التحليل (0-9)
    paths:                      # المجلدات المراد تحليلها
        - app
        - config
        - database
        - routes
    excludePaths:               # المجلدات المستثناة
        - vendor
        - storage
        - bootstrap/cache
        - tests
```

**الميزات:**
- ✅ اكتشاف الأخطاء المحتملة
- ✅ فحص أنواع البيانات
- ✅ فحص الدوال غير المعرفة
- ✅ فحص المتغيرات غير المستخدمة
- ✅ تكامل مع Laravel

---

### **2. Larastan**

**الوصف:** تكامل PHPStan مع Laravel يوفر دعم أفضل لإطار العمل.

**الإصدار:** 3.6.0

**الاستخدام:**
```bash
# تحليل مع Larastan
./vendor/bin/phpstan analyse --configuration=phpstan.neon
```

**الميزات:**
- ✅ دعم Laravel Facades
- ✅ دعم Eloquent Models
- ✅ دعم Laravel Collections
- ✅ دعم Service Container

---

### **3. Psalm**

**الوصف:** أداة تحليل ثابت متقدمة مع دعم TypeScript-like annotations.

**الإصدار:** 6.13.1

**الاستخدام:**
```bash
# تحليل شامل
./vendor/bin/psalm

# تحليل مع تقرير مفصل
./vendor/bin/psalm --show-info=true

# تحليل مع مستوى خطأ محدد
./vendor/bin/psalm --error-level=4

# تحليل مجلد محدد
./vendor/bin/psalm app/Models
```

**ملف التكوين:** `psalm.xml`
```xml
<psalm
    errorLevel="4"
    resolveFromConfigFile="true">
    
    <projectFiles>
        <directory name="app"/>
        <directory name="config"/>
        <directory name="database"/>
        <directory name="routes"/>
    </projectFiles>
    
    <ignoreFiles>
        <directory name="vendor"/>
        <directory name="storage"/>
        <directory name="bootstrap/cache"/>
        <directory name="tests"/>
    </ignoreFiles>
</psalm>
```

**الميزات:**
- ✅ دعم TypeScript-like annotations
- ✅ اكتشاف الأخطاء المتقدمة
- ✅ دعم Generics
- ✅ دعم Union Types
- ✅ دعم Intersection Types

---

## 🧹 **أدوات جودة الكود**

### **4. Laravel Pint**

**الوصف:** أداة تنسيق الكود الرسمية لـ Laravel.

**الإصدار:** 1.13

**الاستخدام:**
```bash
# تنسيق الكود
./vendor/bin/pint

# فحص التنسيق فقط
./vendor/bin/pint --test

# تنسيق ملف محدد
./vendor/bin/pint app/Http/Controllers/UserController.php

# تنسيق مع قواعد مخصصة
./vendor/bin/pint --config=pint.json
```

**الميزات:**
- ✅ تنسيق تلقائي للكود
- ✅ دعم PSR-12
- ✅ تكامل مع Laravel
- ✅ قواعد قابلة للتخصيص

---

### **5. PHPMD**

**الوصف:** أداة كشف رائحة الكود (Code Smells) وتحديد المشاكل المحتملة.

**الإصدار:** 2.15.0

**الاستخدام:**
```bash
# فحص شامل
./vendor/bin/phpmd app text phpmd.xml

# فحص مجلد محدد
./vendor/bin/phpmd app/Models text phpmd.xml

# فحص ملف محدد
./vendor/bin/phpmd app/Http/Controllers/UserController.php text phpmd.xml

# فحص مع تقرير HTML
./vendor/bin/phpmd app html phpmd.xml --reportfile report.html
```

**ملف التكوين:** `phpmd.xml`
```xml
<ruleset name="PHPMD rule set">
    <rule ref="rulesets/cleancode.xml">
        <exclude name="StaticAccess"/>
    </rule>
    
    <rule ref="rulesets/codesize.xml">
        <exclude name="ExcessiveClassLength"/>
        <exclude name="ExcessiveMethodLength"/>
    </rule>
    
    <rule ref="rulesets/controversial.xml">
        <exclude name="CamelCaseMethodName"/>
    </rule>
</ruleset>
```

**الميزات:**
- ✅ كشف رائحة الكود
- ✅ فحص تعقيد الكود
- ✅ فحص طول الدوال والفئات
- ✅ تقارير مفصلة
- ✅ قواعد قابلة للتخصيص

---

### **6. Deptrac**

**الوصف:** أداة فحص التبعيات والهندسة المعمارية.

**الإصدار:** 2.0.4

**الاستخدام:**
```bash
# فحص التبعيات
./vendor/bin/deptrac analyse deptrac.yaml

# فحص مع تقرير مفصل
./vendor/bin/deptrac analyse deptrac.yaml --report-html=report.html

# فحص مع تقرير Graphviz
./vendor/bin/deptrac analyse deptrac.yaml --report-dot=report.dot
```

**ملف التكوين:** `deptrac.yaml`
```yaml
deptrac:
  paths:
    - ./app
  
  layers:
    - name: Domain
      collectors:
        - type: className
          value: App\\Models\\.*
    
    - name: Application
      collectors:
        - type: className
          value: App\\Services\\.*
    
    - name: Infrastructure
      collectors:
        - type: className
          value: App\\Repositories\\.*
    
    - name: Http
      collectors:
        - type: className
          value: App\\Http\\.*
  
  ruleset:
    - Domain:
        - Application
        - Infrastructure
        - Http
    - Application:
        - Infrastructure
        - Http
    - Infrastructure:
        - Http
    - Http: []
```

**الميزات:**
- ✅ فحص التبعيات
- ✅ إنفاذ الهندسة المعمارية
- ✅ تقارير مرئية
- ✅ قواعد قابلة للتخصيص
- ✅ دعم Graphviz

---

## 🚀 **أوامر Composer السريعة**

### **أوامر الفحص:**

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

### **أوامر التنسيق:**

```bash
# تنسيق الكود
composer pint

# فحص التنسيق
composer pint --test
```

---

## 🔧 **تكامل CI/CD**

### **1. Pre-commit Hook**

**الوصف:** فحص تلقائي للجودة قبل كل commit.

**الموقع:** `.git/hooks/pre-commit`

**الميزات:**
- ✅ فحص تنسيق الكود
- ✅ فحص التحليل الثابت
- ✅ تشغيل الاختبارات
- ✅ منع commit إذا فشلت الفحوصات

**الاستخدام:**
```bash
# جعل الملف قابل للتنفيذ
chmod +x .git/hooks/pre-commit

# الملف يعمل تلقائياً عند كل commit
git commit -m "Update user management"
```

### **2. GitHub Actions**

**الوصف:** فحص تلقائي للجودة عند كل push أو pull request.

**الموقع:** `.github/workflows/quality.yml`

**الميزات:**
- ✅ فحص تلقائي
- ✅ دعم PHP 8.3
- ✅ فحص شامل للجودة
- ✅ تقارير مفصلة
- ✅ تكامل مع Codecov

---

## 📊 **تقارير الجودة**

### **1. تقارير PHPStan:**

```bash
# تقرير مفصل
./vendor/bin/phpstan analyse --generate-baseline

# تقرير مع مستوى خطأ محدد
./vendor/bin/phpstan analyse --level=5 --generate-baseline
```

### **2. تقارير Psalm:**

```bash
# تقرير مفصل
./vendor/bin/psalm --show-info=true

# تقرير HTML
./vendor/bin/psalm --output-format=html --output=psalm-report.html
```

### **3. تقارير PHPMD:**

```bash
# تقرير HTML
./vendor/bin/phpmd app html phpmd.xml --reportfile phpmd-report.html

# تقرير XML
./vendor/bin/phpmd app xml phpmd.xml --reportfile phpmd-report.xml
```

### **4. تقارير Deptrac:**

```bash
# تقرير HTML
./vendor/bin/deptrac analyse deptrac.yaml --report-html=deptrac-report.html

# تقرير Graphviz
./vendor/bin/deptrac analyse deptrac.yaml --report-dot=deptrac-report.dot
```

---

## 🎯 **أفضل الممارسات**

### **1. استخدام يومي:**

```bash
# قبل كل commit
composer quality

# عند إضافة ميزة جديدة
composer analyse
composer psalm

# عند إصلاح خطأ
composer test
```

### **2. إعدادات IDE:**

**VS Code:**
- PHPStan Extension
- Psalm Extension
- Laravel Pint Extension

**PHPStorm:**
- PHPStan Integration
- Psalm Integration
- Laravel Pint Integration

### **3. مراقبة الجودة:**

```bash
# فحص يومي
composer quality

# فحص أسبوعي شامل
composer analyse --level=9
composer psalm --error-level=1
```

---

## 🆘 **استكشاف الأخطاء**

### **1. أخطاء PHPStan:**

```bash
# خطأ في التكوين
./vendor/bin/phpstan analyse --configuration=phpstan.neon

# خطأ في المستوى
./vendor/bin/phpstan analyse --level=5
```

### **2. أخطاء Psalm:**

```bash
# خطأ في التكوين
./vendor/bin/psalm --config=psalm.xml

# خطأ في المستوى
./vendor/bin/psalm --error-level=4
```

### **3. أخطاء PHPMD:**

```bash
# خطأ في القواعد
./vendor/bin/phpmd app text phpmd.xml

# خطأ في التقرير
./vendor/bin/phpmd app text phpmd.xml --reportfile report.txt
```

### **4. أخطاء Deptrac:**

```bash
# خطأ في التكوين
./vendor/bin/deptrac analyse deptrac.yaml

# خطأ في التقرير
./vendor/bin/deptrac analyse deptrac.yaml --report-html=report.html
```

---

## 📚 **الموارد المفيدة**

### **1. وثائق الأدوات:**
- [PHPStan Documentation](https://phpstan.org/)
- [Psalm Documentation](https://psalm.dev/)
- [PHPMD Documentation](https://phpmd.org/)
- [Deptrac Documentation](https://deptrac.readthedocs.io/)

### **2. مقالات مفيدة:**
- [Static Analysis in PHP](https://phpstan.org/blog)
- [Code Quality Tools](https://phpmd.org/documentation/)
- [Architecture Enforcement](https://deptrac.readthedocs.io/)

### **3. مجتمع المطورين:**
- [PHPStan GitHub](https://github.com/phpstan/phpstan)
- [Psalm GitHub](https://github.com/vimeo/psalm)
- [PHPMD GitHub](https://github.com/phpmd/phpmd)
- [Deptrac GitHub](https://github.com/qossmic/deptrac)

---

**آخر تحديث:** 25 أغسطس 2025  
**الإصدار:** 2.1.0  
**الحالة:** مستقر ومُختبر ✅



