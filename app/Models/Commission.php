<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon $commission_date
 * @property numeric $sales_amount
 * @property numeric $commission_rate
 * @property numeric $commission_amount
 * @property string $status
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property int|null $paid_by
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string|null $payment_method
 * @property string|null $notes
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereCommissionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereCommissionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereSalesAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereStatus($valfinal ue)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commission whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'invoice_id',
        'commission_date',
        'sales_amount',
        'commission_rate',
        'commission_amount',
        'status', // pending, approved, paid, rejected
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'payment_method',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'commission_date' => 'date',
        'sales_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // العلاقة مع الموظف

    // العلاقة مع الفاتورة

    // العلاقة مع الموظف المعتمد

    // العلاقة مع الموظف الدافع

    // العمولات حسب الموظف

    // العمولات حسب التاريخ

    // العمولات حسب الشهر

    // العمولات حسب الحالة

    // العمولات المعتمدة

    // العمولات المدفوعة

    // العمولات النشطة

    // إنشاء عمولة جديدة

    // اعتماد العمولة
    public function approve($approverId, $notes = null): static
    {
        if ($this->status !== 'pending') {
            throw new \Exception('لا يمكن اعتماد العمولة في هذه الحالة');
        }

        $this->status = 'approved';
        $this->approved_by = $approverId;
        $this->approved_at = now();
        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();

        return $this;
    }

    // رفض العمولة

    // دفع العمولة
    public function pay($payerId, $paymentMethod, $notes = null): static
    {
        if ($this->status !== 'approved') {
            throw new \Exception('يجب اعتماد العمولة قبل الدفع');
        }

        $this->status = 'paid';
        $this->paid_by = $payerId;
        $this->paid_at = now();
        $this->payment_method = $paymentMethod;
        if ($notes) {
            $this->notes = $notes;
        }

        $this->save();

        return $this;
    }

    // الحصول على حالة العمولة

    // الحصول على طريقة الدفع

    // التحقق من إمكانية الاعتماد

    // التحقق من إمكانية الدفع

    // حساب العمولة الإجمالية للموظف في شهر معين

}
