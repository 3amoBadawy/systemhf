<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الفاتورة {{ $invoice->invoice_number }} - نظام إدارة معرض الأثاث</title>
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
        .invoice-header {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        .info-group h3 {
            margin: 0 0 1rem 0;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
        }
        .info-value {
            color: #2d3748;
        }
        .status-badge {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }
        .status-paid {
            background: #c6f6d5;
            color: #22543d;
        }
        .status-pending {
            background: #fef5e7;
            color: #744210;
        }
        .status-partial {
            background: #e6fffa;
            color: #234e52;
        }
        .status-cancelled {
            background: #fed7d7;
            color: #742a2a;
        }
        .contract-image {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .contract-image h3 {
            margin: 0 0 1rem 0;
            color: #2d3748;
        }
        .image-container {
            text-align: center;
        }
        .contract-image img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .products-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .products-section h3 {
            margin: 0 0 1.5rem 0;
            color: #2d3748;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }
        .products-table th {
            background: #f8fafc;
            padding: 1rem;
            text-align: right;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
        }
        .products-table td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
        }
        .products-table tr:hover {
            background: #f8fafc;
        }
        .totals-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .totals-section h3 {
            margin: 0 0 1.5rem 0;
            color: #2d3748;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2rem;
            color: #2d3748;
        }
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-edit {
            background: #ed8936;
            color: white;
        }
        .btn-print {
            background: #38a169;
            color: white;
        }
        .btn-back {
            background: #718096;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .no-image {
            text-align: center;
            padding: 3rem;
            color: #718096;
            background: #f8fafc;
            border-radius: 10px;
            border: 2px dashed #e2e8f0;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>📋 عرض الفاتورة {{ $invoice->invoice_number }}</h1>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">🏠 الرئيسية</a>
            <a href="{{ route('invoices.index') }}">📋 الفواتير</a>
            <a href="{{ route('customers.index') }}">👥 العملاء</a>
        </div>
    </nav>

    <div class="container">
        <!-- رأس الفاتورة -->
        <div class="invoice-header">
            <div>
                <h2>💰 فاتورة رقم: {{ $invoice->invoice_number }}</h2>
                <p>تاريخ الإنشاء: {{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d H:i') }}</p>
            </div>
            @php
                $statusClass = match($invoice->status) {
                    'مدفوع' => 'status-paid',
                    'مؤجل' => 'status-pending',
                    'مقسم' => 'status-partial',
                    'ملغي' => 'status-cancelled',
                    default => 'status-pending'
                };
            @endphp
            <span class="status-badge {{ $statusClass }}">
                {{ $invoice->status }}
            </span>
        </div>

        <!-- معلومات الفاتورة -->
        <div class="invoice-info">
            <div class="info-group">
                <h3>👤 معلومات العميل</h3>
                <div class="info-row">
                    <span class="info-label">الاسم:</span>
                    <span class="info-value">{{ $invoice->customer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">رقم الهاتف:</span>
                    <span class="info-value">{{ $invoice->customer->phone }}</span>
                </div>
                @if($invoice->customer->phone_alt)
                    <div class="info-row">
                        <span class="info-label">هاتف بديل:</span>
                        <span class="info-value">{{ $invoice->customer->phone_alt }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">البلد:</span>
                    <span class="info-value">{{ $invoice->customer->country }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المحافظة:</span>
                    <span class="info-value">{{ $invoice->customer->governorate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العنوان:</span>
                    <span class="info-value">{{ $invoice->customer->address }}</span>
                </div>
            </div>

            <div class="info-group">
                <h3>📋 تفاصيل الفاتورة</h3>
                <div class="info-row">
                    <span class="info-label">رقم العقد:</span>
                    <span class="info-value">{{ $invoice->contract_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">تاريخ البيع:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($invoice->sale_date)->format('Y-m-d') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المبيعات:</span>
                    <span class="info-value">{{ $invoice->user->name ?? 'غير محدد' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المجموع:</span>
                    <span class="info-value">{{ number_format($invoice->total, 2) }} ج.م</span>
                </div>
                @if($invoice->notes)
                    <div class="info-row">
                        <span class="info-label">ملاحظات:</span>
                        <span class="info-value">{{ $invoice->notes }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- صورة العقد -->
        <div class="contract-image">
            <h3>📷 صورة العقد</h3>
            @if($invoice->contract_image)
                <div class="image-container">
                    <img src="{{ asset('storage/' . $invoice->contract_image) }}" alt="صورة العقد">
                </div>
            @else
                <div class="no-image">
                    <h4>📭 لا توجد صورة للعقد</h4>
                    <p>لم يتم رفع صورة للعقد</p>
                </div>
            @endif
        </div>

        <!-- المنتجات -->
        <div class="products-section">
            <h3>🛋️ المنتجات المباعة</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الكمية</th>
                        <th>سعر الوحدة</th>
                        <th>الخصم</th>
                        <th>السعر الإجمالي</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->product)
                                    <br>
                                    <small>{{ $item->product->category }}</small>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }} ج.م</td>
                            <td>{{ number_format($item->discount, 2) }} ج.م</td>
                            <td>
                                <strong>{{ number_format($item->total_price, 2) }} ج.م</strong>
                            </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- المجاميع -->
        <div class="totals-section">
            <h3>�� المجاميع</h3>
            <div class="total-row">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($invoice->subtotal, 2) }} ج.م</span>
            </div>
            <div class="total-row">
                <span>الخصم:</span>
                <span>{{ number_format($invoice->discount, 2) }} ج.م</span>
            </div>
            <div class="total-row">
                <span>المجموع النهائي:</span>
                <span>{{ number_format($invoice->total, 2) }} ج.م</span>
            </div>
        </div>

        <!-- أزرار الإجراءات -->
        <div class="action-buttons">
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-edit">✏️ تعديل الفاتورة</a>
            <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-print" target="_blank">🖨️ طباعة الفاتورة</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-back">⬅️ العودة للفواتير</a>
        </div>
    </div>
</body>
</html>
