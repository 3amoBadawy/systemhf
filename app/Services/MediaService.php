<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaService
{
    /**
     * رفع صورة مع إنشاء أحجام مختلفة
     */
    public function uploadImage(UploadedFile $file, string $folder = 'general'): array
    {
        $filename = $this->generateUniqueFilename($file);
        $path = $file->storeAs("public/{$folder}", $filename);

        // إنشاء أحجام مختلفة للصورة
        $optimizedVersions = $this->createImageVersions($file, $folder, $filename);

        // الحصول على أبعاد الصورة الأصلية
        // @phpstan-ignore-next-line
        $image = Image::make($file);
        $dimensions = [
            'width' => $image->width(),
            'height' => $image->height(),
        ];

        return [
            'original' => $path,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'dimensions' => $dimensions,
            'optimized_versions' => $optimizedVersions,
        ];
    }

    /**
     * إنشاء أحجام مختلفة للصورة
     */
    private function createImageVersions(UploadedFile $file, string $folder, string $filename): array
    {
        $versions = [];
        // @phpstan-ignore-next-line
        $image = Image::make($file);

        // الصورة المصغرة 150x150
        $thumbnail = $image->clone()->fit(150, 150);
        $thumbnailPath = "public/{$folder}/thumbnails/thumb_{$filename}";
        Storage::put($thumbnailPath, $thumbnail->encode('jpeg', 80));
        $versions['thumbnail'] = $thumbnailPath;

        // الصورة المتوسطة 400px عرض
        if ($image->width() > 400) {
            $medium = $image->clone()->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $mediumPath = "public/{$folder}/medium/med_{$filename}";
            Storage::put($mediumPath, $medium->encode('jpeg', 85));
            $versions['medium'] = $mediumPath;
        }

        // الصورة الكبيرة 800px عرض
        if ($image->width() > 800) {
            $large = $image->clone()->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $largePath = "public/{$folder}/large/lg_{$filename}";
            Storage::put($largePath, $large->encode('jpeg', 90));
            $versions['large'] = $largePath;
        }

        return $versions;
    }

    /**
     * رفع فيديو
     */
    public function uploadVideo(UploadedFile $file, string $folder = 'general'): array
    {
        $filename = $this->generateUniqueFilename($file);
        $path = $file->storeAs("public/{$folder}", $filename);

        return [
            'original' => $path,
            'filename' => $filename,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * إنشاء اسم ملف فريد
     */
    public function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->timestamp;
        $random = Str::random(8);

        return "{$name}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * تحسين الصورة
     */
    public function optimizeImage(string $imagePath, array $options = []): string
    {
        // @phpstan-ignore-next-line
        $image = Image::make($imagePath);

        // تطبيق التحسينات
        if (isset($options['width']) && isset($options['height'])) {
            $image->resize($options['width'], $options['height']);
        }

        if (isset($options['quality'])) {
            $image->encode('jpg', $options['quality']);
        }

        // حفظ الصورة المحسنة
        $optimizedPath = $this->generateOptimizedPath($imagePath);
        $image->save($optimizedPath);

        return $optimizedPath;
    }

    /**
     * إنشاء thumbnail
     */
    public function createThumbnail(string $imagePath, int $width = 150, int $height = 150): string
    {
        // @phpstan-ignore-next-line
        $image = Image::make($imagePath);
        $image->fit($width, $height);

        $thumbnailPath = $this->generateThumbnailPath($imagePath);
        $image->save($thumbnailPath);

        return $thumbnailPath;
    }

    /**
     * إنشاء مسار الصورة المحسنة
     */
    private function generateOptimizedPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'] ?? dirname($originalPath);
        $filename = $pathInfo['filename'] ?? pathinfo($originalPath, PATHINFO_FILENAME);
        $extension = $pathInfo['extension'] ?? 'jpg';

        return "{$directory}/optimized/{$filename}_opt.{$extension}";
    }

    /**
     * إنشاء مسار الصورة المصغرة
     */
    private function generateThumbnailPath(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $directory = $pathInfo['dirname'] ?? dirname($originalPath);
        $filename = $pathInfo['filename'] ?? pathinfo($originalPath, PATHINFO_FILENAME);
        $extension = $pathInfo['extension'] ?? 'jpg';

        return "{$directory}/thumbnails/{$filename}_thumb.{$extension}";
    }
}
