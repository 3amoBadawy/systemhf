<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $product_code
 * @property string $name
 * @property string $name_ar
 * @property-read string $display_name
 * @property string $description
 * @property string $description_ar
 * @property string $category
 * @property string $brand
 * @property string $model
 * @property string $unit
 * @property string $unit_ar
 * @property int $stock_quantity
 * @property int $min_stock_level
 * @property string|null $main_image
 * @property array<string, mixed>|null $gallery_images
 * @property array<string, mixed>|null $videos
 * @property array<string, mixed>|null $components
 * @property array<string, mixed>|null $component_pricing
 * @property float $price
 * @property float $cost_price
 * @property float $profit_percentage
 * @property bool $is_active
 * @property int|null $supplier_id
 * @property int|null $branch_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product whereBrand($value)
 * @method static Builder<static>|Product whereCategory($value)
 * @method static Builder<static>|Product whereComponentPricing($value)
 * @method static Builder<static>|Product whereComponents($value)
 * @method static Builder<static>|Product whereCostPrice($value)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereCreatedBy($value)
 * @method static Builder<static>|Product whereDescription($value)
 * @method static Builder<static>|Product whereDescriptionAr($value)
 * @method static Builder<static>|Product whereGalleryImages($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereIsActive($value)
 * @method static Builder<static>|Product whereMainImage($value)
 * @method static Builder<static>|Product whereMinStockLevel($value)
 * @method static Builder<static>|Product whereModel($value)
 * @method static Builder<static>|Product whereName($value)
 * @method static Builder<static>|Product whereNameAr($value)
 * @method static Builder<static>|Product wherePrice($value)
 * @method static Builder<static>|Product whereProductCode($value)
 * @method static Builder<static>|Product whereProfitPercentage($value)
 * @method static Builder<static>|Product whereStockQuantity($value)
 * @method static Builder<static>|Product whereSupplierId($value)
 * @method static Builder<static>|Product whereUnit($value)
 * @method static Builder<static>|Product whereUnitAr($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product whereUpdatedBy($value)
 * @method static Builder<static>|Product whereVideos($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'category',
        'brand',
        'model',
        'unit',
        'unit_ar',
        'stock_quantity',
        'min_stock_level',
        'main_image',
        'gallery_images',
        'videos',
        'components',
        'component_pricing',
        'price',
        'cost_price',
        'profit_percentage',
        'is_active',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_level' => 'integer',
        'warranty_months' => 'integer',
        'gallery_images' => 'array',
        'videos' => 'array',
        'components' => 'array',
        'component_pricing' => 'array',
        'is_active' => 'boolean',
        'product_code' => 'string',
        'name' => 'string',
        'name_ar' => 'string',
        'description' => 'string',
        'description_ar' => 'string',
        'category' => 'string',
        'brand' => 'string',
        'model' => 'string',
        'unit' => 'string',
        'unit_ar' => 'string',
        'main_image' => 'string',
        'supplier_id' => 'integer',
        'branch_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating([self::class, 'handleCreating']);
        static::updating([self::class, 'handleUpdating']);
    }

    /**
     * معالجة حدث الإنشاء
     */
    protected static function handleCreating(Product $product): void
    {
        self::setProductCode($product);
        self::setDefaultProfitPercentage($product);
        self::calculatePrice($product);
    }

    /**
     * معالجة حدث التحديث
     */
    protected static function handleUpdating(Product $product): void
    {
        self::calculateProfitPercentage($product);
        self::recalculatePrice($product);
    }

    /**
     * تعيين كود المنتج
     */
    private static function setProductCode(Product $product): void
    {
        if (empty($product->product_code)) {
            $product->product_code = self::generateProductCode();
        }
    }

    /**
     * تعيين نسبة الربح الافتراضية
     */
    private static function setDefaultProfitPercentage(Product $product): void
    {
        if (empty($product->profit_percentage)) {
            $product->profit_percentage = 20.0; // Default 20%
        }
    }

    /**
     * حساب السعر
     */
    private static function calculatePrice(Product $product): void
    {
        if (self::canCalculatePrice($product)) {
            $product->price = self::calculatePriceFromCostAndProfit($product);
        }
    }

    /**
     * التحقق من إمكانية حساب السعر
     */
    private static function canCalculatePrice(Product $product): bool
    {
        return ! empty($product->cost_price) && ! empty($product->profit_percentage) && empty($product->price);
    }

    /**
     * حساب السعر من التكلفة ونسبة الربح
     */
    private static function calculatePriceFromCostAndProfit(Product $product): float
    {
        return $product->cost_price * (1 + ($product->profit_percentage / 100));
    }

    /**
     * حساب نسبة الربح
     */
    private static function calculateProfitPercentage(Product $product): void
    {
        if (self::canCalculateProfitPercentage($product)) {
            $product->profit_percentage = self::calculateProfitPercentageFromPriceAndCost($product);
        }
    }

    /**
     * التحقق من إمكانية حساب نسبة الربح
     */
    private static function canCalculateProfitPercentage(Product $product): bool
    {
        return $product->isDirty('price') && ! empty($product->cost_price) && ! empty($product->price);
    }

    /**
     * حساب نسبة الربح من السعر والتكلفة
     */
    private static function calculateProfitPercentageFromPriceAndCost(Product $product): float
    {
        return (($product->price - $product->cost_price) / $product->cost_price) * 100;
    }

    /**
     * إعادة حساب السعر
     */
    private static function recalculatePrice(Product $product): void
    {
        if (self::shouldRecalculatePrice($product)) {
            $product->price = self::calculatePriceFromCostAndProfit($product);
        }
    }

    /**
     * التحقق من الحاجة لإعادة حساب السعر
     */
    private static function shouldRecalculatePrice(Product $product): bool
    {
        return ($product->isDirty('cost_price') || $product->isDirty('profit_percentage')) &&
               ! empty($product->cost_price) && ! empty($product->profit_percentage);
    }

    /**
     * إنشاء كود منتج تلقائي
     */
    public static function generateProductCode(): string
    {
        $prefix = 'PRD';
        // This should be handled by a repository
        $nextId = 1; // Placeholder

        return $prefix.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * علاقة الوسائط (صور، فيديوهات، مستندات)
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    /**
     * علاقة الصور فقط
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    /**
     * علاقة الفيديوهات فقط
     */
    public function videos(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    /**
     * علاقة المورد
     */
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * علاقة الفرع
     */
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * الحصول على اسم العرض
     */
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name;
    }
}
