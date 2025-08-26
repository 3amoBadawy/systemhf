<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $code
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $manager_name
 * @property bool $is_active
 * @property int|null $is_main
 * @property int $sort_order
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Account> $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentMethod> $paymentMethods
 * @property-read int|null $payment_methods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'address',
        'phone',
        'email',
        'manager_name',
        'is_active',
        'sort_order',
        'notes',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'name' => 'string',
        'name_ar' => 'string',
        'code' => 'string',
        'address' => 'string',
        'address_ar' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'manager_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    // العلاقة مع العملاء

    // العلاقة مع الفواتير

    // العلاقة مع المدفوعات

    // العلاقة مع الحسابات المالية

    // العلاقة مع المصروفات

    // العلاقة مع طرق الدفع

    // العلاقة مع المستخدمين

    // العلاقة مع الموظفين

    // العلاقة مع المنتجات

    // العلاقة مع إعدادات الفرع

    // الفروع النشطة فقط

    // ترتيب الفروع

    // الحصول على الاسم بالعربية

    // التحقق من أن الفرع نشط
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope for active branches
     *
     * @phpstan-ignore-next-line
     */
    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    // إحصائيات الفرع

}
