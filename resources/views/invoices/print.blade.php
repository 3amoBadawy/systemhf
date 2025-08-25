<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة فاتورة {{ $invoice->invoice_number }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #34495e;
            margin: 20px 0;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-info, .customer-info {
            flex: 1;
        }
        
        .info-section h3 {
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #34495e;
        }
        
        .info-value {
            color: #2c3e50;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .products-table th {
            background: #ecf0f1;
            padding: 12px;
            text-align: right;
            border: 1px solid #bdc3c7;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .products-table td {
            padding: 12px;
            border: 1px solid #bdc3c7;
            text-align: right;
        }
        
        .products-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .totals-section {
            margin-top: 30px;
            text-align: left;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .total-row:last-child {
            border-bottom: 2px solid #2c3e50;
            font-weight: bold;
            font-size: 18px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
        
        .contract-image {
            text-align: center;
            margin: 20px 0;
        }
        
        .contract-image img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #bdc3c7;
        }
        
        .no-image {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            border: 2px dashed #bdc3c7;
            margin: 20px 0;
        }
        
        .print-button {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .print-button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-button">🖨️ طباعة الفاتورة</button>
        <button onclick="window.close()" class="print-button" style="background: #e74c3c;">❌ إغلاق</button>
    </div>

    <!-- رأس الفاتورة -->
    <div class="header">
        <div class="company-name">معرض الأثاث العالي</div>
        <div class="company-info">
            نظام إدارة معرض الأثاث<br>
            https://sys.high-furniture.com
        </div>
    </div>

    <!-- عنوان الفاتورة -->
    <div class="invoice-title">فاتورة مبيعات</div>

    <!-- تفاصيل الفاتورة -->
    <div class="invoice-details">
        <div class="invoice-info">
            <div class="info-section">
                <h3>معلومات الفاتورة</h3>
                <div class="info-row">
                    <span class="info-label">رقم الفاتورة:</span>
                    <span class="info-value">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">تاريخ البيع:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($invoice->sale_date)->format('Y-m-d') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">رقم العقد:</span>
                    <span class="info-value">{{ $invoice->contract_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الحالة:</span>
                    <span class="info-value">{{ $invoice->status }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">المبيعات:</span>
                    <span class="info-value">{{ $invoice->user->name ?? 'غير محدد' }}</span>
                </div>
            </div>
        </div>

        <div class="customer-info">
            <div class="info-section">
                <h3>معلومات العميل</h3>
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
        </div>
    </div>

    <!-- صورة العقد -->
    @if($invoice->contract_image)
        <div class="contract-image">
            <h3>صورة العقد</h3>
            <img src="{{ asset('storage/' . $invoice->contract_image) }}" alt="صورة العقد">
        </div>
    @else
        <div class="no-image">
            <h4>لا توجد صورة للعقد</h4>
        </div>
    @endif

    <!-- المنتجات -->
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
                    <td>{{ number_format($item->discount, 0) }} ج.م</td>
                    <td>
                        <strong>{{ number_format($item->total_price, 2) }} ج.م</strong>
                    </td>
                    <td>{{ $item->notes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- المجاميع -->
    <div class="totals-section">
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

    <!-- ملاحظات -->
    @if($invoice->notes)
        <div style="margin-top: 30px;">
            <h3>ملاحظات:</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif

    <!-- تذييل الفاتورة -->
    <div class="footer">
        <p>تم إنشاء هذه الفاتورة بواسطة نظام إدارة معرض الأثاث</p>
        <p>تاريخ الطباعة: {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>
