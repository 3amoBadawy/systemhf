@extends('layouts.app')

@section('title', 'إنشاء فاتورة جديدة')
@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">إنشاء فاتورة جديدة</h1>
        <p class="mt-1 text-sm text-gray-500">إضافة فاتورة جديدة مع نظام اختيار المنتجات المتطور</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <a href="{{ route('invoices.index') }}" class="btn btn-outline">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للفواتير
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Custom CSS -->
<link href="{{ asset('css/invoice-create.css') }}" rel="stylesheet" />

<div class="invoice-create-container">
    <!-- رأس الصفحة -->
    <div class="invoice-header">
        <div class="invoice-header-content">
            <h2 class="invoice-header-title">💰 إنشاء فاتورة جديدة</h2>
            <p class="invoice-header-subtitle">إضافة فاتورة جديدة مع نظام اختيار المنتجات المتطور</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('invoices.store') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- بيانات الفاتورة الأساسية -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">📋</span>
                    بيانات الفاتورة الأساسية
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="invoice_number" class="form-label required">رقم الفاتورة</label>
                        <input type="text" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber }}" required class="form-control" readonly>
                        <small class="form-help">رقم الفاتورة (يتم إنشاؤه تلقائياً)</small>
                    </div>
                    <div class="form-group">
                        <label for="sale_date" class="form-label required">تاريخ إنشاء الفاتورة</label>
                        <input type="date" id="sale_date" name="sale_date" value="{{ date('Y-m-d') }}" required class="form-control" readonly>
                        <small class="form-help">تاريخ إنشاء الفاتورة (لا يمكن تعديله)</small>
                    </div>
                    <div class="form-group">
                        <label for="contract_date" class="form-label required">تاريخ عمل الفاتورة في المعرض</label>
                        <input type="date" id="contract_date" name="contract_date" value="{{ old('contract_date') }}" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="delivery_date" class="form-label required">تاريخ التسليم</label>
                        <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" required class="form-control" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- صورة العقد -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">📄</span>
                    صورة العقد
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="contract_number" class="form-label required">رقم العقد</label>
                        <input type="text" id="contract_number" name="contract_number" value="{{ old('contract_number') }}" required class="form-control" placeholder="رقم العقد">
                    </div>
                    <div class="form-group">
                        <label for="contract_image" class="form-label">صورة العقد</label>
                        <input type="file" id="contract_image" name="contract_image" accept="image/*" class="form-control">
                        <small class="form-help">صورة العقد (اختياري)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ملاحظات -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">📝</span>
                    ملاحظات
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="form-group">
                    <label for="notes" class="form-label">ملاحظات</label>
                    <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="أضف ملاحظاتك هنا...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- بيانات العميل -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">👤</span>
                    بيانات العميل
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer_id" class="form-label required">اختر العميل</label>
                        <select id="customer_id" name="customer_id" required class="form-control">
                            <option value="">ابحث بالاسم أو رقم الهاتف...</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" data-address="{{ $customer->address ?? '' }}" data-governorate="{{ $customer->governorate ?? '' }}">
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                            @endforeach
                        </select>
                        <small class="form-help">اكتب اسم العميل أو رقم الهاتف للبحث</small>
                    </div>
                    <div class="form-group">
                        <button type="button" onclick="openCustomerModal()" class="btn btn-outline mt-6">
                            <span class="text-green-600">➕</span>
                            إضافة عميل جديد
                        </button>
                    </div>
                </div>
                
                <div id="customer_info" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">معلومات العميل</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div><span class="font-medium text-blue-700">رقم الهاتف:</span> <span id="customer_phone" class="text-blue-600"></span></div>
                        <div><span class="font-medium text-blue-700">العنوان:</span> <span id="customer_address" class="text-blue-600"></span></div>
                        <div><span class="font-medium text-blue-700">المحافظة:</span> <span id="customer_governorate" class="text-blue-600"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- نظام اختيار المنتجات المتطور -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">🛋️</span>
                    اختيار المنتجات
                </h3>
            </div>
            <div class="invoice-card-body">
                <!-- خطوات الاختيار -->
                <div class="steps-container">
                    <div id="step-1" class="step-item active">
                        <div class="step-content">1</div>
                        <div class="step-label">اختيار الفئة</div>
                    </div>
                    <div id="step-2" class="step-item">
                        <div class="step-content">2</div>
                        <div class="step-label">اختيار المنتج</div>
                    </div>
                    <div id="step-3" class="step-item">
                        <div class="step-content">3</div>
                        <div class="step-label">تحديد الكمية والسعر</div>
                    </div>
                </div>

                <!-- الخطوة 1: اختيار الفئة -->
                <div id="step-1-content">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">اختر فئة المنتج</h4>
                    <div class="categories-grid">
                        <!-- الفئات ستظهر هنا عبر JavaScript -->
                    </div>
                    <div class="text-center mt-6">
                        <button type="button" class="btn btn-primary" onclick="nextStep()" disabled>التالي</button>
                    </div>
                </div>

                <!-- الخطوة 2: اختيار المنتج -->
                <div id="step-2-content" class="hidden">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">اختر المنتج من فئة: <span id="selected-category-name" class="text-blue-600"></span></h4>
                    
                    <div class="products-container">
                        <div class="products-header">
                            <div class="products-info">
                                <div class="products-count">
                                    <span id="products-count">0</span> منتج متاح
                                </div>
                            </div>
                            <div class="search-container">
                                <span class="search-icon">🔍</span>
                                <input type="text" id="product-search" placeholder="ابحث في المنتجات..." class="search-input">
                            </div>
                        </div>
                        
                        <!-- عرض صورة المنتج المختار -->
                        <div id="selected-product-preview" class="hidden mb-6">
                            <div class="product-preview">
                                <div id="preview-product-image" class="product-preview-image">
                                    <div class="product-placeholder">📦</div>
                                </div>
                                <div class="product-preview-info">
                                    <h4 id="preview-product-name">اسم المنتج</h4>
                                    <p>السعر: <span id="preview-product-price">0 ج.م</span></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- شبكة المنتجات -->
                        <div id="products-grid" class="products-grid">
                            <!-- المنتجات ستظهر هنا -->
                        </div>
                        
                        <!-- الترقيم -->
                        <div id="pagination" class="pagination-container hidden">
                            <button id="prev-page" class="pagination-btn" onclick="prevPage()">السابق</button>
                            <div id="page-info" class="page-info">صفحة 1 من 1</div>
                            <button id="next-page" class="pagination-btn" onclick="nextPage()">التالي</button>
                        </div>
                    </div>
                    
                    <div class="text-center mt-6">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">السابق</button>
                    </div>
                </div>

                <!-- الخطوة 3: تحديد الكمية والسعر -->
                <div id="step-3-content" class="hidden">
                    <div class="product-details-container">
                        <div class="product-preview">
                            <div id="selected-product-image" class="product-preview-image">
                                <div class="product-placeholder">📦</div>
                            </div>
                            <div class="product-preview-info">
                                <h4>حدد الكمية والسعر للمنتج: <span id="selected-product-name" class="text-blue-600"></span></h4>
                                <p>اختر الكمية والسعر ثم أضف المنتج للفاتورة</p>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="quantity" class="form-label required">الكمية</label>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="unit_price" class="form-label required">سعر الوحدة</label>
                                <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" required class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="discount" class="form-label">الخصم</label>
                                <input type="number" id="discount" name="discount" step="0.01" min="0" value="0" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="total" class="form-label">المجموع</label>
                                <input type="text" id="total" readonly class="form-control">
                            </div>
                        </div>
                        
                        <div class="text-center mt-6">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">السابق</button>
                            <button type="button" class="btn btn-success" onclick="addProductToInvoice()">إضافة للفاتورة</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المنتجات المختارة -->
        <div id="selected-products" class="invoice-card hidden">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-green-600">📋</span>
                    المنتجات المختارة
                </h3>
            </div>
            <div class="invoice-card-body">
                <div id="selected-products-list">
                    <!-- المنتجات ستظهر هنا -->
                </div>
            </div>
        </div>

        <!-- خصم إجمالي على الفاتورة -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">💰</span>
                    خصم إجمالي على الفاتورة
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="invoice_discount" class="form-label">خصم إجمالي على الفاتورة</label>
                        <input type="number" id="invoice_discount" name="invoice_discount" step="0.01" min="0" value="0" class="form-control">
                        <small class="form-help">خصم إجمالي على الفاتورة (اختياري)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ملخص الفاتورة -->
        <div class="invoice-card">
            <div class="invoice-card-header">
                <h3 class="invoice-card-title">
                    <span class="text-blue-600">📊</span>
                    ملخص الفاتورة
                </h3>
            </div>
            <div class="invoice-card-body">
                <div class="invoice-summary">
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-label">المجموع الفرعي</div>
                            <div class="summary-value">ج.م <span id="subtotal">0.00</span></div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">إجمالي الخصم</div>
                            <div class="summary-value">ج.م <span id="total-discount">0.00</span></div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">خصم الفاتورة</div>
                            <div class="summary-value">ج.م <span id="invoice-discount">0.00</span></div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-label">المجموع الكلي</div>
                            <div class="summary-value total">ج.م <span id="grand-total">0.00</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الإرسال -->
        <div class="text-center mt-8">
            <button type="submit" class="btn btn-primary btn-lg">
                <span class="text-xl">💾</span>
                حفظ الفاتورة
            </button>
        </div>
    </form>
</div>

<!-- Modal إضافة عميل جديد -->
<div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">➕ إضافة عميل جديد</h3>
                <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            
            <form id="customerForm">
                <div class="space-y-4">
                    <div class="form-group">
                        <label for="modal_name" class="form-label required">الاسم</label>
                        <input type="text" id="modal_name" name="name" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_phone" class="form-label required">رقم الهاتف</label>
                        <input type="text" id="modal_phone" name="phone" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_phone_alt" class="form-label">رقم هاتف بديل</label>
                        <input type="text" id="modal_phone_alt" name="phone_alt" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_country" class="form-label required">البلد</label>
                        <select id="modal_country" name="country" required class="form-control">
                            <option value="">اختر البلد</option>
                            <option value="مصر">مصر</option>
                            <option value="السعودية">السعودية</option>
                            <option value="الإمارات">الإمارات</option>
                            <option value="الكويت">الكويت</option>
                            <option value="قطر">قطر</option>
                            <option value="البحرين">البحرين</option>
                            <option value="عمان">عمان</option>
                            <option value="الأردن">الأردن</option>
                            <option value="لبنان">لبنان</option>
                            <option value="سوريا">سوريا</option>
                            <option value="العراق">العراق</option>
                            <option value="اليمن">اليمن</option>
                            <option value="فلسطين">فلسطين</option>
                            <option value="السودان">السودان</option>
                            <option value="ليبيا">ليبيا</option>
                            <option value="تونس">تونس</option>
                            <option value="الجزائر">الجزائر</option>
                            <option value="المغرب">المغرب</option>
                            <option value="موريتانيا">موريتانيا</option>
                            <option value="جيبوتي">جيبوتي</option>
                            <option value="الصومال">الصومال</option>
                            <option value="جزر القمر">جزر القمر</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_governorate" class="form-label required">المحافظة</label>
                        <select id="modal_governorate" name="governorate" required class="form-control">
                            <option value="">اختر المحافظة</option>
                            <option value="القاهرة">القاهرة</option>
                            <option value="الجيزة">الجيزة</option>
                            <option value="الإسكندرية">الإسكندرية</option>
                            <option value="الدقهلية">الدقهلية</option>
                            <option value="الشرقية">الشرقية</option>
                            <option value="الغربية">الغربية</option>
                            <option value="المنوفية">المنوفية</option>
                            <option value="القليوبية">القليوبية</option>
                            <option value="البحيرة">البحيرة</option>
                            <option value="كفر الشيخ">كفر الشيخ</option>
                            <option value="دمياط">دمياط</option>
                            <option value="بورسعيد">بورسعيد</option>
                            <option value="الإسماعيلية">الإسماعيلية</option>
                            <option value="السويس">السويس</option>
                            <option value="بني سويف">بني سويف</option>
                            <option value="المنيا">المنيا</option>
                            <option value="أسيوط">أسيوط</option>
                            <option value="سوهاج">سوهاج</option>
                            <option value="قنا">قنا</option>
                            <option value="الأقصر">الأقصر</option>
                            <option value="أسوان">أسوان</option>
                            <option value="الوادي الجديد">الوادي الجديد</option>
                            <option value="مطروح">مطروح</option>
                            <option value="شمال سيناء">شمال سيناء</option>
                            <option value="جنوب سيناء">جنوب سيناء</option>
                            <option value="البحر الأحمر">البحر الأحمر</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_address" class="form-label">العنوان</label>
                        <input type="text" id="modal_address" name="address" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="modal_notes" class="form-label">ملاحظات</label>
                        <textarea id="modal_notes" name="notes" rows="3" class="form-control"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary">💾 حفظ العميل</button>
                    <button type="button" class="btn btn-secondary" onclick="closeCustomerModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery (مطلوب لـ Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Custom JavaScript -->
<script src="{{ asset('js/invoice-create.js') }}"></script>

<script>
// Modal functions
function openCustomerModal() {
    document.getElementById('customerModal').classList.remove('hidden');
}

function closeCustomerModal() {
    document.getElementById('customerModal').classList.add('hidden');
    document.getElementById('customerForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('customerModal');
    if (event.target === modal) {
        closeCustomerModal();
    }
}

// Handle customer form submission
document.getElementById('customerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/customers', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إضافة العميل الجديد للقائمة
            addCustomerToList(data.customer);
            
            closeCustomerModal();
            alert('تم إضافة العميل بنجاح!');
        } else {
            alert('حدث خطأ أثناء إضافة العميل');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة العميل');
    });
});

// إضافة عميل جديد للقائمة
function addCustomerToList(customer) {
    const customerSelect = document.getElementById('customer_id');
    const option = document.createElement('option');
    option.value = customer.id;
    option.textContent = `${customer.name} - ${customer.phone}`;
    option.setAttribute('data-phone', customer.phone);
    option.setAttribute('data-address', customer.address || '');
    option.setAttribute('data-governorate', customer.governorate || '');
    
    customerSelect.appendChild(option);
    
    // تحديث Select2
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#customer_id').trigger('change');
        // اختيار العميل الجديد
        $('#customer_id').val(customer.id).trigger('change');
    }
}
</script>
@endpush
