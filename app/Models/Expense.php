<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $title_ar
 * @property numeric $amount
 * @property string $category
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $date
 * @property int $branch_id
 * @property int $user_id
 * @property int $payment_method_id
 * @property string|null $receipt_image
 * @property bool $is_approved
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $account_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereReceiptImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereTitleAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUserId($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'amount',
        'category',
        'description',
        'date',
        'branch_id',
        'user_id',
        'payment_method_id',
        'account_id',
        'receipt_image',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // العلاقة مع الفرع

    // العلاقة مع المستخدم

    // العلاقة مع طريقة الدفع

    // العلاقة مع الحساب المالي

    // العلاقة مع المستخدم المعتمد

    // نطاق المصروفات المعتمدة

    // نطاق المصروفات غير المعتمدة

    // نطاق حسب الفئة

    // نطاق حسب التاريخ

    // نطاق حسب الفرع

    // الفئات المتاحة

    // الحصول على اسم الفئة بالعربية

    // اعتماد المصروف
    public function approve(int $userId): void
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    // إلغاء اعتماد المصروف

}
