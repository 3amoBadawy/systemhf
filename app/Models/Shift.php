<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $name_ar
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property \Illuminate\Support\Carbon|null $break_start
 * @property \Illuminate\Support\Carbon|null $break_end
 * @property int $late_threshold_minutes
 * @property int $overtime_threshold_minutes
 * @property bool $is_active
 * @property int $sort_order
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @property-read mixed $break_duration
 * @property-read mixed $duration
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereBreakEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereBreakStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereLateThresholdMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereOvertimeThresholdMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereStartTimefinal ($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_active',
        'branch_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'is_active' => 'boolean',
        'branch_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    // العلاقة مع الفرع

    // العلاقة مع الموظفين

    // العلاقة مع الحضور

    // نطاق النوبات النشطة

    // نطاق حسب الفرع

    // الحصول على اسم النوبة بالعربية

    // التحقق من أن النوبة نشطة
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // حساب دقائق التأخير
    public function calculateLateMinutes(\DateTime $checkInTime): int
    {
        $shiftStart = $this->start_time;
        $checkIn = clone $checkInTime;
        $checkIn->setDate($shiftStart->year, $shiftStart->month, $shiftStart->day);

        if ($checkIn <= $shiftStart) {
            return 0;
        }

        $diff = $checkIn->diff($shiftStart);

        return ($diff->h * 60) + $diff->i;
    }

    // حساب دقائق العمل الإضافي
    public function calculateOvertimeMinutes(\DateTime $checkOutTime): int
    {
        $shiftEnd = $this->end_time;
        $checkOut = clone $checkOutTime;
        $checkOut->setDate($shiftEnd->year, $shiftEnd->month, $shiftEnd->day);

        if ($checkOut <= $shiftEnd) {
            return 0;
        }

        $diff = $checkOut->diff($shiftEnd);

        return ($diff->h * 60) + $diff->i;
    }
}
