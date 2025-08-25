<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $invoice_number
 * @property int $customer_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $sale_date
 * @property string|null $delivery_date
 * @property string $contract_number
 * @property string|null $contract_image
 * @property numeric $subtotal
 * @property numeric $discount
 * @property string $tax_rate
 * @property string $tax_amount
 * @property numeric $total
 * @property string $status
 * @property string $payment_status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $branch_id
 * @property int|null $employee_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 *
 * @method static Builder<static>|Invoice newModelQuery()
 * @method static Builder<static>|Invoice newQuery()
 * @method static Builder<static>|Invoice query()
 * @method static Builder<static>|Invoice whereBranchId($value)
 * @method static Builder<static>|Invoice whereContractImage($value)
 * @method static Builder<static>|Invoice whereContractNumber($value)
 * @method static Builder<static>|Invoice whereCreatedAt($value)
 * @method static Builder<static>|Invoice whereCustomerId($value)
 * @method static Builder<static>|Invoice whereDeliveryDate($value)
 * @method static Builder<static>|Invoice whereDiscount($value)
 * @method static Builder<static>|Invoice whereEmployeeId($value)
 * @method static Builder<static>|Invoice whereId($value)
 * @method static Builder<static>|Invoice whereInvoiceNumber($value)
 * @method static Builder<static>|Invoice whereNotes($value)
 * @method static Builder<static>|Invoice wherePaymentStatus($value)
 * @method static Builder<static>|Invoice whereSaleDate($value)
 * @method static Builder<static>|Invoice whereStatus($value)
 * @method static Builder<static>|Invoice whereSubtotal($value)
 * @method static Builder<static>|Invoice whereTaxAmount($value)
 * @method static Builder<static>|Invoice whereTaxRate($value)
 * @method static Builder<static>|Invoice whereTotal($value)
 * @method static Builder<static>|Invoice whereUpdatedAt($value)
 * @method static Builder<static>|Invoice whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'user_id',
        'branch_id',
        'sale_date',
        'contract_number',
        'contract_image',
        'subtotal',
        'discount',
        'total',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * علاقة العميل
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * علاقة المستخدم
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة الفرع
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * علاقة الموظف
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * علاقة المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * علاقة عناصر الفاتورة
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
