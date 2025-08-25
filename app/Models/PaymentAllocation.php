<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $payment_id
 * @property int $invoice_id
 * @property float $amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentAllocation whereAmount($value)
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
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
