@extends('layouts.app')

@section('title', 'تفاصيل الفئة - نظام إدارة معرض الأثاث')

@section('navbar-title', '🏷️ تفاصيل الفئة')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="card-title">🏷️ تفاصيل الفئة: {{ $category->name }}</h2>
                <p>معلومات مفصلة عن الفئة المحددة</p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn-secondary">🔙 رجوع للفئات</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div>
            <!-- معلومات الفئة -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📋 معلومات الفئة</h3>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">🏷️ اسم الفئة:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $category->name }}</p>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">🔢 ترتيب العرض:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $category->sort_order }}</p>
                    </div>
                </div>
                
                <div class="form-group" style="margin-top: 1rem;">
                    <label style="font-weight: 600; color: #2d3748;">📝 وصف الفئة:</label>
                    <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568; min-height: 60px;">
                        {{ $category->description ?: 'لا يوجد وصف' }}
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📊 الحالة:</label>
                        <div style="margin-top: 0.5rem;">
                            @if($category->is_active)
                                <span style="background: #c6f6d5; color: #22543d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">✅ نشط</span>
                            @else
                                <span style="background: #fed7d7; color: #742a2a; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">❌ غير نشط</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 600; color: #2d3748;">📅 تاريخ الإنشاء:</label>
                        <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $category->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1rem;">
                    <label style="font-weight: 600; color: #2d3748;">🔄 آخر تحديث:</label>
                    <p style="margin: 0; padding: 0.5rem; background: #f7fafc; border-radius: 5px; color: #4a5568;">{{ $category->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>

            <!-- المجموعات التابعة -->
            @if($category->collections->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📦 المجموعات التابعة</h3>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>📦 اسم المجموعة</th>
                                <th>📝 الوصف</th>
                                <th>🔢 عدد القطع</th>
                                <th>📊 الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->collections as $collection)
                            <tr>
                                <td><strong>{{ $collection->name }}</strong></td>
                                <td>{{ Str::limit($collection->description, 50) ?: 'لا يوجد وصف' }}</td>
                                <td>{{ $collection->items->count() }}</td>
                                <td>
                                    @if($collection->is_active)
                                        <span style="background: #c6f6d5; color: #22543d; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">✅ نشط</span>
                                    @else
                                        <span style="background: #fed7d7; color: #742a2a; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">❌ غير نشط</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <div>
            <!-- صورة الفئة -->
            @if($category->image)
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">🖼️ صورة الفئة</h3>
                </div>
                <div style="text-align: center;">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="صورة الفئة" 
                         style="max-width: 100%; border-radius: 8px; border: 2px solid #e2e8f0;">
                </div>
            </div>
            @endif

            <!-- إحصائيات سريعة -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">📊 إحصائيات سريعة</h3>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="color: #4a5568;">عدد المجموعات:</span>
                    <span style="background: #667eea; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">{{ $category->collections->count() }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #4a5568;">إجمالي القطع:</span>
                    <span style="background: #38b2ac; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.875rem;">{{ $category->collections->sum(function($collection) { return $collection->items->count(); }) }}</span>
                </div>
            </div>

            <!-- الإجراءات -->
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0; color: #2d3748; font-size: 1.1rem;">⚙️ الإجراءات</h3>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="{{ route('categories.edit', $category) }}" class="btn-primary" style="text-align: center;">
                        ✏️ تعديل الفئة
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" 
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width: 100%;">
                            🗑️ حذف الفئة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-responsive {
    overflow-x: auto;
}
</style>
@endsection
