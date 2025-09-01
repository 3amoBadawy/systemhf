@extends('layouts.app')

@section('title', 'عرض المنتج - نظام إدارة معرض الأثاث')

@section('navbar-title', '👁️ عرض المنتج')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">🛋️ {{ $product->name }}</h2>
                <p class="text-muted">تفاصيل المنتج</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('products.edit', $product) }}" class="btn-primary">✏️ تعديل</a>
                <a href="{{ route('products.index') }}" class="btn-secondary">🔙 عودة</a>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- معلومات المنتج -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📋 معلومات المنتج</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">اسم المنتج:</label>
                    <p class="form-value">{{ $product->name }}</p>
                </div>
                
                <div>
                    <label class="form-label">الفئة:</label>
                    <p class="form-value">{{ $product->category }}</p>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label class="form-label">الوصف:</label>
                <p class="form-value">{{ $product->description ?: 'لا يوجد وصف' }}</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label class="form-label">سعر البيع:</label>
                    <p class="form-value price">💰 {{ number_format($product->price, 2) }} {{ $currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol }}</p>
                </div>
                
                <div>
                    <label class="form-label">سعر التكلفة:</label>
                    <p class="form-value">{{ $product->cost_price ? number_format($product->cost_price, 2) . ' ' . ($currencySymbol ?? optional(\App\Models\BusinessSetting::getInstance())->currency_symbol) : 'غير محدد' }}</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label class="form-label">رمز المنتج (SKU):</label>
                    <p class="form-value">{{ $product->sku ?: 'غير محدد' }}</p>
                </div>
            </div>
        </div>

        <!-- صورة المنتج -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🖼️ صورة المنتج</h3>
            </div>
            
            @if($product->image)
                <div style="text-align: center;">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="صورة {{ $product->name }}" 
                         style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                </div>
            @else
                <div style="text-align: center; padding: 2rem; color: #6b7280;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🖼️</div>
                    <p>لا توجد صورة للمنتج</p>
                </div>
            @endif
        </div>
    </div>

    <!-- معلومات إضافية -->
    @if($product->collection)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📚 المجموعة</h3>
        </div>
        
        <div>
            <label class="form-label">اسم المجموعة:</label>
            <p class="form-value">{{ $product->collection->name }}</p>
        </div>
    </div>
    @endif

    <!-- القطع المكونة للمنتج -->
    @if($product->included_items && count($product->included_items) > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">🔧 القطع المكونة للمنتج</h3>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
            @foreach($product->included_items as $item)
            <div class="item-display-card">
                <div class="item-header">
                    <h4 class="item-name">{{ $item['name'] }}</h4>
                    <span class="item-quantity">الكمية: {{ $item['quantity'] ?? 1 }}</span>
                </div>
                @if(isset($item['notes']) && !empty($item['notes']))
                <div class="item-notes">
                    <strong>ملاحظات:</strong> {{ $item['notes'] }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- إحصائيات المنتج -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📊 إحصائيات المنتج</h3>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value">{{ $product->created_at->format('Y-m-d') }}</div>
                <div class="stat-label">تاريخ الإنشاء</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🔄</div>
                <div class="stat-value">{{ $product->updated_at->format('Y-m-d') }}</div>
                <div class="stat-label">آخر تحديث</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🆔</div>
                <div class="stat-value">{{ $product->id }}</div>
                <div class="stat-label">رقم المنتج</div>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
    display: block;
}

.form-value {
    color: #111827;
    margin: 0;
    padding: 0.5rem;
    background: #f9fafb;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.price {
    color: #059669;
    font-weight: 600;
}

.stock.in-stock {
    color: #059669;
}

.stock.out-of-stock {
    color: #dc2626;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
}

.badge.success {
    background: #d1fae5;
    color: #065f46;
}

.badge.error {
    background: #fee2e2;
    color: #991b1b;
}

.stat-card {
    text-align: center;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.stat-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
}

.item-display-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.item-display-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.item-name {
    margin: 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.item-quantity {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
}

.item-notes {
    color: #64748b;
    font-size: 0.875rem;
    border-top: 1px solid #e2e8f0;
    padding-top: 0.5rem;
}
</style>
@endsection
