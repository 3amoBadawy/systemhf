<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected \App\Services\MediaService $mediaService;

    /**
     * تحديث منتج
     */
    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
    {
        $this->validateProductUpdateRequest($request);

        $this->handleMediaFiles($request, $product);

        $profitPercentage = $this->calculateProfitPercentage($request);

        $components = $this->processComponents($request);

        $this->updateProduct($product, $request, $profitPercentage, $components);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح!');
    }

    /**
     * التحقق من صحة بيانات تحديث المنتج
     */
    private function validateProductUpdateRequest(Request $request): void
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description_ar' => 'nullable|string',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'videos.*' => 'nullable|mimes:mp4,mov,avi,wmv,flv,webm|max:10240',
        ]);
    }

    /**
     * معالجة ملفات الوسائط
     */
    private function handleMediaFiles(Request $request, Product $product): void
    {
        if (! $request->hasFile('media_files')) {
            return;
        }

        $product->media()->delete();
        $mediaFiles = $request->file('media_files');

        if (! is_array($mediaFiles)) {
            return;
        }

        foreach ($mediaFiles as $file) {
            try {
                $mediaData = $this->processMediaFile($file, $request);
                $mediaData->update([
                    'mediaable_type' => Product::class,
                    'mediaable_id' => $product->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Error processing media file in update', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);

                continue;
            }
        }
    }

    /**
     * حساب نسبة الربح
     */
    private function calculateProfitPercentage(Request $request): float
    {
        if (empty($request->cost_price) || empty($request->price) || $request->cost_price <= 0) {
            return 0;
        }

        return (($request->price - $request->cost_price) / $request->cost_price) * 100;
    }

    /**
     * معالجة مكونات المنتج
     */
    private function processComponents(Request $request): array
    {
        if (! $request->has('components') || ! is_array($request->input('components'))) {
            return [];
        }

        $components = [];
        foreach ($request->input('components') as $component) {
            if (empty($component['name']) || empty($component['quantity'])) {
                continue;
            }

            $componentData = $this->createComponentData($component);
            $components[] = $componentData;
        }

        return $components;
    }

    /**
     * إنشاء بيانات المكون
     */
    private function createComponentData(array $component): array
    {
        $componentData = [
            'name' => $component['name'],
            'quantity' => (int) $component['quantity'],
        ];

        if ($this->hasComponentPricing($component)) {
            $componentData = array_merge($componentData, $this->getComponentPricing($component));
        }

        if (! $this->hasComponentPricing($component)) {
            $componentData['has_pricing'] = false;
        }

        return $componentData;
    }

    /**
     * التحقق من وجود تسعير للمكون
     */
    private function hasComponentPricing(array $component): bool
    {
        return ! empty($component['has_pricing']) && $component['has_pricing'] == '1';
    }

    /**
     * الحصول على تسعير المكون
     */
    private function getComponentPricing(array $component): array
    {
        $componentData = ['has_pricing' => true];

        if (empty($component['cost_price']) || empty($component['selling_price'])) {
            return $componentData;
        }

        $costPrice = (float) $component['cost_price'];
        $sellingPrice = (float) $component['selling_price'];
        $profitPercentage = $this->calculateComponentProfitPercentage($costPrice, $sellingPrice);

        $componentData['cost_price'] = $costPrice;
        $componentData['selling_price'] = $sellingPrice;
        $componentData['profit_percentage'] = $profitPercentage;

        return $componentData;
    }

    /**
     * حساب نسبة ربح المكون
     */
    private function calculateComponentProfitPercentage(float $costPrice, float $sellingPrice): float
    {
        if ($costPrice <= 0) {
            return 0;
        }

        return (($sellingPrice - $costPrice) / $costPrice) * 100;
    }

    /**
     * تحديث بيانات المنتج
     */
    private function updateProduct(Product $product, Request $request, float $profitPercentage, array $components): void
    {
        $product->update([
            'name_ar' => $request->input('name_ar'),
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'supplier_id' => $request->input('supplier_id'),
            'description_ar' => $request->input('description_ar'),
            'description' => $request->input('description'),
            'cost_price' => $request->input('cost_price'),
            'profit_percentage' => $profitPercentage,
            'price' => $request->input('price'),
            'components' => $components,
        ]);
    }

    /**
     * معالجة ملف وسائط
     */
    private function processMediaFile(\Illuminate\Http\UploadedFile $file, Request $request): Media
    {
        $mimeType = $file->getMimeType() ?? 'application/octet-stream';
        $mediaData = $this->processMediaByType($file, $mimeType);
        $mediaType = $this->determineMediaType($mimeType);

        return $this->createMediaRecord($file, $request, $mediaData, $mediaType);
    }

    /**
     * معالجة الوسائط حسب النوع
     */
    private function processMediaByType(\Illuminate\Http\UploadedFile $file, string $mimeType): array
    {
        if (str_starts_with($mimeType, 'image/')) {
            return $this->mediaService->uploadImage($file, 'products');
        }

        if (str_starts_with($mimeType, 'video/')) {
            return $this->mediaService->uploadVideo($file, 'products');
        }

        return $this->processDocumentFile($file, $mimeType);
    }

    /**
     * معالجة ملف المستند
     */
    private function processDocumentFile(\Illuminate\Http\UploadedFile $file, string $mimeType): array
    {
        $filename = $this->mediaService->generateUniqueFilename($file);
        $path = $file->storeAs('public/documents', $filename);

        return [
            'original' => $path,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $mimeType,
        ];
    }

    /**
     * تحديد نوع الوسائط
     */
    private function determineMediaType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return 'document';
    }

    /**
     * إنشاء سجل الوسائط
     */
    private function createMediaRecord(\Illuminate\Http\UploadedFile $file, Request $request, array $mediaData, string $mediaType): Media
    {
        return Media::create([
            'filename' => $mediaData['filename'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mediaData['mime_type'],
            'size' => $mediaData['size'],
            'path' => $mediaData['original'],
            'disk' => 'public',
            'alt_text' => $request->get('alt_text', ''),
            'caption' => $request->get('caption', ''),
            'is_public' => true,
            'media_type' => $mediaType,
            'dimensions' => $mediaType === 'image' ? $this->getImageDimensions($file) : null,
            'duration' => null,
            'thumbnail_path' => null,
            'optimized_versions' => null,
            'order' => 0,
        ]);
    }

    /**
     * الحصول على أبعاد الصورة
     *
     * @return int[]
     *
     * @psalm-return array{width: int, height: int}
     */
    private function getImageDimensions(\Illuminate\Http\UploadedFile $file): array
    {
        try {
            // استخدام getimagesize بدلاً من Intervention Image
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo) {
                return [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1],
                ];
            }

            return ['width' => 0, 'height' => 0];
        } catch (\Exception $e) {
            Log::error('Error getting image dimensions: '.$e->getMessage());

            return ['width' => 0, 'height' => 0];
        }
    }
}
