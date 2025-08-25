<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $shift_id
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $check_in_time
 * @property \Illuminate\Support\Carbon|null $check_out_time
 * @property string $check_in_method
 * @property string|null $check_out_method
 * @property string|null $check_in_location
 * @property string|null $check_out_location
 * @property string|null $check_in_ip
 * @property string|null $check_out_ip
 * @property numeric|null $check_in_gps_lat
 * @property numeric|null $check_in_gps_lng
 * @property numeric|null $check_out_gps_lat
 * @property numeric|null $check_out_gps_lng
 * @property int $late_minutes
 * @property int $overtime_minutes
 * @property \Illuminate\Support\Carbon|null $break_start_time
 * @property \Illuminate\Support\Carbon|null $break_end_time
 * @property int $total_break_minutes
 * @property numeric $work_hours
 * @property string $status
 * @property string|null $notes
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereBreakEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereBreakStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInGpsLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInGpsLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutGpsLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutGpsLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCheckOutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereLateMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereOvertimeMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereTotalBreakMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereUpdatedAt($valfinal ue)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attendance whereWorkHours($value)
 *
 * @mixin \Eloquent
 */
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'check_in_time',
        'check_out_time',
        'check_in_method', // web_kiosk, qr_pin, gps, manual
        'check_out_method',
        'check_in_location',
        'check_out_location',
        'check_in_ip',
        'check_out_ip',
        'check_in_gps_lat',
        'check_in_gps_lng',
        'check_out_gps_lat',
        'check_out_gps_lng',
        'late_minutes',
        'overtime_minutes',
        'break_start_time',
        'break_end_time',
        'total_break_minutes',
        'work_hours',
        'status', // present, absent, late, half_day, leave
        'notes',
        'approved_by',
        'approved_at',
        'is_verified',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
        'total_break_minutes' => 'integer',
        'work_hours' => 'decimal:2',
        'check_in_gps_lat' => 'decimal:8',
        'check_in_gps_lng' => 'decimal:8',
        'check_out_gps_lat' => 'decimal:8',
        'check_out_gps_lng' => 'decimal:8',
        'is_verified' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // العلاقة مع الموظف

    // العلاقة مع الشِفت

    // العلاقة مع الموظف المعتمد

    // الحضور حسب التاريخ

    // الحضور حسب الموظف

    // الحضور حسب الفرع

    // الحضور حسب الشهر

    // الحضور حسب الحالة

    // الحضور المؤكد

    // الحضور المعتمد

    // تسجيل الحضور
    public static function checkIn(int $employeeId, int $shiftId, string $method = 'web_kiosk', array $data = []): self
    {
        $today = now()->toDateString();

        // التحقق من عدم وجود تسجيل سابق اليوم
        $existing = self::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            throw new \Exception('تم تسجيل الحضور مسبقاً لهذا اليوم');
        }

        $attendance = new self;
        $attendance->employee_id = $employeeId;
        $attendance->shift_id = $shiftId;
        $attendance->date = $today;
        $attendance->check_in_time = now();
        $attendance->check_in_method = $method;
        $attendance->check_in_ip = request()->ip();
        $attendance->status = 'present';

        // إضافة بيانات GPS إذا كانت متوفرة
        if (isset($data['gps_lat']) && isset($data['gps_lng'])) {
            $attendance->check_in_gps_lat = $data['gps_lat'];
            $attendance->check_in_gps_lng = $data['gps_lng'];
            $attendance->check_in_location = 'GPS';
        }

        // إضافة الموقع إذا كان متوفر
        if (isset($data['location'])) {
            $attendance->check_in_location = $data['location'];
        }

        // حساب التأخير
        $shift = Shift::find($shiftId);
        if ($shift) {
            $attendance->late_minutes = $shift->calculateLateMinutes($attendance->check_in_time);
        }

        $attendance->save();

        return $attendance;
    }

    // تسجيل الانصراف
    public function checkOut($method = 'web_kiosk', $data = []): static
    {
        if ($this->check_out_time) {
            throw new \Exception('تم تسجيل الانصراف مسبقاً');
        }

        $this->check_out_time = now();
        $this->check_out_method = $method;
        $this->check_out_ip = request()->ip();

        // إضافة بيانات GPS إذا كانت متوفرة
        if (isset($data['gps_lat']) && isset($data['gps_lng'])) {
            $this->check_out_gps_lat = $data['gps_lat'];
            $this->check_out_gps_lng = $data['gps_lng'];
            $this->check_out_location = 'GPS';
        }

        // إضافة الموقع إذا كان متوفر
        if (isset($data['location'])) {
            $this->check_out_location = $data['location'];
        }

        // حساب الوقت الإضافي
        if ($this->shift) {
            $this->overtime_minutes = $this->shift->calculateOvertimeMinutes($this->check_out_time);
        }

        // حساب ساعات العمل
        $this->work_hours = $this->check_in_time->diffInMinutes($this->check_out_time) / 60;
        $this->work_hours = round($this->work_hours - ($this->total_break_minutes / 60), 2);

        $this->save();

        return $this;
    }

    // تسجيل استراحة
    public function startBreak(): static
    {
        if ($this->break_start_time) {
            throw new \Exception('تم بدء الاستراحة مسبقاً');
        }

        $this->break_start_time = now();
        $this->save();

        return $this;
    }

    // إنهاء استراحة
    public function endBreak(): static
    {
        if (! $this->break_start_time) {
            throw new \Exception('لم يتم بدء الاستراحة');
        }

        $this->break_end_time = now();
        $this->total_break_minutes = $this->break_start_time->diffInMinutes($this->break_end_time);

        // إعادة حساب ساعات العمل
        if ($this->check_out_time) {
            $this->work_hours = $this->check_in_time->diffInMinutes($this->check_out_time) / 60;
            $this->work_hours = round($this->work_hours - ($this->total_break_minutes / 60), 2);
        }

        $this->save();

        return $this;
    }

    // التحقق من صحة GPS

    // حساب المسافة بين نقطتين GPS
    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371000; // متر

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    // الحصول على حالة الحضور

    // الحصول على طريقة التسجيل

    // التحقق من أن الحضور مكتمل

    // حساب إجمالي الوقت الفعلي

}
