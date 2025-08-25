<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $mediaService;

    /**
     * تحديث منتج
     */
    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
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

        // رفع الوسائط الجديدة باستخدام النظام الجديد
        if ($request->hasFile('media_files')) {
            // حذف الوسائط القديمة
            $product->media()->delete();

            // رفع الوسائط الجديدة
            foreach ($request->file('media_files') as $file) {
                try {
                    $mediaData = $this->processMediaFile($file, $request);
                    $mediaData->update([
                        'mediaable_type' => Product::class,
                        'mediaable_id' => $product->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error processing media file in update', [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);

                    continue;
                }
            }
        }

        // حساب نسبة الربح تلقائياً
        $profitPercentage = 0;
        if (! empty($request->cost_price) && ! empty($request->price) && $request->cost_price > 0) {
            $profitPercentage = (($request->price - $request->cost_price) / $request->cost_price) * 100;
        }

        // تجهيز المكونات وتسعيرها
        $components = [];
        $componentPricing = [];

        if ($request->has('components') && is_array($request->components)) {
            foreach ($request->components as $component) {
                if (! empty($component['name']) && ! empty($component['quantity'])) {
                    $componentData = [
                        'name' => $component['name'],
                        'quantity' => (int) $component['quantity'],
                    ];

                    // إضافة تسعير المكون إذا كان مفعل
                    if (! empty($component['has_pricing']) && $component['has_pricing'] == '1') {
                        $componentData['has_pricing'] = true;

                        // إضافة بيانات التسعير
                        if (! empty($component['cost_price']) && ! empty($component['selling_price'])) {
                            $costPrice = (float) $component['cost_price'];
                            $sellingPrice = (float) $component['selling_price'];
                            $profitPercentage = 0;

                            if ($costPrice > 0) {
                                $profitPercentage = (($sellingPrice - $costPrice) / $costPrice) * 100;
                            }

                            $componentData['cost_price'] = $costPrice;
                            $componentData['selling_price'] = $sellingPrice;
                            $componentData['profit_percentage'] = $profitPercentage;

                            // إضافة لتسعير المكونات
                            $componentPricing[] = [
                                'name' => $component['name'],
                                'cost_price' => $costPrice,
                                'selling_price' => $sellingPrice,
                                'profit_percentage' => $profitPercentage,
                            ];
                        }
                    } else {
                        $componentData['has_pricing'] = false;
                    }

                    $components[] = $componentData;
                }
            }
        }

        $product->update([
            'name_ar' => $request->name_ar,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'description_ar' => $request->description_ar,
            'description' => $request->description,
            'cost_price' => $request->cost_price,
            'profit_percentage' => $profitPercentage,
            'price' => $request->price,
            'components' => $components,
            'component_pricing' => $componentPricing,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'تم تحديث المنتج بنجاح!');
    }

    /**
     * معالجة ملف وسائط
     */
    private function processMediaFile($file, Request $request)
    {
        $mimeType = $file->getMimeType();
        $isImage = str_starts_with($mimeType, 'image/');
        $isVideo = str_starts_with($mimeType, 'video/');

        if ($isImage) {
            $mediaData = $this->mediaService->uploadImage($file, 'products');
        } elseif ($isVideo) {
            $mediaData = $this->mediaService->uploadVideo($file, 'products');
        } else {
            // ملفات أخرى
            $filename = $this->mediaService->generateUniqueFilename($file);
            $path = $file->storeAs('public/documents', $filename);
            $mediaData = [
                'original' => $path,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $mimeType,
            ];
        }

        // إنشاء سجل في قاعدة البيانات
        $media = Media::create([
            'filename' => $mediaData['filename'],
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $mimeType,
            'size' => $mediaData['size'],
            'path' => $mediaData['original'],
            'disk' => 'public',
            'alt_text' => $request->get('alt_text', ''),
            'caption' => $request->get('caption', ''),
            'is_public' => true,
            'media_type' => $isImage ? 'image' : ($isVideo ? 'video' : 'document'),
            'dimensions' => $isImage ? $this->getImageDimensions($file) : null,
            'duration' => null,
            'thumbnail_path' => null,
            'optimized_versions' => null,
            'order' => 0,
        ]);

        return $media;
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
            \Log::error('Error getting image dimensions: '.$e->getMessage());

            return ['width' => 0, 'height' => 0];
        }
    }
}
