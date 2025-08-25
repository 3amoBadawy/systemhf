@extends('layouts.app')

@section('title', 'تسجيل دفعة جديدة - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">تسجيل دفعة جديدة</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة دفعة جديدة للنظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للمدفوعات
        </a>
    </div>
</div>
@endsection

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إضافة دفعة جديدة</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة دفعة جديدة للنظام</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للمدفوعات
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ إضافة دفعة جديدة</h2>
        <p>أدخل تفاصيل الدفعة</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-right: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">👤 اختيار العميل</h3>
            </div>
            
            <div class="form-group">
                <label for="customer_id">العميل *</label>
                <select id="customer_id" name="customer_id" required class="form-control" onchange="loadCustomerInvoices(this.value)">
                    <option value="">اختر العميل</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customer->name === 'خالد' ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- معلومات العميل والفواتير -->
        <div class="card" id="customer-info" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">💰 ملخص العميل المالي</h3>
            </div>
            
            <div id="customer-summary">
                <!-- ستظهر هنا ملخص العميل المالي -->
            </div>
        </div>

        <!-- قائمة فواتير العميل -->
        <div class="card" id="customer-invoices" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">📋 فواتير العميل</h3>
            </div>
            
            <div id="invoices-list">
                <!-- ستظهر هنا فواتير العميل -->
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">💳 تفاصيل الدفعة</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="amount">مبلغ الدفع *</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" 
                           value="{{ old('amount') }}" required placeholder="0.00" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="payment_date">تاريخ الدفع *</label>
                    <input type="date" id="payment_date" name="payment_date" 
                           value="{{ old('payment_date', date('Y-m-d')) }}" required class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="payment_method">طريقة الدفع *</label>
                    <select id="payment_method" name="payment_method" required class="form-control">
                        <option value="">اختر طريقة الدفع</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->code }}" {{ old('payment_method') == $method->code ? 'selected' : '' }}>
                                {{ $method->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="payment_status">حالة الدفع *</label>
                    <select id="payment_status" name="payment_status" required class="form-control">
                        <option value="">اختر حالة الدفع</option>
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="reference_number">رقم المرجع</label>
                    <input type="text" id="reference_number" name="reference_number" 
                           value="{{ old('reference_number') }}" placeholder="رقم الشيك، رقم التحويل، إلخ" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="receipt_image">صورة الإيصال</label>
                    <input type="file" id="receipt_image" name="receipt_image" accept="image/*" 
                           onchange="previewImage(this)" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="notes">ملاحظات إضافية</label>
                <textarea id="notes" name="notes" rows="3" 
                          placeholder="ملاحظات إضافية حول الدفعة" class="form-control">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary">💾 حفظ الدفعة</button>
            <a href="{{ route('payments.index') }}" class="btn-secondary">❌ إلغاء</a>
        </div>
    </form>
</div>

<script>
function loadCustomerInvoices(customerId) {
    console.log('=== loadCustomerInvoices called ===');
    console.log('Customer ID:', customerId);
    
    if (!customerId) {
        console.log('No customer ID, hiding sections');
        document.getElementById('customer-info').style.display = 'none';
        document.getElementById('customer-invoices').style.display = 'none';
        return;
    }

    // إظهار أقسام العميل
    console.log('Showing customer sections');
    document.getElementById('customer-info').style.display = 'block';
    document.getElementById('customer-invoices').style.display = 'block';

    // جلب معلومات العميل وفواتيره عبر AJAX
    console.log('Fetching customer invoices for ID:', customerId);
    
    // التحقق من CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found!');
        return;
    }
    console.log('CSRF token found:', csrfToken.getAttribute('content'));
    
    const url = `/customers/${customerId}/invoices`;
    console.log('Fetching from URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        },
        credentials: 'same-origin'
    })
        .then(response => {
            console.log('=== Response received ===');
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            console.log('Response type:', response.type);
            console.log('Response url:', response.url);
            
            if (!response.ok) {
                console.error('Response not ok, throwing error');
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            console.log('Response is ok, parsing JSON...');
            return response.json();
        })
        .then(data => {
            console.log('=== Data parsed successfully ===');
            console.log('Response data:', data);
            console.log('Customer data:', data.customer);
            console.log('Invoices data:', data.invoices);
            
            if (data.customer) {
                // عرض ملخص العميل المالي
                document.getElementById('customer-summary').innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                        <div class="summary-card">
                            <h4>💰 إجمالي الفواتير</h4>
                            <p class="amount total">${parseFloat(data.customer.total_invoiced).toFixed(2)} ريال</p>
                        </div>
                        <div class="summary-card">
                            <h4>💳 إجمالي المدفوع</h4>
                            <p class="amount paid">${parseFloat(data.customer.total_paid).toFixed(2)} ريال</p>
                        </div>
                        <div class="summary-card">
                            <h4>⚠️ المبلغ المستحق</h4>
                            <p class="amount remaining">${parseFloat(data.customer.remaining_balance).toFixed(2)} ريال</p>
                        </div>
                    </div>
                `;

                // عرض فواتير العميل
                if (data.invoices && data.invoices.length > 0) {
                    let invoicesHtml = '<div class="invoices-grid">';
                    data.invoices.forEach(invoice => {
                        const isPaid = invoice.is_fully_paid;
                        const statusClass = isPaid ? 'paid' : 'unpaid';
                        const statusText = isPaid ? '✅ مدفوع بالكامل' : '⚠️ مبلغ مستحق';
                        
                        invoicesHtml += `
                            <div class="invoice-card ${statusClass}">
                                <div class="invoice-header">
                                    <h4>📋 ${invoice.contract_number}</h4>
                                    <span class="status ${statusClass}">${statusText}</span>
                                </div>
                                <div class="invoice-details">
                                    <p><strong>التاريخ:</strong> ${new Date(invoice.sale_date).toLocaleDateString('ar-SA')}</p>
                                    <p><strong>المبلغ:</strong> ${parseFloat(invoice.total).toFixed(2)} ريال</p>
                                    <p><strong>المدفوع:</strong> ${parseFloat(invoice.total_paid || 0).toFixed(2)} ريال</p>
                                    <p><strong>المستحق:</strong> ${parseFloat(invoice.remaining_balance || invoice.total).toFixed(2)} ريال</p>
                                </div>
                            </div>
                        `;
                    });
                    invoicesHtml += '</div>';
                    document.getElementById('invoices-list').innerHTML = invoicesHtml;
                } else {
                    document.getElementById('invoices-list').innerHTML = '<p class="no-invoices">لا توجد فواتير لهذا العميل</p>';
                }
            }
        })
        .catch(error => {
            console.error('Error loading customer invoices:', error);
            
            // عرض رسائل خطأ أكثر وضوحاً
            if (error.message.includes('401') || error.message.includes('Unauthorized')) {
                document.getElementById('customer-summary').innerHTML = '<p class="error">⚠️ يجب تسجيل الدخول أولاً</p>';
                document.getElementById('invoices-list').innerHTML = '<p class="error">⚠️ يجب تسجيل الدخول أولاً</p>';
            } else if (error.message.includes('404')) {
                document.getElementById('customer-summary').innerHTML = '<p class="error">❌ العميل غير موجود</p>';
                document.getElementById('invoices-list').innerHTML = '<p class="error">❌ العميل غير موجود</p>';
            } else {
                document.getElementById('customer-summary').innerHTML = '<p class="error">❌ حدث خطأ أثناء تحميل معلومات العميل</p>';
                document.getElementById('invoices-list').innerHTML = '<p class="error">❌ حدث خطأ أثناء تحميل الفواتير</p>';
            }
        });
}

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (!preview) {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'image-preview';
        previewDiv.style.marginTop = '1rem';
        input.parentNode.appendChild(previewDiv);
    }
    
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            img.style.borderRadius = '8px';
            img.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            preview.appendChild(img);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// تحميل معلومات العميل عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM Content Loaded ===');
    const customerSelect = document.getElementById('customer_id');
    console.log('Customer select element:', customerSelect);
    console.log('Selected value:', customerSelect.value);
    
    if (customerSelect.value) {
        console.log('Auto-loading customer invoices...');
        loadCustomerInvoices(customerSelect.value);
    } else {
        console.log('No customer selected, waiting for user selection...');
    }
});
</script>

<style>
.summary-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.summary-card h4 {
    margin: 0 0 0.5rem 0;
    color: #475569;
    font-size: 0.875rem;
}

.amount {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.amount.total { color: #1e40af; }
.amount.paid { color: #059669; }
.amount.remaining { color: #dc2626; }

.invoices-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.invoice-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    background: #ffffff;
}

.invoice-card.paid {
    border-color: #10b981;
    background: #f0fdf4;
}

.invoice-card.unpaid {
    border-color: #f59e0b;
    background: #fffbeb;
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.invoice-header h4 {
    margin: 0;
    color: #1f2937;
}

.status {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status.paid {
    background: #d1fae5;
    color: #065f46;
}

.status.unpaid {
    background: #fef3c7;
    color: #92400e;
}

.invoice-details p {
    margin: 0.25rem 0;
    color: #4b5563;
}

.no-invoices, .error {
    text-align: center;
    color: #6b7280;
    font-style: italic;
}

.error {
    color: #dc2626;
}

#image-preview {
    text-align: center;
}
</style>
@endsection
