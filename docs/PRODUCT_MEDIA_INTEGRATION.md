# 🔗 تكامل نظام الوسائط مع المنتجات

## 📋 نظرة عامة

تم دمج نظام الوسائط المتقدم مع صفحة إنشاء وتعديل المنتجات، مما يوفر تجربة مستخدم محسنة لإدارة وسائط المنتجات.

## ✨ المميزات الجديدة

### **🎨 نظام وسائط موحد**
- رفع وسائط متعددة (صور، فيديوهات، مستندات)
- معاينة فورية للوسائط
- سحب وإفلات للملفات
- ترتيب الوسائط بالسحب والإفلات

### **📱 واجهة مستخدم محسنة**
- منطقة رفع تفاعلية مع سحب وإفلات
- معاينة شبكية للوسائط
- أيقونات مميزة لكل نوع وسائط
- معلومات واضحة عن الملفات المدعومة

### **⚡ معالجة ذكية**
- تحسين تلقائي للصور
- إنشاء thumbnails للفيديوهات
- دعم صيغ متعددة
- ربط تلقائي بالمنتج

## 🔧 التحديثات التقنية

### **1. ProductController**
```php
// إضافة MediaService
protected $mediaService;

public function __construct(MediaService $mediaService)
{
    $this->mediaService = $mediaService;
}

// تحديث method store
public function store(Request $request)
{
    // رفع الوسائط باستخدام النظام الجديد
    $mediaIds = [];
    if ($request->hasFile('media_files')) {
        foreach ($request->file('media_files') as $file) {
            $mediaData = $this->processMediaFile($file, $request);
            $mediaIds[] = $mediaData->id;
        }
    }
    
    // إنشاء المنتج
    $product = Product::create([...]);
    
    // ربط الوسائط بالمنتج
    if (!empty($mediaIds)) {
        Media::whereIn('id', $mediaIds)->update([
            'mediaable_type' => Product::class,
            'mediaable_id' => $product->id
        ]);
    }
}
```

### **2. نموذج الوسائط**
```php
// في Product Model
public function media()
{
    return $this->morphMany(Media::class, 'mediaable')->ordered();
}

public function images()
{
    return $this->morphMany(Media::class, 'mediaable')->images()->ordered();
}

public function videos()
{
    return $this->morphMany(Media::class, 'mediaable')->videos()->ordered();
}

public function mainImage()
{
    return $this->morphOne(Media::class, 'mediaable')->where('is_featured', true)->images();
}
```

## 🚀 كيفية الاستخدام

### **إنشاء منتج جديد**
1. اذهب إلى `/products/create`
2. املأ معلومات المنتج الأساسية
3. في قسم الوسائط:
   - اسحب وأفلت الملفات أو اضغط "اختيار ملفات"
   - اختر صور، فيديوهات، أو مستندات
   - رتب الوسائط بالسحب والإفلات
   - الصورة الأولى ستكون الصورة الرئيسية

### **تعديل منتج موجود**
1. اذهب إلى `/products/{id}/edit`
2. شاهد الوسائط الحالية
3. أضف وسائط جديدة (ستستبدل القديمة)
4. احفظ التغييرات

## 📁 الملفات المدعومة

### **🖼️ الصور**
- **JPG/JPEG** - صيغة شائعة للصور
- **PNG** - دعم الشفافية
- **GIF** - الصور المتحركة
- **WebP** - صيغة حديثة محسنة

### **🎥 الفيديوهات**
- **MP4** - صيغة شائعة ومتوافقة
- **AVI** - صيغة قديمة
- **MOV** - صيغة Apple
- **WebM** - صيغة ويب حديثة

### **📄 المستندات**
- **PDF** - مستندات قابلة للقراءة
- **DOC/DOCX** - مستندات Word
- **TXT** - ملفات نصية

## 🎯 المميزات المتقدمة

### **معالجة الصور**
- تحسين تلقائي للجودة
- إنشاء نسخ متعددة (Thumbnail, Medium, Large)
- تحويل لصيغة WebP
- ضغط ذكي

### **معالجة الفيديوهات**
- إنشاء thumbnails تلقائياً
- استخراج مدة الفيديو
- تحسين الجودة
- دعم البث

### **إدارة الملفات**
- تنظيم تلقائي حسب النوع
- حفظ البيانات الوصفية
- دعم الترتيب
- حذف آمن

## 🔍 استكشاف الأخطاء

### **مشاكل شائعة**

#### **1. خطأ في رفع الملفات**
```bash
# تأكد من صلاحيات المجلدات
chmod -R 755 storage/app/public
chmod -R 755 storage/app/public/products

# تأكد من وجود المجلدات
mkdir -p storage/app/public/products/thumbnails
mkdir -p storage/app/public/products/medium
mkdir -p storage/app/public/products/large
mkdir -p storage/app/public/products/webp
```

#### **2. مشاكل في عرض الصور**
```bash
# إنشاء رابط التخزين
php artisan storage:link

# مسح الكاش
php artisan view:clear
php artisan cache:clear
```

#### **3. مشاكل في معالجة الصور**
```bash
# تثبيت Intervention Image
composer require intervention/image

# تثبيت FFMpeg للفيديوهات
composer require php-ffmpeg/php-ffmpeg
```

## 📊 قاعدة البيانات

### **جدول `media`**
```sql
CREATE TABLE media (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size BIGINT UNSIGNED NOT NULL,
    path VARCHAR(500) NOT NULL,
    disk VARCHAR(50) DEFAULT 'public',
    alt_text TEXT NULL,
    caption TEXT NULL,
    description TEXT NULL,
    metadata JSON NULL,
    is_public BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    media_type ENUM('image', 'video', 'document') NOT NULL,
    dimensions JSON NULL,
    duration INT NULL,
    thumbnail_path VARCHAR(500) NULL,
    optimized_versions JSON NULL,
    mediaable_type VARCHAR(255) NULL,
    mediaable_id BIGINT UNSIGNED NULL,
    `order` INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_mediaable (mediaable_type, mediaable_id),
    INDEX idx_media_type (media_type),
    INDEX idx_order (`order`)
);
```

## 🔌 API Endpoints

### **رفع وسائط منتج**
```http
POST /api/media/upload
Content-Type: multipart/form-data

media_files[]: [file1, file2, ...]
alt_text: "نص بديل"
caption: "تعليق"
```

### **عرض وسائط منتج**
```http
GET /api/media?mediaable_type=Product&mediaable_id={id}
```

### **تحديث وسائط منتج**
```http
PUT /api/media/{id}
Content-Type: application/json

{
    "alt_text": "نص بديل جديد",
    "caption": "تعليق جديد",
    "order": 1
}
```

## 🎨 تخصيص الواجهة

### **تغيير ألوان المنطقة**
```css
#mediaDropZone {
    border-color: #3b82f6; /* أزرق */
    background-color: #eff6ff;
}

#mediaDropZone:hover {
    border-color: #1d4ed8;
    background-color: #dbeafe;
}
```

### **تغيير حجم المعاينة**
```css
#media_preview_container {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
}
```

## 🚀 التطوير المستقبلي

### **ميزات مقترحة**
- **معرض 360 درجة** للصور
- **تحليل الوسائط** بالذكاء الاصطناعي
- **تخزين سحابي** متقدم
- **مزامنة الوسائط** بين المنتجات
- **إدارة الحقوق** والتراخيص

### **تحسينات الأداء**
- **Lazy Loading** متقدم
- **ضغط ذكي** للصور
- **CDN** متكامل
- **Cache** متقدم

## 📞 الدعم

للمساعدة أو الإبلاغ عن مشاكل:
- راجع هذا الدليل
- تحقق من Logs
- تواصل مع فريق التطوير

---

**تم التطوير بواسطة فريق High Furniture System** 🚀
**آخر تحديث: أغسطس 2025**

