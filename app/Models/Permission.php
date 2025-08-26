<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $group
 * @property string|null $group_ar
 * @property bool|null $is_system
 * @property int|null $sort_order
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $description
 * @property string|null $description_ar
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescriptionAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGroupAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTrashed()
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'group',
        'group_ar',
        'is_active',
        'is_system',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'sort_order' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'name' => 'string',
        'name_ar' => 'string',
        'key' => 'string',
        'group' => 'string',
        'description' => 'string',
        'description_ar' => 'string',
    ];

    protected $attributes = [
        'is_active' => true,
        'is_system' => false,
        'sort_order' => 0,
    ];

    /**
     * العلاقة مع الأدوار
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    /**
     * العلاقة مع المستخدمين
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permission');
    }

    /**
     * العلاقة مع الموظفين
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_permission');
    }

    /**
     * التحقق من إمكانية الحذف
     */
    public function canBeDeleted(): bool
    {
        if ($this->is_system) {
            return false;
        }

        return $this->roles()->count() === 0 &&
               $this->users()->count() === 0 &&
               $this->employees()->count() === 0;
    }

    /**
     * الحصول على الصلاحيات الأساسية
     */
    public static function getBasicPermissions(): array
    {
        return [
            'users' => [
                'users.view' => 'عرض المستخدمين',
                'users.create' => 'إنشاء مستخدمين',
                'users.edit' => 'تعديل المستخدمين',
                'users.delete' => 'حذف المستخدمين',
                'users.roles' => 'إدارة أدوار المستخدمين',
            ],
            'employees' => [
                'employees.view' => 'عرض الموظفين',
                'employees.create' => 'إنشاء موظفين',
                'employees.edit' => 'تعديل الموظفين',
                'employees.delete' => 'حذف الموظفين',
                'employees.attendance' => 'إدارة الحضور والانصراف',
                'employees.salary' => 'إدارة الرواتب',
            ],
            'products' => [
                'products.view' => 'عرض المنتجات',
                'products.create' => 'إنشاء منتجات',
                'products.edit' => 'تعديل المنتجات',
                'products.delete' => 'حذف المنتجات',
                'products.inventory' => 'إدارة المخزون',
                'products.categories' => 'إدارة الفئات',
            ],
            'customers' => [
                'customers.view' => 'عرض العملاء',
                'customers.create' => 'إنشاء عملاء',
                'customers.edit' => 'تعديل العملاء',
                'customers.delete' => 'حذف العملاء',
                'customers.credit' => 'إدارة الائتمان',
            ],
            'invoices' => [
                'invoices.view' => 'عرض الفواتير',
                'invoices.create' => 'إنشاء فواتير',
                'invoices.edit' => 'تعديل الفواتير',
                'invoices.delete' => 'حذف الفواتير',
                'invoices.approve' => 'الموافقة على الفواتير',
                'invoices.cancel' => 'إلغاء الفواتير',
            ],
            'payments' => [
                'payments.view' => 'عرض المدفوعات',
                'payments.create' => 'إنشاء مدفوعات',
                'payments.edit' => 'تعديل المدفوعات',
                'payments.delete' => 'حذف المدفوعات',
                'payments.refund' => 'إرجاع المدفوعات',
            ],
            'expenses' => [
                'expenses.view' => 'عرض المصروفات',
                'expenses.create' => 'إنشاء مصروفات',
                'expenses.edit' => 'تعديل المصروفات',
                'expenses.delete' => 'حذف المصروفات',
                'expenses.approve' => 'الموافقة على المصروفات',
            ],
            'reports' => [
                'reports.view' => 'عرض التقارير',
                'reports.sales' => 'تقارير المبيعات',
                'reports.inventory' => 'تقارير المخزون',
                'reports.financial' => 'التقارير المالية',
                'reports.employees' => 'تقارير الموظفين',
                'reports.export' => 'تصدير التقارير',
            ],
            'settings' => [
                'settings.view' => 'عرض الإعدادات',
                'settings.edit' => 'تعديل الإعدادات',
                'settings.business' => 'إعدادات الأعمال',
                'settings.system' => 'إعدادات النظام',
                'settings.branches' => 'إعدادات الفروع',
            ],
            'system' => [
                'system.admin' => 'إدارة النظام',
                'system.maintenance' => 'صيانة النظام',
                'system.backup' => 'النسخ الاحتياطي',
                'system.logs' => 'عرض السجلات',
                'system.users' => 'إدارة المستخدمين',
            ],
        ];
    }

    /**
     * إنشاء الصلاحيات الأساسية
     */
    public static function createBasicPermissions(): void
    {
        $basicPermissions = self::getBasicPermissions();

        foreach ($basicPermissions as $group => $permissions) {
            foreach ($permissions as $name => $description) {
                $groupAr = self::getGroupArabicName($group);
                $nameAr = $description;

                self::firstOrCreate(
                    ['name' => $name],
                    [
                        'name_ar' => $nameAr,
                        'description' => $description,
                        'description_ar' => $description,
                        'group' => $group,
                        'group_ar' => $groupAr,
                        'is_system' => true,
                        'sort_order' => 0,
                    ]
                );
            }
        }
    }

    /**
     * الحصول على اسم المجموعة بالعربية
     *
     * @param  (int|string)  $group
     *
     * @psalm-param array-key $group
     */
    private static function getGroupArabicName($group): string
    {
        $names = [
            'users' => 'المستخدمين',
            'employees' => 'الموظفين',
            'products' => 'المنتجات',
            'customers' => 'العملاء',
            'invoices' => 'الفواتير',
            'payments' => 'المدفوعات',
            'expenses' => 'المصروفات',
            'reports' => 'التقارير',
            'settings' => 'الإعدادات',
            'system' => 'النظام',
        ];

        return $names[$group] ?? $group;
    }

    /**
     * Boot method
     */
    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permission) {
            if (Auth::check()) {
                $permission->created_by = Auth::id();
            }
        });

        static::updating(function ($permission) {
            if (Auth::check()) {
                $permission->updated_by = Auth::id();
            }
        });

        static::deleting(function ($permission) {
            if (! $permission->canBeDeleted()) {
                throw new Exception('لا يمكن حذف هذه الصلاحية لأنها مستخدمة أو صلاحية نظامية');
            }
        });
    }
}
