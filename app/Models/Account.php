<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $name_ar
 * @property string $type
 * @property numeric $balance
 * @property string|null $description
 * @property bool $is_active
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expense> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereType($final value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'type', // income, expense, asset, liability
        'balance',
        'description',
        'is_active',
        'branch_id',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // العلاقة مع الفرع

    // العلاقة مع طريقة الدفع (إذا كان الحساب مرتبط بطريقة دفع)

    // العلاقة مع المعاملات (إيرادات ومصروفات)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // المدفوعات المرتبطة بالحساب

    // المصروفات المرتبطة بالحساب

    // الحصول على الرصيد الحالي

    // الحصول على الرصيد حسب الفرع
    public function getBalanceByBranch($branchId)
    {
        $debits = $this->transactions()
            ->where('type', 'debit')
            ->where('branch_id', $branchId)
            ->sum('amount');

        $credits = $this->transactions()
            ->where('type', 'credit')
            ->where('branch_id', $branchId)
            ->sum('amount');

        if ($this->type === 'income' || $this->type === 'liability') {
            return $credits - $debits;
        } else {
            return $debits - $credits;
        }
    }

    // الحصول على إحصائيات حسب الفرع

    // الحصول على إحصائيات جميع الفروع
    /**
     * @return array[]
     *
     * @psalm-return array<array{branch_name: mixed, branch_id: mixed, balance: mixed, transaction_count: mixed, last_transaction: mixed}>
     */
    public function getAllBranchesStats(): array
    {
        $branches = \App\Models\Branch::active()->get();
        $stats = [];

        foreach ($branches as $branch) {
            $stats[$branch->id] = [
                'branch_name' => $branch->name,
                'branch_id' => $branch->id,
                'balance' => $this->getBalanceByBranch($branch->id),
                'transaction_count' => $this->transactions()->where('branch_id', $branch->id)->count(),
                'last_transaction' => $this->transactions()->where('branch_id', $branch->id)->latest()->first(),
            ];
        }

        return $stats;
    }

    // الحصول على الرصيد الإجمالي من جميع الفروع
    public function getTotalBalanceFromAllBranches()
    {
        $totalBalance = 0;
        $branches = \App\Models\Branch::active()->get();

        foreach ($branches as $branch) {
            $totalBalance += $this->getBalanceByBranch($branch->id);
        }

        return $totalBalance;
    }

    // الحصول على التحويلات حسب الفرع

    // الحصول على التحويلات من جميع الفروع

    // التحقق من أن العميل مدين

    // الحصول على حالة الدفع

    // الحصول على حالة الدفع بالعربية

    // الحصول على عدد الإشعارات غير المقروءة

    // نطاق الحسابات النشطة

    // نطاق حسب النوع

    // نطاق حسب الفرع

    // نطاق حسب نوع الحساب

    // الأنواع المتاحة

    // الحصول على اسم النوع بالعربية

    // تحديث الرصيد
    public function updateBalance(): void
    {
        $this->balance = $this->current_balance;
        $this->save();
    }

    // الحصول على ملخص الحساب

}
