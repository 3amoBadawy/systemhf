<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $payment_id
 * @property int|null $invoice_id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property numeric|null $amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereType($value)
 *
 * @mefinal thod static \Illuminate\Database\Eloquent\Builder<static>|CustomerNotification whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CustomerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'payment_id',
        'invoice_id',
        'type',
        'title',
        'message',
        'amount',
        'status',
        'read_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'read_at' => 'datetime',
    ];

    // العلاقة مع العميل

    // العلاقة مع الدفعة

    // العلاقة مع الفاتورة

    // الحصول على نوع الإشعار بالعربية

    // الحصول على حالة الإشعار بالعربية

    // تحديد الإشعار كمقروء

    // التحقق من أن الإشعار مقروء

    // التحقق من أن الإشعار غير مقروء

    // البحث في الإشعارات

    // الإشعارات حسب النوع

    // الإشعارات حسب الحالة

    // الإشعارات غير المقروءة

    // الإشعارات المقروءة

}
