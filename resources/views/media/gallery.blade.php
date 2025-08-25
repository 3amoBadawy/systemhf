@extends('layouts.app')

@section('title', 'معرض الوسائط - نظام إدارة معرض الأثاث')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">🖼️ معرض الوسائط المتقدم</h1>
        <p class="mt-1 text-sm text-gray-500">استكشف الصور والفيديوهات بجودة عالية</p>
    </div>
    <div class="flex space-x-3 rtl:space-x-reverse">
        <button onclick="openLightbox()" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            عرض كامل الشاشة
        </button>
        
        <button onclick="toggle360View()" id="view360Btn" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            عرض 360°
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Advanced Filters -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الوسائط</label>
                <select id="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">جميع الأنواع</option>
                    <option value="image">صور</option>
                    <option value="video">فيديوهات</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الأبعاد</label>
                <select id="dimensionFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">جميع الأبعاد</option>
                    <option value="square">مربعة</option>
                    <option value="landscape">أفقية</option>
                    <option value="portrait">عمودية</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحجم</label>
                <select id="sizeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">جميع الأحجام</option>
                    <option value="small">صغير (&lt; 1MB)</option>
                    <option value="medium">متوسط (1-5MB)</option>
                    <option value="large">كبير (&gt; 5MB)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <input type="text" id="searchInput" placeholder="ابحث في الوسائط..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            
            <div class="flex items-end">
                <button onclick="applyAdvancedFilters()" 
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    تطبيق الفلاتر
                </button>
            </div>
        </div>
    </div>

    <!-- Gallery View Options -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <span class="text-sm font-medium text-gray-700">عرض:</span>
                <button onclick="setGalleryView('masonry')" id="masonryBtn" 
                        class="px-3 py-1 text-sm rounded-md bg-blue-100 text-blue-700">Masonry</button>
                <button onclick="setGalleryView('grid')" id="gridBtn" 
                        class="px-3 py-1 text-sm rounded-md text-gray-600 hover:bg-gray-100">Grid</button>
                <button onclick="setGalleryView('list')" id="listBtn" 
                        class="px-3 py-1 text-sm rounded-md text-gray-600 hover:bg-gray-100">List</button>
            </div>
            
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm text-gray-500">ترتيب:</span>
                <select id="sortOrder" onchange="sortGallery()" class="text-sm border border-gray-300 rounded px-2 py-1">
                    <option value="newest">الأحدث</option>
                    <option value="oldest">الأقدم</option>
                    <option value="name">الاسم</option>
                    <option value="size">الحجم</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Gallery Container -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="p-6">
            <!-- Masonry View -->
            <div id="masonryView" class="columns-1 md:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
            
            <!-- Grid View -->
            <div id="gridView" class="hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
            
            <!-- List View -->
            <div id="listView" class="hidden space-y-4">
                <!-- سيتم ملؤه بـ JavaScript -->
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
                <p class="mt-1 text-sm text-gray-500">جرب تغيير الفلاتر أو البحث</p>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative h-full">
        <!-- Close Button -->
        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Navigation Buttons -->
        <button onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 text-white hover:text-gray-300">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        
        <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 text-white hover:text-gray-300">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        
        <!-- Image Container -->
        <div class="flex items-center justify-center h-full p-8">
            <div id="lightboxContent" class="max-w-full max-h-full">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
        </div>
        
        <!-- Thumbnail Navigation -->
        <div id="thumbnailNav" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-10">
            <div class="flex space-x-2 rtl:space-x-reverse">
                <!-- سيتم ملؤه بـ JavaScript -->
            </div>
        </div>
        
        <!-- Image Info -->
        <div id="imageInfo" class="absolute bottom-4 left-4 z-10 text-white text-sm">
            <!-- سيتم ملؤه بـ JavaScript -->
        </div>
    </div>
</div>

<!-- 360 View Modal -->
<div id="view360Modal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative h-full">
        <button onclick="close360View()" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="flex items-center justify-center h-full">
            <div id="view360Content" class="text-center text-white">
                <h3 class="text-2xl font-bold mb-4">عرض 360 درجة</h3>
                <p class="text-lg mb-8">هذه الميزة قيد التطوير</p>
                <div class="w-64 h-64 bg-gray-800 rounded-lg flex items-center justify-center mx-auto">
                    <svg class="w-24 h-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentViewMode = 'masonry';
let mediaData = [];
let currentFilters = {};
let currentImageIndex = 0;
let isLightboxOpen = false;

// تهيئة الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadMedia();
    setupEventListeners();
    setupKeyboardNavigation();
});

// إعداد Event Listeners
function setupEventListeners() {
    // Search Input
    document.getElementById('searchInput').addEventListener('input', debounce(handleSearch, 300));
    
    // Keyboard Navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
}

// إعداد التنقل بالكيبورد
function setupKeyboardNavigation() {
    document.addEventListener('keydown', function(e) {
        if (isLightboxOpen) {
            switch(e.key) {
                case 'ArrowLeft':
                    previousImage();
                    break;
                case 'ArrowRight':
                    nextImage();
                    break;
                case 'Escape':
                    closeLightbox();
                    break;
            }
        }
    });
}

// تحميل الوسائط
async function loadMedia() {
    showLoading();
    
    try {
        const response = await fetch('/api/media?' + new URLSearchParams(currentFilters));
        const data = await response.json();
        
        if (data.success) {
            mediaData = data.data.data;
            renderGallery();
        } else {
            showError('حدث خطأ في تحميل الوسائط');
        }
    } catch (error) {
        showError('حدث خطأ في تحميل الوسائط');
    }
    
    hideLoading();
}

// عرض المعرض
function renderGallery() {
    if (mediaData.length === 0) {
        showEmptyState();
        return;
    }
    
    switch(currentViewMode) {
        case 'masonry':
            renderMasonryView();
            break;
        case 'grid':
            renderGridView();
            break;
        case 'list':
            renderListView();
            break;
    }
}

// عرض Masonry
function renderMasonryView() {
    const container = document.getElementById('masonryView');
    container.innerHTML = '';
    
    mediaData.forEach((media, index) => {
        const mediaItem = createMasonryItem(media, index);
        container.appendChild(mediaItem);
    });
    
    showView('masonryView');
}

// عرض Grid
function renderGridView() {
    const container = document.getElementById('gridView');
    container.innerHTML = '';
    
    mediaData.forEach((media, index) => {
        const mediaItem = createGridItem(media, index);
        container.appendChild(mediaItem);
    });
    
    showView('gridView');
}

// عرض List
function renderListView() {
    const container = document.getElementById('listView');
    container.innerHTML = '';
    
    mediaData.forEach((media, index) => {
        const mediaItem = createListItem(media, index);
        container.appendChild(mediaItem);
    });
    
    showView('listView');
}

// إنشاء عنصر Masonry
function createMasonryItem(media, index) {
    const item = document.createElement('div');
    item.className = 'break-inside-avoid mb-4';
    
    let mediaContent = '';
    if (media.isImage()) {
        mediaContent = `
            <div class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <img src="${media.medium_url}" alt="${media.alt_text || media.original_name}" 
                     class="w-full h-auto object-cover" loading="lazy"
                     onclick="openLightbox(${index})">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <h4 class="text-white font-medium truncate">${media.original_name}</h4>
                    <p class="text-gray-200 text-sm">${media.formatted_size}</p>
                </div>
                <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button onclick="event.stopPropagation(); downloadMedia('${media.url}')" 
                            class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-blue-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
    } else if (media.isVideo()) {
        mediaContent = `
            <div class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:scale-105">
                <div class="relative">
                    <img src="${media.thumbnail_path ? '/storage/' + media.thumbnail_path : '/images/video-placeholder.png'}" 
                         alt="فيديو" class="w-full h-auto object-cover" loading="lazy">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                        ${media.formatted_duration}
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <h4 class="text-white font-medium truncate">${media.original_name}</h4>
                    <p class="text-gray-200 text-sm">${media.formatted_size}</p>
                </div>
            </div>
        `;
    }
    
    item.innerHTML = mediaContent;
    return item;
}

// إنشاء عنصر Grid
function createGridItem(media, index) {
    const item = document.createElement('div');
    item.className = 'group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer';
    
    let mediaContent = '';
    if (media.isImage()) {
        mediaContent = `
            <img src="${media.thumbnail_url}" alt="${media.alt_text || media.original_name}" 
                 class="w-full h-48 object-cover" loading="lazy"
                 onclick="openLightbox(${index})">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <h4 class="text-white font-medium text-sm truncate">${media.original_name}</h4>
            </div>
        `;
    } else if (media.isVideo()) {
        mediaContent = `
            <div class="relative w-full h-48 bg-gray-100 flex items-center justify-center">
                <img src="${media.thumbnail_path ? '/storage/' + media.thumbnail_path : '/images/video-placeholder.png'}" 
                     alt="فيديو" class="w-16 h-16 text-gray-400">
                <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                    ${media.formatted_duration}
                </div>
            </div>
        `;
    }
    
    item.innerHTML = mediaContent;
    return item;
}

// إنشاء عنصر List
function createListItem(media, index) {
    const item = document.createElement('div');
    item.className = 'flex items-center space-x-4 rtl:space-x-reverse p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer';
    item.onclick = () => openLightbox(index);
    
    let mediaContent = '';
    if (media.isImage()) {
        mediaContent = `<img src="${media.thumbnail_url}" alt="${media.alt_text || media.original_name}" class="w-20 h-20 object-cover rounded">`;
    } else if (media.isVideo()) {
        mediaContent = `
            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        `;
    }
    
    item.innerHTML = `
        ${mediaContent}
        <div class="flex-1">
            <h4 class="font-medium text-gray-900">${media.original_name}</h4>
            <p class="text-sm text-gray-500">${media.alt_text || ''}</p>
            <p class="text-xs text-gray-400">${media.formatted_size} • ${new Date(media.created_at).toLocaleDateString('ar-SA')}</p>
        </div>
        <div class="flex items-center space-x-2 rtl:space-x-reverse">
            <button onclick="event.stopPropagation(); downloadMedia('${media.url}')" 
                    class="p-2 text-gray-400 hover:text-blue-600 rounded-full hover:bg-blue-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </button>
        </div>
    `;
    
    return item;
}

// تبديل وضع العرض
function setGalleryView(view) {
    currentViewMode = view;
    
    // تحديث الأزرار
    document.getElementById('masonryBtn').classList.remove('bg-blue-100', 'text-blue-700');
    document.getElementById('gridBtn').classList.remove('bg-blue-100', 'text-blue-700');
    document.getElementById('listBtn').classList.remove('bg-blue-100', 'text-blue-700');
    
    document.getElementById(view + 'Btn').classList.add('bg-blue-100', 'text-blue-700');
    
    renderGallery();
}

// إظهار عرض محدد
function showView(viewId) {
    ['masonryView', 'gridView', 'listView'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
    document.getElementById(viewId).classList.remove('hidden');
}

// تطبيق الفلاتر المتقدمة
function applyAdvancedFilters() {
    currentFilters = {
        type: document.getElementById('typeFilter').value,
        dimension: document.getElementById('dimensionFilter').value,
        size: document.getElementById('sizeFilter').value,
        search: document.getElementById('searchInput').value
    };
    
    loadMedia();
}

// معالجة البحث
function handleSearch() {
    currentFilters.search = document.getElementById('searchInput').value;
    loadMedia();
}

// ترتيب المعرض
function sortGallery() {
    const sortOrder = document.getElementById('sortOrder').value;
    
    switch(sortOrder) {
        case 'newest':
            mediaData.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'oldest':
            mediaData.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            break;
        case 'name':
            mediaData.sort((a, b) => a.original_name.localeCompare(b.original_name, 'ar'));
            break;
        case 'size':
            mediaData.sort((a, b) => a.size - b.size);
            break;
    }
    
    renderGallery();
}

// فتح Lightbox
function openLightbox(index = 0) {
    if (index >= 0 && index < mediaData.length) {
        currentImageIndex = index;
        showLightboxImage();
        document.getElementById('lightboxModal').classList.remove('hidden');
        isLightboxOpen = true;
        document.body.style.overflow = 'hidden';
    }
}

// إغلاق Lightbox
function closeLightbox() {
    document.getElementById('lightboxModal').classList.add('hidden');
    isLightboxOpen = false;
    document.body.style.overflow = 'auto';
}

// عرض الصورة في Lightbox
function showLightboxImage() {
    const media = mediaData[currentImageIndex];
    const content = document.getElementById('lightboxContent');
    const info = document.getElementById('imageInfo');
    const nav = document.getElementById('thumbnailNav');
    
    if (media.isImage()) {
        content.innerHTML = `
            <img src="${media.large_url}" alt="${media.alt_text || media.original_name}" 
                 class="max-w-full max-h-full object-contain">
        `;
        
        info.innerHTML = `
            <div>
                <p class="font-medium">${media.original_name}</p>
                <p class="text-gray-300">${media.dimensions?.width || 0} × ${media.dimensions?.height || 0} • ${media.formatted_size}</p>
            </div>
        `;
    } else if (media.isVideo()) {
        content.innerHTML = `
            <video controls class="max-w-full max-h-full">
                <source src="/storage/${media.path}" type="${media.mime_type}">
                متصفحك لا يدعم تشغيل الفيديو
            </video>
        `;
        
        info.innerHTML = `
            <div>
                <p class="font-medium">${media.original_name}</p>
                <p class="text-gray-300">${media.formatted_duration} • ${media.formatted_size}</p>
            </div>
        `;
    }
    
    // تحديث التنقل المصغر
    updateThumbnailNavigation();
}

// تحديث التنقل المصغر
function updateThumbnailNavigation() {
    const nav = document.getElementById('thumbnailNav');
    nav.innerHTML = '';
    
    mediaData.forEach((media, index) => {
        const thumb = document.createElement('div');
        thumb.className = `w-16 h-16 rounded cursor-pointer border-2 ${index === currentImageIndex ? 'border-blue-500' : 'border-transparent'}`;
        thumb.onclick = () => {
            currentImageIndex = index;
            showLightboxImage();
        };
        
        if (media.isImage()) {
            thumb.innerHTML = `<img src="${media.thumbnail_url}" alt="" class="w-full h-full object-cover rounded">`;
        } else {
            thumb.innerHTML = `
                <div class="w-full h-full bg-gray-800 rounded flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            `;
        }
        
        nav.appendChild(thumb);
    });
}

// الصورة السابقة
function previousImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
    } else {
        currentImageIndex = mediaData.length - 1;
    }
    showLightboxImage();
}

// الصورة التالية
function nextImage() {
    if (currentImageIndex < mediaData.length - 1) {
        currentImageIndex++;
    } else {
        currentImageIndex = 0;
    }
    showLightboxImage();
}

// عرض 360 درجة
function toggle360View() {
    document.getElementById('view360Modal').classList.remove('hidden');
}

// إغلاق عرض 360 درجة
function close360View() {
    document.getElementById('view360Modal').classList.add('hidden');
}

// تحميل الوسائط
function downloadMedia(url) {
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// معالجة التنقل بالكيبورد
function handleKeyboardNavigation(e) {
    if (isLightboxOpen) {
        switch(e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                previousImage();
                break;
            case 'ArrowRight':
                e.preventDefault();
                nextImage();
                break;
            case 'Escape':
                e.preventDefault();
                closeLightbox();
                break;
        }
    }
}

// Utility Functions
function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    ['masonryView', 'gridView', 'listView'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
    document.getElementById('emptyState').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
}

function showEmptyState() {
    document.getElementById('emptyState').classList.remove('hidden');
    ['masonryView', 'gridView', 'listView'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
}

function showError(message) {
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
