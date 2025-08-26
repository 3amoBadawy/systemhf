<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string|null $phone2
 * @property string $country
 * @property string $governorate
 * @property string $address
 * @property string $customer_type
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read float $total_invoiced
 * @property-read float $total_paid
 * @property-read float $remaining_balance
 * @property-read string $payment_status
 * @property-read string $payment_status_text
 * @property-read int $unread_notifications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereGovernorate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'phone2',
        'country',
        'governorate',
        'address',
        'customer_type',
        'notes',
        'is_active',
        'branch_id',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'is_active' => 'boolean',
        'name' => 'string',
        'phone' => 'string',
        'phone2' => 'string',
        'country' => 'string',
        'governorate' => 'string',
        'address' => 'string',
        'customer_type' => 'string',
        'notes' => 'string',
        'branch_id' => 'integer',
    ];

    /**
     * علاقة الفرع
     */
    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // العلاقة مع الفواتير
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // العلاقة مع المدفوعات
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // العلاقة مع توزيعات الدفع

    // العلاقة مع الإشعارات
    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CustomerNotification::class);
    }

    // الحصول على إجمالي المدفوع

    // الحصول على إجمالي الفواتير

    // الحصول على الرصيد المتبقي

    // التحقق من أن العميل مدين

    // الحصول على نوع العميل بالعربية

    // البحث في العملاء

    // العملاء حسب النوع

    // العملاء حسب الفرع

    // الحصول على حالة الدفع

    // الحصول على حالة الدفع بالعربية

    // الحصول على عدد الإشعارات غير المقروءة

    // البحث السريع بالاسم

    // البحث برقم التلفون

    // البحث بالمنطقة

    // العملاء النشطين فقط

    /**
     * الحصول على إجمالي الفواتير
     */
    public function getTotalInvoicedAttribute(): float
    {
        return $this->invoices()->sum('total');
    }

    /**
     * الحصول على إجمالي المدفوع
     */
    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'confirmed')->sum('amount');
    }

    /**
     * الحصول على الرصيد المتبقي
     */
    public function getRemainingBalanceAttribute(): float
    {
        return $this->total_invoiced - $this->total_paid;
    }

    /**
     * الحصول على حالة الدفع
     */
    public function getPaymentStatusAttribute(): string
    {
        if ($this->remaining_balance <= 0) {
            return 'paid';
        }

        if ($this->remaining_balance <= 1000) {
            return 'low_balance';
        }

        return 'outstanding';
    }

    /**
     * الحصول على حالة الدفع بالعربية
     */
    public function getPaymentStatusTextAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'مدفوع بالكامل',
            'low_balance' => 'رصيد منخفض',
            'outstanding' => 'رصيد مرتفع',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * التحقق من وجود رصيد مرتفع
     */
    public function hasOutstandingBalance(): bool
    {
        return $this->remaining_balance > 1000;
    }
}
