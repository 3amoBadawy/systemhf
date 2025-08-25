<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $account_id
 * @property string $type
 * @property numeric $amount
 * @property string $description
 * @property string|null $reference_type
 * @property int|null $reference_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $branch_id
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type', // debit, credit
        'amount',
        'description',
        'reference_type', // invoice, payment, expense, etc.
        'reference_id',
        'date',
        'branch_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // العلاقة مع الحساب

    // العلاقة مع الفرع

    // العلاقة مع المستخدم

    // نطاق حسب النوع

    // نطاق حسب التاريخ

    // نطاق حسب الفرع

    // نطاق حسب الحساب

    // الأنواع المتاحة

    // الحصول على اسم النوع بالعربية

    // الحصول على المبلغ المطلق

    // تحديث رصيد الحساب
    #[\Override]
    public static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            $transaction->account->updateBalance();
        });

        static::updated(function ($transaction) {
            $transaction->account->updateBalance();
        });

        static::deleted(function ($transaction) {
            $transaction->account->updateBalance();
        });
    }
}
