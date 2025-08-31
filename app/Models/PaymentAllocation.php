<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $invoice_id
 * @property float $allocated_amount
 * @property float $remaining_balance
 * @property string $allocation_status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereAllocatedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereRemainingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereAllocationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class PaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'allocated_amount',
        'remaining_balance',
        'allocation_status',
        'notes',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'payment_id' => 'integer',
        'invoice_id' => 'integer',
        'allocation_status' => 'string',
        'notes' => 'string',
    ];

    /**
     * علاقة الدفع
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * علاقة الفاتورة
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
