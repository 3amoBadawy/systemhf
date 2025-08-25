@props(['product'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">معرض الصور والفيديوهات</h3>
    
    <!-- الصورة الأساسية -->
    @if($product->main_image)
    <div class="mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">الصورة الأساسية</h4>
        <div class="relative group">
            <img src="{{ $product->main_image_url }}" 
                 alt="{{ $product->display_name }}" 
                 class="w-full h-64 object-cover rounded-lg shadow-md">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                <button type="button" 
                        onclick="openImageModal('{{ $product->main_image_url }}', '{{ $product->display_name }}')"
                        class="opacity-0 group-hover:opacity-100 bg-white bg-opacity-90 text-gray-800 px-4 py-2 rounded-lg shadow-lg transition-all duration-200 hover:bg-opacity-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- معرض الصور -->
    @if($product->gallery_images && count($product->gallery_images) > 0)
    <div class="mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">معرض الصور</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($product->gallery_images as $index => $image)
            <div class="relative group">
                <img src="{{ asset('storage/' . $image) }}" 
                     alt="صورة {{ $index + 1 }}" 
                     class="w-full h-24 object-cover rounded-lg shadow-md">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-lg flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 flex space-x-2 rtl:space-x-reverse">
                        <button type="button" 
                                onclick="openImageModal('{{ asset('storage/' . $image) }}', 'صورة {{ $index + 1 }}')"
                                class="bg-white bg-opacity-90 text-gray-800 p-2 rounded-lg shadow-lg transition-all duration-200 hover:bg-opacity-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <button type="button" 
                                onclick="removeGalleryImage({{ $product->id }}, {{ $index }})"
                                class="bg-red-500 bg-opacity-90 text-white p-2 rounded-lg shadow-lg transition-all duration-200 hover:bg-opacity-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- معرض الفيديوهات -->
    @if($product->videos && count($product->videos) > 0)
    <div class="mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">معرض الفيديوهات</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($product->videos as $index => $video)
            <div class="relative group">
                <video class="w-full h-48 object-cover rounded-lg shadow-md" controls>
                    <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                    متصفحك لا يدعم تشغيل الفيديو.
                </video>
                <div class="absolute top-2 right-2 rtl:left-2">
                    <button type="button" 
                            onclick="removeVideo({{ $product->id }}, {{ $index }})"
                            class="bg-red-500 bg-opacity-90 text-white p-2 rounded-lg shadow-lg transition-all duration-200 hover:bg-opacity-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!$product->main_image && (!$product->gallery_images || count($product->gallery_images) == 0) && (!$product->videos || count($product->videos) == 0))
    <div class="text-center py-8 text-gray-500">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد صور أو فيديوهات</h3>
        <p class="mt-1 text-sm text-gray-500">لم يتم إضافة أي وسائط لهذا المنتج بعد.</p>
    </div>
    @endif
</div>

<!-- Modal لعرض الصور -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button type="button" 
                onclick="closeImageModal()"
                class="absolute top-4 right-4 rtl:left-4 z-10 bg-white bg-opacity-90 text-gray-800 p-2 rounded-lg shadow-lg hover:bg-opacity-100 transition-all duration-200">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-75 text-white px-4 py-2 rounded-lg">
            <span id="modalImageTitle"></span>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, imageTitle) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImageTitle').textContent = imageTitle;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// إغلاق Modal عند النقر خارجه
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// إغلاق Modal عند الضغط على ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

function removeGalleryImage(productId, imageIndex) {
    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        fetch(`/products/${productId}/gallery-image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image_index: imageIndex })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الصورة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الصورة');
        });
    }
}

function removeVideo(productId, videoIndex) {
    if (confirm('هل أنت متأكد من حذف هذا الفيديو؟')) {
        fetch(`/products/${productId}/video`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ video_index: videoIndex })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الفيديو');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الفيديو');
        });
    }
}
</script>


