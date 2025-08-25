<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة معرض الأثاث</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }

        .title {
            font-size: 1.5rem;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .subtitle {
            color: #718096;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: right;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #feb2b2;
            text-align: right;
        }

        .demo-info {
            background: #e6fffa;
            color: #234e52;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1.5rem;
            border: 1px solid #9ae6b4;
            text-align: right;
        }

        .demo-info h4 {
            margin-bottom: 0.5rem;
            color: #234e52;
        }

        .demo-credentials {
            background: #f0fff4;
            padding: 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            margin-top: 0.5rem;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🏢</div>
        <h1 class="title">نظام إدارة معرض الأثاث</h1>
        <p class="subtitle">تسجيل الدخول</p>

        @if ($errors->any())
            <div class="error-message">
                <ul style="margin: 0; padding-right: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       required class="form-control" placeholder="أدخل بريدك الإلكتروني">
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" 
                       required class="form-control" placeholder="أدخل كلمة المرور">
            </div>

            <button type="submit" class="btn-login">🚪 تسجيل الدخول</button>
        </form>

        <div class="demo-info">
            <h4>💡 بيانات تجريبية:</h4>
            <div class="demo-credentials">
                البريد: test@example.com<br>
                كلمة المرور: password
            </div>
        </div>
    </div>
</body>
</html>
