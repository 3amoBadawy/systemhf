@extends('layouts.app')

@section('title', 'إدارة الوسائط - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">🎨 إدارة الوسائط</h1>
        <p class="mt-1 text-sm text-gray-500">إدارة الصور والفيديوهات والملفات</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <button onclick="openUploadModal()" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            رفع وسائط جديدة
        </button>
        
        <button onclick="openBulkUploadModal()" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            رفع متعدد
        </button>
        
        <a href="{{ route('media.gallery') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            معرض متقدم
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Filters and Search -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الوسائط</label>
                <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">جميع الأنواع</option>
                    <option value="image">صور</option>
                    <option value="video">فيديوهات</option>
                    <option value="document">مستندات</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">جميع الحالات</option>
                    <option value="1">عام</option>
                    <option value="0">خاص</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <input type="text" id="searchInput" placeholder="ابحث في الوسائط..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            
            <div class="flex items-end">
                <button onclick="applyFilters()" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    تطبيق الفلاتر
                </button>
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">الوسائط</h3>
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <button onclick="toggleViewMode('grid')" id="gridViewBtn" 
                            class="p-2 rounded-md bg-blue-100 text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button onclick="toggleViewMode('list')" id="listViewBtn" 
                            class="p-2 rounded-md text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Grid View -->
            <div id="gridView" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
            
            <!-- List View -->
            <div id="listView" class="hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصورة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحجم</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="listViewBody" class="bg-white divide-y divide-gray-200">
                            <!-- سيتم ملؤه بـ JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Loading State -->
            <div id="loadingState" class="hidden text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-gray-600">جاري تحميل الوسائط...</p>
            </div>
            
            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد وسائط</h3>
                <p class="mt-1 text-sm text-gray-500">ابدأ برفع وسائط جديدة</p>
                <div class="mt-6">
                    <button onclick="openUploadModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        رفع وسائط جديدة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">رفع وسائط جديدة</h3>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">اختر الملفات</label>
                    <input type="file" name="files[]" multiple accept="image/*,video/*,.pdf,.doc,.docx" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">النص البديل</label>
                    <input type="text" name="alt_text" placeholder="وصف الصورة" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">التعليق</label>
                    <textarea name="caption" placeholder="تعليق إضافي" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" checked 
                               class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">عام</span>
                    </label>
                </div>
                
                <div class="flex justify-end space-x-3 rtl:space-x-reverse">
                    <button type="button" onclick="closeUploadModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        رفع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Media Preview Modal -->
<div id="mediaPreviewModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 w-full max-w-4xl">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-medium text-gray-900" id="previewTitle">معاينة الوسائط</h3>
                <button onclick="closeMediaPreview()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-4" id="previewContent">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentViewMode = 'grid';
let mediaData = [];
let currentFilters = {};

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadMedia();
    setupEventListeners();
});

// إعداد Event Listeners
function setupEventListeners() {
    // Upload Form
    document.getElementById('uploadForm').addEventListener('submit', handleUpload);
    
    // Search Input
    document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
}

// تحميل الوسائط
async function loadMedia() {
    showLoading();
    
    try {
        const response = await fetch('/api/media?' + new URLSearchParams(currentFilters));
        const data = await response.json();
        
        if (data.success) {
            mediaData = data.data.data;
            renderMedia();
        } else {
            showError('حدث خطأ في تحميل الوسائط');
        }
    } catch (error) {
        showError('حدث خطأ في تحميل الوسائط');
    }
    
    hideLoading();
}

// عرض الوسائط
function renderMedia() {
    if (mediaData.length === 0) {
        showEmptyState();
        return;
    }
    
    if (currentViewMode === 'grid') {
        renderGridView();
    } else {
        renderListView();
    }
}

// عرض العرض الشبكي
function renderGridView() {
    const container = document.getElementById('gridView');
    container.innerHTML = '';
    
    mediaData.forEach(media => {
        const mediaCard = createMediaCard(media);
        container.appendChild(mediaCard);
    });
    
    document.getElementById('gridView').classList.remove('hidden');
    document.getElementById('listView').classList.add('hidden');
}

// عرض العرض القائمة
function renderListView() {
    const tbody = document.getElementById('listViewBody');
    tbody.innerHTML = '';
    
    mediaData.forEach(media => {
        const row = createMediaRow(media);
        tbody.appendChild(row);
    });
    
    document.getElementById('listView').classList.remove('hidden');
    document.getElementById('gridView').classList.add('hidden');
}

// إنشاء بطاقة وسائط
function createMediaCard(media) {
    const card = document.createElement('div');
    card.className = 'group relative bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow cursor-pointer';
    card.onclick = () => openMediaPreview(media);
    
    let mediaContent = '';
    if (media.isImage()) {
        mediaContent = `<img src="${media.thumbnail_url}" alt="${media.alt_text || media.original_name}" class="w-full h-32 object-cover">`;
    } else if (media.isVideo()) {
        mediaContent = `
            <div class="relative w-full h-32 bg-gray-100 flex items-center justify-center">
                <img src="${media.thumbnail_path ? '/storage/' + media.thumbnail_path : '/images/video-placeholder.png'}" 
                     alt="فيديو" class="w-16 h-16 text-gray-400">
                <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                    ${media.formatted_duration}
                </div>
            </div>
        `;
    } else {
        mediaContent = `
            <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        `;
    }
    
    card.innerHTML = `
        ${mediaContent}
        <div class="p-3">
            <h4 class="text-sm font-medium text-gray-900 truncate">${media.original_name}</h4>
            <p class="text-xs text-gray-500">${media.formatted_size}</p>
        </div>
        <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <button onclick="event.stopPropagation(); deleteMedia(${media.id})" 
                    class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    return card;
}

// إنشاء صف وسائط
function createMediaRow(media) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50';
    
    let mediaContent = '';
    if (media.isImage()) {
        mediaContent = `<img src="${media.thumbnail_url}" alt="${media.alt_text || media.original_name}" class="w-12 h-12 object-cover rounded">`;
    } else if (media.isVideo()) {
        mediaContent = `
            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        `;
    } else {
        mediaContent = `
            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        `;
    }
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            ${mediaContent}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">${media.original_name}</div>
            <div class="text-sm text-gray-500">${media.alt_text || ''}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                media.media_type === 'image' ? 'bg-green-100 text-green-800' :
                media.media_type === 'video' ? 'bg-blue-100 text-blue-800' :
                'bg-gray-100 text-gray-800'
            }">
                ${media.media_type === 'image' ? 'صورة' : media.media_type === 'video' ? 'فيديو' : 'مستند'}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            ${media.formatted_size}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            ${new Date(media.created_at).toLocaleDateString('ar-SA')}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button onclick="openMediaPreview(${JSON.stringify(media).replace(/"/g, '&quot;')})" 
                    class="text-blue-600 hover:text-blue-900 ml-3">عرض</button>
            <button onclick="deleteMedia(${media.id})" 
                    class="text-red-600 hover:text-red-900">حذف</button>
        </td>
    `;
    
    return row;
}

// تبديل وضع العرض
function toggleViewMode(mode) {
    currentViewMode = mode;
    
    if (mode === 'grid') {
        document.getElementById('gridViewBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('listViewBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('listViewBtn').classList.add('text-gray-400');
    } else {
        document.getElementById('listViewBtn').classList.add('bg-blue-100', 'text-blue-600');
        document.getElementById('gridViewBtn').classList.remove('bg-blue-100', 'text-blue-600');
        document.getElementById('gridViewBtn').classList.add('text-gray-400');
    }
    
    renderMedia();
}

// تطبيق الفلاتر
function applyFilters() {
    currentFilters = {
        type: document.getElementById('typeFilter').value,
        public: document.getElementById('statusFilter').value,
        search: document.getElementById('searchInput').value
    };
    
    loadMedia();
}

// معالجة البحث
function handleSearch() {
    currentFilters.search = document.getElementById('searchInput').value;
    loadMedia();
}

// معالجة الرفع
async function handleUpload(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    try {
        const response = await fetch('/api/media/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeUploadModal();
            loadMedia();
            showSuccess('تم رفع الوسائط بنجاح');
        } else {
            showError('حدث خطأ في رفع الوسائط');
        }
    } catch (error) {
        showError('حدث خطأ في رفع الوسائط');
    }
}

// حذف وسائط
async function deleteMedia(mediaId) {
    if (!confirm('هل أنت متأكد من حذف هذه الوسائط؟')) return;
    
    try {
        const response = await fetch(`/api/media/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadMedia();
            showSuccess('تم حذف الوسائط بنجاح');
        } else {
            showError('حدث خطأ في حذف الوسائط');
        }
    } catch (error) {
        showError('حدث خطأ في حذف الوسائط');
    }
}

// فتح معاينة الوسائط
function openMediaPreview(media) {
    const modal = document.getElementById('mediaPreviewModal');
    const title = document.getElementById('previewTitle');
    const content = document.getElementById('previewContent');
    
    title.textContent = media.original_name;
    
    let previewContent = '';
    if (media.isImage()) {
        previewContent = `
            <div class="text-center">
                <img src="${media.large_url}" alt="${media.alt_text || media.original_name}" 
                     class="max-w-full max-h-96 object-contain mx-auto">
                <div class="mt-4 text-left">
                    <p><strong>النص البديل:</strong> ${media.alt_text || 'غير محدد'}</p>
                    <p><strong>التعليق:</strong> ${media.caption || 'غير محدد'}</p>
                    <p><strong>الأبعاد:</strong> ${media.dimensions?.width || 0} × ${media.dimensions?.height || 0}</p>
                    <p><strong>الحجم:</strong> ${media.formatted_size}</p>
                </div>
            </div>
        `;
    } else if (media.isVideo()) {
        previewContent = `
            <div class="text-center">
                <video controls class="max-w-full max-h-96 mx-auto">
                    <source src="/storage/${media.path}" type="${media.mime_type}">
                    متصفحك لا يدعم تشغيل الفيديو
                </video>
                <div class="mt-4 text-left">
                    <p><strong>المدة:</strong> ${media.formatted_duration}</p>
                    <p><strong>الحجم:</strong> ${media.formatted_size}</p>
                </div>
            </div>
        `;
    } else {
        previewContent = `
            <div class="text-center">
                <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                    <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="mt-4 text-left">
                    <p><strong>اسم الملف:</strong> ${media.original_name}</p>
                    <p><strong>النوع:</strong> ${media.mime_type}</p>
                    <p><strong>الحجم:</strong> ${media.formatted_size}</p>
                </div>
            </div>
        `;
    }
    
    content.innerHTML = previewContent;
    modal.classList.remove('hidden');
}

// إغلاق معاينة الوسائط
function closeMediaPreview() {
    document.getElementById('mediaPreviewModal').classList.add('hidden');
}

// Modal Functions
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadForm').reset();
}

function openBulkUploadModal() {
    // يمكن إضافة modal منفصل للرفع المتعدد
    openUploadModal();
}

// Utility Functions
function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('gridView').classList.add('hidden');
    document.getElementById('listView').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showEmptyState() {
    document.getElementById('emptyState').classList.remove('hidden');
    document.getElementById('gridView').classList.add('hidden');
    document.getElementById('listView').classList.add('hidden');
}

function showSuccess(message) {
    // يمكن استخدام toast notification
    alert(message);
}

function showError(message) {
    // يمكن استخدام toast notification
    alert('خطأ: ' + message);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endpush
