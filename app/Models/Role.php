<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $description
 * @property string $description_ar
 * @property bool $is_system
 * @property int $sort_order
 * @property bool $is_active
 * @property int|null $branch_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedBy($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'permissions',
        'is_active',
        'is_system',
        'sort_order',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'sort_order' => 'integer',
        'branch_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_system' => false,
        'sort_order' => 0,
    ];

    /**
     * العلاقة مع المستخدمين
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * العلاقة مع الموظفين
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * العلاقة مع الصلاحيات
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission(string $permission): bool
    {
        // Get permissions from the relationship
        $permissions = $this->permissions()->pluck('name')->toArray();

        if (empty($permissions)) {
            return false;
        }

        // التحقق من الصلاحية المباشرة
        if (in_array($permission, $permissions)) {
            return true;
        }

        // التحقق من الصلاحيات المجمعة
        foreach ($permissions as $perm) {
            if (str_starts_with($perm, $permission.'.') || $perm === '*') {
                return true;
            }
        }

        return false;
    }

    /**
     * التحقق من إمكانية الحذف
     */
    public function canBeDeleted(): bool
    {
        if ($this->is_system) {
            return false;
        }

        return $this->users()->count() === 0 && $this->employees()->count() === 0;
    }

    /**
     * Boot method
     */
    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (Auth::check()) {
                $role->created_by = Auth::id();
            }
        });

        static::updating(function ($role) {
            if (Auth::check()) {
                $role->updated_by = Auth::id();
            }
        });

        static::deleting(function ($role) {
            if (! $role->canBeDeleted()) {
                throw new \Exception('لا يمكن حذف هذا الدور لأنه مستخدم أو دور نظامي');
            }
        });
    }
}
