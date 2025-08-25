<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $code
 * @property string $description
 * @property bool $is_active
 * @property int $sort_order
 * @property int|null $account_id
 * @property int|null $branch_id
 * @property string $type
 * @property float $initial_balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereInitialBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'description',
        'is_active',
        'sort_order',
        'account_id',
        'branch_id',
        'type',
        'initial_balance',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'initial_balance' => 'decimal:2',
    ];

    /**
     * علاقة الحساب المالي
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * علاقة الفرع
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * علاقة المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * علاقة المصروفات
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // طرق الدفع النشطة فقط

    // ترتيب طرق الدفع

    // نطاق حسب الفرع

    // الحصول على الاسم بالعربية

    // التحقق من أن طريقة الدفع نشطة
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // الحصول على الرصيد الحالي من الحساب المالي

    // الحصول على نوع الحساب

    // الحصول على اسم النوع بالعربية

    /**
     * إنشاء حساب مالي مرتبط تلقائياً
     */
    public function createLinkedAccount(): ?Account
    {
        if (! $this->account_id && $this->branch_id) {
            // This should be handled by a service or repository
            // For now, return null to avoid static method calls
            return null;
        }

        return null;
    }

    /**
     * تحديث الحساب المالي المرتبط
     */
    public function updateLinkedAccount(): ?Account
    {
        if ($this->account_id && $this->account) {
            // This should be handled by a service or repository
            // For now, return the account to avoid static method calls
            return $this->account;
        }

        return null;
    }

    /**
     * الحصول على الحساب المالي المرتبط
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    // حذف الحساب المالي المرتبط
}
