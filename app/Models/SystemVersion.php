<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $version
 * @property string|null $version_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $release_date
 * @property string $type
 * @property array<array-key, mixed>|null $features
 * @property array<array-key, mixed>|null $bug_fixes
 * @property bool $is_current
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereBugFixes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemVersion whereVersionName($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class SystemVersion extends Model
{
    protected $fillable = [
        'version',
        'version_name',
        'description',
        'release_date',
        'type',
        'features',
        'bug_fixes',
        'is_current',
        'is_required',
    ];

    protected $casts = [
        'release_date' => 'date',
        'features' => 'array',
        'bug_fixes' => 'array',
        'is_current' => 'boolean',
        'is_required' => 'boolean',
    ];

    /**
     * الحصول على الإصدار الحالي
     */
    public static function getCurrentVersion(): ?static
    {
        return self::where('is_current', true)->first();
    }

    /**
     * تعيين إصدار كإصدار حالي
     */
    public function setAsCurrent(): bool
    {
        // إلغاء الإصدارات الحالية
        self::where('is_current', true)->update(['is_current' => false]);

        // تعيين هذا الإصدار كحالي
        $this->update(['is_current' => true]);

        return true;
    }
}
