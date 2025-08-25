// نظام إنشاء الفواتير المحسن
class InvoiceCreator {
    constructor() {
        this.currentStep = 1;
        this.selectedCategory = null;
        this.selectedCategoryName = null;
        this.selectedProduct = null;
        this.selectedProductName = null;
        this.selectedProductPrice = null;
        this.selectedProductImage = null;
        this.selectedProducts = [];
        this.currentPage = 1;
        this.totalPages = 1;
        this.perPage = 12;
        this.products = [];
        this.categories = [];
        
        this.init();
    }
    
    init() {
        this.loadData();
        this.setupEventListeners();
        this.initializeSelect2();
    }
    
    async loadData() {
        try {
            // تحميل الفئات
            const categoriesResponse = await fetch('/api/categories');
            this.categories = await categoriesResponse.json();
            
            // تحميل المنتجات
            const productsResponse = await fetch('/api/products');
            this.products = await productsResponse.json();
            
            this.renderCategories();
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }
    
    setupEventListeners() {
        // البحث في المنتجات
        const searchInput = document.getElementById('product-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.searchProducts(e.target.value));
        }
        
        // الكمية والسعر
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const discountInput = document.getElementById('discount');
        
        if (quantityInput) quantityInput.addEventListener('input', () => this.calculateTotal());
        if (unitPriceInput) unitPriceInput.addEventListener('input', () => this.calculateTotal());
        if (discountInput) discountInput.addEventListener('input', () => this.calculateTotal());
        
        // خصم الفاتورة
        const invoiceDiscountInput = document.getElementById('invoice_discount');
        if (invoiceDiscountInput) {
            invoiceDiscountInput.addEventListener('input', () => this.updateTotals());
        }
    }
    
    initializeSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#customer_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'ابحث بالاسم أو رقم الهاتف...',
                allowClear: true,
                width: '100%',
                language: 'ar',
                dir: 'rtl'
            });
            
            $('#customer_id').on('change', (e) => {
                const customerId = e.target.value;
                if (customerId) {
                    this.showCustomerInfo(customerId);
                } else {
                    this.hideCustomerInfo();
                }
            });
        }
    }
    
    renderCategories() {
        const container = document.getElementById('step-1-content');
        if (!container) return;
        
        const categoriesGrid = container.querySelector('.categories-grid') || container;
        if (categoriesGrid) {
                    categoriesGrid.innerHTML = this.categories.map(category => `
            <div 
                id="category-${category.id}"
                onclick="invoiceCreator.selectCategory(${category.id}, '${category.name_ar}')" 
                class="category-card"
            >
                <div class="category-icon">🛋️</div>
                <div class="category-name">${category.name_ar}</div>
                <div class="category-count">${this.getProductsCountByCategory(category.id)} منتج</div>
            </div>
        `).join('');
        }
    }
    
    getProductsCountByCategory(categoryId) {
        return this.products.filter(p => p.category_id == categoryId).length;
    }
    
    selectCategory(categoryId, categoryName) {
        console.log('Selecting category:', categoryId, categoryName);
        
        // إزالة التحديد من جميع الفئات
        document.querySelectorAll('.category-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // تحديد الفئة المختارة
        const selectedCard = document.getElementById(`category-${categoryId}`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
        }
        
        this.selectedCategory = categoryId;
        this.selectedCategoryName = categoryName;
        
        this.updateStepStatus(1, 'completed');
        this.showProductsByCategory(categoryId);
        this.nextStep();
    }
    
    async showProductsByCategory(categoryId) {
        const step2Content = document.getElementById('step-2-content');
        const productsGrid = document.getElementById('products-grid');
        
        if (!step2Content || !productsGrid) return;
        
        // إظهار الخطوة الثانية
        step2Content.style.display = 'block';
        document.getElementById('step-1-content').style.display = 'none';
        
        // تحديث اسم الفئة
        const categoryNameSpan = document.getElementById('selected-category-name');
        if (categoryNameSpan) {
            categoryNameSpan.textContent = this.selectedCategoryName;
        }
        
        // تحميل المنتجات
        await this.loadProductsForCategory(categoryId);
        
        this.updateStepStatus(2, true);
    }
    
    async loadProductsForCategory(categoryId) {
        try {
            const response = await fetch(`/products/category/${categoryId}?per_page=${this.perPage}&page=${this.currentPage}`);
            const data = await response.json();
            
            this.renderProducts(data.data || data);
            this.updatePagination(data.pagination);
            
            // تحديث عدد المنتجات
            const productsCount = document.getElementById('products-count');
            if (productsCount && data.pagination) {
                productsCount.textContent = data.pagination.total;
            }
        } catch (error) {
            console.error('Error loading products:', error);
            this.showError('حدث خطأ أثناء تحميل المنتجات');
        }
    }
    
    renderProducts(products) {
        const productsGrid = document.getElementById('products-grid');
        if (!productsGrid) return;
        
        if (!products || products.length === 0) {
            productsGrid.innerHTML = `
                <div class="no-products">
                    <div class="no-products-icon">📦</div>
                    <div class="no-products-text">لا توجد منتجات في هذه الفئة</div>
                </div>
            `;
            return;
        }
        
        productsGrid.innerHTML = products.map(product => `
            <div 
                class="product-card"
                onclick="invoiceCreator.selectProduct(${product.id}, '${product.name_ar}', ${product.price}, '${product.main_image || ''}')"
            >
                <div class="product-image">
                    ${product.main_image ? 
                        `<img src="/storage/${product.main_image}" alt="${product.name_ar}">` :
                        `<div class="product-placeholder">📦</div>`
                    }
                </div>
                <div class="product-info">
                    <div class="product-name">${product.name_ar}</div>
                    ${product.description_ar ? `<div class="product-description">${product.description_ar.substring(0, 60)}${product.description_ar.length > 60 ? '...' : ''}</div>` : ''}
                    <div class="product-price">${product.price} ج.م</div>
                </div>
            </div>
        `).join('');
    }
    
    selectProduct(productId, productName, productPrice, productImage) {
        console.log('Selecting product:', productId, productName, productPrice);
        
        this.selectedProduct = productId;
        this.selectedProductName = productName;
        this.selectedProductPrice = productPrice;
        this.selectedProductImage = productImage;
        
        this.updateStepStatus(2, 'completed');
        this.showProductDetails();
        this.nextStep();
    }
    
    showProductDetails() {
        const step3Content = document.getElementById('step-3-content');
        if (!step3Content) return;
        
        // إظهار الخطوة الثالثة
        step3Content.style.display = 'block';
        document.getElementById('step-2-content').style.display = 'none';
        
        // تحديث اسم المنتج
        const productNameSpan = document.getElementById('selected-product-name');
        if (productNameSpan) {
            productNameSpan.textContent = this.selectedProductName;
        }
        
        // تحديث صورة المنتج
        const productImageDiv = document.getElementById('selected-product-image');
        if (productImageDiv) {
            if (this.selectedProductImage) {
                productImageDiv.innerHTML = `<img src="/storage/${this.selectedProductImage}" alt="${this.selectedProductName}">`;
            } else {
                productImageDiv.innerHTML = `<div class="product-placeholder">📦</div>`;
            }
        }
        
        // تعيين السعر
        const unitPriceInput = document.getElementById('unit_price');
        if (unitPriceInput) {
            unitPriceInput.value = this.selectedProductPrice;
        }
        
        this.calculateTotal();
        this.updateStepStatus(3, true);
    }
    
    calculateTotal() {
        const quantity = parseInt(document.getElementById('quantity')?.value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price')?.value) || 0;
        const discount = parseFloat(document.getElementById('discount')?.value) || 0;
        
        const total = (quantity * unitPrice) - discount;
        const totalInput = document.getElementById('total');
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }
    }
    
    addProductToInvoice() {
        const quantity = parseInt(document.getElementById('quantity')?.value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price')?.value) || 0;
        const discount = parseFloat(document.getElementById('discount')?.value) || 0;
        
        if (!this.selectedProduct || !quantity || !unitPrice) {
            this.showError('يرجى تحديد المنتج والكمية والسعر');
            return;
        }
        
        const total = (quantity * unitPrice) - discount;
        
        const productData = {
            id: this.selectedProduct,
            name: this.selectedProductName,
            image: this.selectedProductImage,
            quantity: quantity,
            unit_price: unitPrice,
            discount: discount,
            total: total
        };
        
        this.selectedProducts.push(productData);
        this.displaySelectedProducts();
        this.resetProductSelection();
        this.showStep(1);
    }
    
    resetProductSelection() {
        this.selectedProduct = null;
        this.selectedProductName = null;
        this.selectedProductImage = null;
        this.selectedCategory = null;
        this.selectedCategoryName = null;
        
        // إعادة تعيين النموذج
        const quantityInput = document.getElementById('quantity');
        const unitPriceInput = document.getElementById('unit_price');
        const discountInput = document.getElementById('discount');
        const totalInput = document.getElementById('total');
        
        if (quantityInput) quantityInput.value = 1;
        if (unitPriceInput) unitPriceInput.value = '';
        if (discountInput) discountInput.value = 0;
        if (totalInput) totalInput.value = '';
        
        // إزالة التحديد من الفئات
        document.querySelectorAll('.category-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // إعادة تعيين حالة الخطوات
        this.updateStepStatus(1, false);
        this.updateStepStatus(2, false);
        this.updateStepStatus(3, false);
    }
    
    displaySelectedProducts() {
        const container = document.getElementById('selected-products-list');
        const productsContainer = document.getElementById('selected-products');
        
        if (!container || !productsContainer) return;
        
        if (this.selectedProducts.length === 0) {
            productsContainer.style.display = 'none';
            return;
        }
        
        productsContainer.style.display = 'block';
        
        container.innerHTML = this.selectedProducts.map((product, index) => `
            <div class="selected-product-item">
                <div class="product-image">
                    ${product.image ? 
                        `<img src="/storage/${product.image}" alt="${product.name}">` :
                        `<div class="product-placeholder">📦</div>`
                    }
                </div>
                <div class="product-details">
                    <div class="product-name">${product.name}</div>
                    <div class="product-quantity">الكمية: ${product.quantity} × ${product.unit_price} ج.م</div>
                </div>
                <div class="product-actions">
                    <div class="product-total">${product.total} ج.م</div>
                    <button type="button" onclick="invoiceCreator.removeProduct(${index})" class="remove-btn">حذف</button>
                </div>
            </div>
        `).join('');
        
        this.updateTotals();
    }
    
    removeProduct(index) {
        this.selectedProducts.splice(index, 1);
        this.displaySelectedProducts();
    }
    
    updateTotals() {
        const subtotal = this.selectedProducts.reduce((sum, product) => sum + product.total, 0);
        const totalDiscount = this.selectedProducts.reduce((sum, product) => sum + product.discount, 0);
        const invoiceDiscount = parseFloat(document.getElementById('invoice_discount')?.value) || 0;
        const grandTotal = subtotal - invoiceDiscount;
        
        const subtotalSpan = document.getElementById('subtotal');
        const totalDiscountSpan = document.getElementById('total-discount');
        const invoiceDiscountSpan = document.getElementById('invoice-discount');
        const grandTotalSpan = document.getElementById('grand-total');
        
        if (subtotalSpan) subtotalSpan.textContent = subtotal.toFixed(2);
        if (totalDiscountSpan) totalDiscountSpan.textContent = totalDiscount.toFixed(2);
        if (invoiceDiscountSpan) invoiceDiscountSpan.textContent = invoiceDiscount.toFixed(2);
        if (grandTotalSpan) grandTotalSpan.textContent = grandTotal.toFixed(2);
    }
    
    searchProducts(searchTerm) {
        const productCards = document.querySelectorAll('#products-grid .product-card');
        
        productCards.forEach(card => {
            const productName = card.querySelector('.product-name')?.textContent.toLowerCase() || '';
            const productDesc = card.querySelector('.product-description')?.textContent.toLowerCase() || '';
            
            if (productName.includes(searchTerm.toLowerCase()) || productDesc.includes(searchTerm.toLowerCase())) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    nextStep() {
        if (this.currentStep < 3) {
            this.showStep(this.currentStep + 1);
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.showStep(this.currentStep - 1);
        }
    }
    
    showStep(stepNumber) {
        // إخفاء جميع الخطوات
        document.getElementById('step-1-content').style.display = 'none';
        document.getElementById('step-2-content').style.display = 'none';
        document.getElementById('step-3-content').style.display = 'none';
        
        // إظهار الخطوة المطلوبة
        const stepContent = document.getElementById(`step-${stepNumber}-content`);
        if (stepContent) {
            stepContent.style.display = 'block';
        }
        
        this.currentStep = stepNumber;
        this.updateStepStatus(stepNumber, true);
    }
    
    updateStepStatus(stepNumber, status) {
        const step = document.getElementById(`step-${stepNumber}`);
        if (!step) return;
        
        const stepNumberDiv = step.querySelector('div');
        if (!stepNumberDiv) return;
        
        if (status === true) {
            step.style.borderColor = '#667eea';
            step.style.background = '#ebf8ff';
            stepNumberDiv.style.background = '#667eea';
            stepNumberDiv.style.color = 'white';
        } else if (status === 'completed') {
            step.style.borderColor = '#48bb78';
            step.style.background = '#f0fff4';
            stepNumberDiv.style.background = '#48bb78';
            stepNumberDiv.style.color = 'white';
        } else {
            step.style.borderColor = '#e2e8f0';
            step.style.background = 'white';
            stepNumberDiv.style.background = '#e2e8f0';
            stepNumberDiv.style.color = '#4a5568';
        }
    }
    
    updatePagination(pagination) {
        if (!pagination) return;
        
        this.totalPages = pagination.last_page;
        const paginationContainer = document.getElementById('pagination');
        
        if (!paginationContainer) return;
        
        if (this.totalPages <= 1) {
            paginationContainer.style.display = 'none';
            return;
        }
        
        paginationContainer.style.display = 'block';
        
        const pageInfo = document.getElementById('page-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        
        if (pageInfo) pageInfo.textContent = `صفحة ${this.currentPage} من ${this.totalPages}`;
        if (prevBtn) prevBtn.disabled = this.currentPage <= 1;
        if (nextBtn) nextBtn.disabled = this.currentPage >= this.totalPages;
    }
    
    async loadPage(page) {
        if (page < 1 || page > this.totalPages || !this.selectedCategory) return;
        
        this.currentPage = page;
        await this.loadProductsForCategory(this.selectedCategory);
    }
    
    prevPage() {
        if (this.currentPage > 1) {
            this.loadPage(this.currentPage - 1);
        }
    }
    
    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.loadPage(this.currentPage + 1);
        }
    }
    
    showCustomerInfo(customerId) {
        const customerSelect = document.getElementById('customer_id');
        const selectedOption = customerSelect?.querySelector(`option[value="${customerId}"]`);
        const customerInfo = document.getElementById('customer_info');
        
        if (selectedOption && customerInfo) {
            const phone = selectedOption.getAttribute('data-phone') || '';
            const address = selectedOption.getAttribute('data-address') || '';
            const governorate = selectedOption.getAttribute('data-governorate') || '';
            
            const phoneSpan = document.getElementById('customer_phone');
            const addressSpan = document.getElementById('customer_address');
            const governorateSpan = document.getElementById('customer_governorate');
            
            if (phoneSpan) phoneSpan.textContent = phone;
            if (addressSpan) addressSpan.textContent = address || 'غير محدد';
            if (governorateSpan) governorateSpan.textContent = governorate || 'غير محدد';
            
            customerInfo.style.display = 'block';
        }
    }
    
    hideCustomerInfo() {
        const customerInfo = document.getElementById('customer_info');
        if (customerInfo) {
            customerInfo.style.display = 'none';
        }
    }
    
    showError(message) {
        // يمكن تطوير هذا ليعرض رسائل خطأ أفضل
        alert(message);
    }
    
    // إضافة المنتجات للفورم عند الإرسال
    prepareFormSubmission() {
        const form = document.querySelector('form');
        if (!form) return;
        
        // إضافة المنتجات المختارة للفورم
        this.selectedProducts.forEach((product, index) => {
            const productInputs = `
                <input type="hidden" name="products[${index}][product_id]" value="${product.id}">
                <input type="hidden" name="products[${index}][quantity]" value="${product.quantity}">
                <input type="hidden" name="products[${index}][unit_price]" value="${product.unit_price}">
                <input type="hidden" name="products[${index}][discount]" value="${product.discount}">
                <input type="hidden" name="products[${index}][total]" value="${product.total}">
            `;
            form.insertAdjacentHTML('beforeend', productInputs);
        });
    }
}

// تهيئة النظام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    window.invoiceCreator = new InvoiceCreator();
    
    // إعداد إرسال الفورم
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            window.invoiceCreator.prepareFormSubmission();
        });
    }
    
    // إعداد أزرار التنقل
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    
    if (prevPageBtn) prevPageBtn.addEventListener('click', () => window.invoiceCreator.prevPage());
    if (nextPageBtn) nextPageBtn.addEventListener('click', () => window.invoiceCreator.nextPage());
});

// دوال مساعدة للاستدعاء من HTML
function selectCategory(categoryId, categoryName) {
    if (window.invoiceCreator) {
        window.invoiceCreator.selectCategory(categoryId, categoryName);
    }
}

function selectProduct(productId, productName, productPrice, productImage) {
    if (window.invoiceCreator) {
        window.invoiceCreator.selectProduct(productId, productName, productPrice, productImage);
    }
}

function nextStep() {
    if (window.invoiceCreator) {
        window.invoiceCreator.nextStep();
    }
}

function prevStep() {
    if (window.invoiceCreator) {
        window.invoiceCreator.prevStep();
    }
}

function addProductToInvoice() {
    if (window.invoiceCreator) {
        window.invoiceCreator.addProductToInvoice();
    }
}

function removeProduct(index) {
    if (window.invoiceCreator) {
        window.invoiceCreator.removeProduct(index);
    }
}

function prevPage() {
    if (window.invoiceCreator) {
        window.invoiceCreator.prevPage();
    }
}

function nextPage() {
    if (window.invoiceCreator) {
        window.invoiceCreator.nextPage();
    }
}
