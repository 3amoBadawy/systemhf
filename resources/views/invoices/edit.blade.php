<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الفاتورة {{ $invoice->invoice_number }} - نظام إدارة معرض الأثاث</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .navbar .nav-links {
            display: flex;
            gap: 1rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .form-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .form-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #2d3748;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2d3748;
            font-weight: 500;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .current-image {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            text-align: center;
        }
        .current-image img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
        }
        .error {
            color: #e53e3e;
            background: #fed7d7;
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 1px solid #feb2b2;
        }
        .success {
            color: #22543d;
            background: #c6f6d5;
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: 1px solid #9ae6b4;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>✏️ تعديل الفاتورة {{ $invoice->invoice_number }}</h1>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">🏠 الرئيسية</a>
            <a href="{{ route('invoices.index') }}">📋 الفواتير</a>
            <a href="{{ route('customers.index') }}">👥 العملاء</a>
        </div>
    </nav>

    <div class="container">
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('invoices.update', $invoice) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- معلومات الفاتورة الأساسية -->
            <div class="form-card">
                <h2 class="form-title">📋 تعديل معلومات الفاتورة</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="invoice_number">رقم الفاتورة</label>
                        <input type="text" id="invoice_number" value="{{ $invoice->invoice_number }}" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="customer_id">العميل *</label>
                        <select id="customer_id" name="customer_id" required>
                            <option value="">اختر العميل</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sale_date">تاريخ البيع *</label>
                        <input type="date" id="sale_date" name="sale_date" value="{{ old('sale_date', $invoice->sale_date) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contract_number">رقم العقد في المعرض *</label>
                        <input type="text" id="contract_number" name="contract_number" value="{{ old('contract_number', $invoice->contract_number) }}" required placeholder="أدخل رقم العقد">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="contract_image">صورة العقد الجديدة</label>
                        <input type="file" id="contract_image" name="contract_image" accept="image/*">
                        @if($invoice->contract_image)
                            <div class="current-image">
                                <h4>الصورة الحالية:</h4>
                                <img src="{{ asset('storage/' . $invoice->contract_image) }}" alt="صورة العقد الحالية">
                            </div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="status">حالة الفاتورة</label>
                        <select id="status" name="status" required>
                            <option value="مدفوع" {{ $invoice->status == 'مدفوع' ? 'selected' : '' }}>مدفوع</option>
                            <option value="مؤجل" {{ $invoice->status == 'مؤجل' ? 'selected' : '' }}>مؤجل</option>
                            <option value="مقسم" {{ $invoice->status == 'مقسم' ? 'selected' : '' }}>مقسم</option>
                            <option value="ملغي" {{ $invoice->status == 'ملغي' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="notes">ملاحظات</label>
                    <textarea id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
            </div>

            <!-- ملاحظة حول المنتجات -->
            <div class="form-card">
                <h3>⚠️ ملاحظة مهمة</h3>
                <p>لا يمكن تعديل المنتجات من هذه الصفحة. إذا كنت تريد تعديل المنتجات، يرجى حذف الفاتورة وإنشاؤها من جديد.</p>
                
                <div style="background: #f8fafc; padding: 1rem; border-radius: 10px; margin: 1rem 0;">
                    <h4>المنتجات الحالية:</h4>
                    <ul>
                        @foreach($invoice->items as $item)
                            <li>
                                <strong>{{ $item->product_name }}</strong> - 
                                الكمية: {{ $item->quantity }} - 
                                السعر: {{ number_format($item->unit_price, 2) }} ج.م - 
                                الإجمالي: {{ number_format($item->total_price, 2) }} ج.م
                            </li>
                        @endforeach
                    </ul>
                    <p><strong>المجموع النهائي: {{ number_format($invoice->total, 2) }} ج.م</strong></p>
                </div>
            </div>

            <button type="submit" class="btn-submit">💾 حفظ التعديلات</button>
        </form>
    </div>
</body>
</html>
