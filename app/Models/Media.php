<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $filename
 * @property string $original_name
 * @property string $mime_type
 * @property int $size
 * @property string $path
 * @property string $disk
 * @property string|null $alt_text
 * @property string|null $caption
 * @property string|null $description
 * @property array<string, mixed>|null $metadata
 * @property bool $is_public
 * @property string $media_type
 * @property array<string, mixed>|null $dimensions
 * @property int|null $duration
 * @property string|null $thumbnail_path
 * @property array<string, mixed>|null $optimized_versions
 * @property string $mediaable_type
 * @property int $mediaable_id
 * @property int $order
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDimensions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereOptimizedVersions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'size',
        'path',
        'disk',
        'alt_text',
        'caption',
        'description',
        'metadata',
        'is_public',
        'media_type', // image, video, document
        'dimensions', // JSON: width, height
        'duration', // للفيديوهات
        'thumbnail_path',
        'optimized_versions', // JSON: thumbnail, medium, large, webp
        'mediaable_type',
        'mediaable_id',
        'order',
        'is_featured',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'metadata' => 'array',
        'dimensions' => 'array',
        'optimized_versions' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'size' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
        'filename' => 'string',
        'original_name' => 'string',
        'mime_type' => 'string',
        'path' => 'string',
        'disk' => 'string',
        'alt_text' => 'string',
        'caption' => 'string',
        'description' => 'string',
        'media_type' => 'string',
        'thumbnail_path' => 'string',
        'mediaable_type' => 'string',
        'mediaable_id' => 'integer',
    ];

    /** @var array<string> */
    protected $appends = [
        'url',
        'thumbnail_url',
        'medium_url',
        'large_url',
        'webp_url',
        'formatted_size',
        'formatted_duration',
    ];

    /**
     * علاقة polymorphic
     */
    public function mediaable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * حذف الملفات من التخزين عند حذف السجل
     */
    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            // حذف الملف الأصلي
            if (Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            // حذف النسخ المحسنة
            if ($media->optimized_versions) {
                foreach ($media->optimized_versions as $version) {
                    if (Storage::disk('public')->exists($version)) {
                        Storage::disk('public')->delete($version);
                    }
                }
            }

            // حذف thumbnail الفيديو
            if ($media->thumbnail_path && Storage::disk('public')->exists($media->thumbnail_path)) {
                Storage::disk('public')->delete($media->thumbnail_path);
            }
        });
    }
}
