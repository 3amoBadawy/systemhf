<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة معرض الأثاث</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            margin: 2rem;
        }
        h1 {
            color: #2d3748;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #718096;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .feature {
            background: #f7fafc;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .feature h3 {
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        .feature p {
            color: #718096;
            margin: 0;
        }
        .status {
            background: #c6f6d5;
            color: #22543d;
            padding: 1rem;
            border-radius: 10px;
            margin: 2rem 0;
            border: 1px solid #9ae6b4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 مرحباً بك في نظام إدارة معرض الأثاث</h1>
        <p class="subtitle">نظام متكامل لإدارة المبيعات والمخزون والموارد البشرية</p>
        
        <div class="status">
            <strong>✅ التطبيق يعمل بنجاح!</strong><br>
            تم النشر على: <strong>https://sys.high-furniture.com</strong>
        </div>

        <div class="features">
            <div class="feature">
                <h3>🛋️ إدارة المنتجات</h3>
                <p>إدارة كاملة للأثاث والمخزون</p>
            </div>
            <div class="feature">
                <h3>💰 إدارة المبيعات</h3>
                <p>تتبع المبيعات والفواتير</p>
            </div>
            <div class="feature">
                <h3>👥 إدارة العملاء</h3>
                <p>قاعدة بيانات العملاء</p>
            </div>
            <div class="feature">
                <h3>👨‍💼 الموارد البشرية</h3>
                <p>إدارة الموظفين والرواتب</p>
            </div>
        </div>

        <div class="status">
            <strong>🔧 التقنيات المستخدمة:</strong><br>
            Laravel 12 • PHP 8.3 • MySQL 8.0 • Nginx • SSL
        </div>
    </div>
</body>
</html>
