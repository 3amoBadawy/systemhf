<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    /**
     * تسجيل الحضور عبر كشك الويب
     */
    public function webKioskCheckIn(int $employeeId, int $shiftId, array $data = []): Attendance
    {
        try {
            // التحقق من صحة البيانات
            $this->validateCheckInData($employeeId, $shiftId);

            // تسجيل الحضور
            $attendance = Attendance::checkIn($employeeId, $shiftId, 'web_kiosk', $data);

            Log::info('تم تسجيل الحضور عبر كشك الويب', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'attendance_id' => $attendance->id,
                'ip' => request()->ip(),
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل تسجيل الحضور عبر كشك الويب', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * تسجيل الحضور عبر QR/PIN
     */
    public function qrPinCheckIn(int $employeeId, int $shiftId, string $pin, array $data = []): Attendance
    {
        try {
            // التحقق من صحة PIN
            $this->validatePin($employeeId, $pin);

            // التحقق من صحة البيانات
            $this->validateCheckInData($employeeId, $shiftId);

            // تسجيل الحضور
            $attendance = Attendance::checkIn($employeeId, $shiftId, 'qr_pin', $data);

            Log::info('تم تسجيل الحضور عبر QR/PIN', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'attendance_id' => $attendance->id,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل تسجيل الحضور عبر QR/PIN', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * تسجيل الحضور عبر GPS
     */
    public function gpsCheckIn(int $employeeId, int $shiftId, float $lat, float $lng, array $data = []): Attendance
    {
        try {
            // التحقق من صحة البيانات
            $this->validateCheckInData($employeeId, $shiftId);

            // التحقق من صحة موقع GPS
            $this->validateGPSLocation($employeeId, $lat, $lng);

            // إضافة بيانات GPS
            $data['gps_lat'] = $lat;
            $data['gps_lng'] = $lng;

            // تسجيل الحضور
            $attendance = Attendance::checkIn($employeeId, $shiftId, 'gps', $data);

            Log::info('تم تسجيل الحضور عبر GPS', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'attendance_id' => $attendance->id,
                'lat' => $lat,
                'lng' => $lng,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل تسجيل الحضور عبر GPS', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * تسجيل الحضور يدوياً
     */
    public function manualCheckIn(int $employeeId, int $shiftId, string $approverId, array $data = []): Attendance
    {
        try {
            // التحقق من صحة البيانات
            $this->validateCheckInData($employeeId, $shiftId);

            // التحقق من صلاحية المعتمد
            $this->validateApprover($approverId);

            // إضافة بيانات المعتمد
            $data['approved_by'] = $approverId;
            $data['location'] = 'Manual Entry';

            // تسجيل الحضور
            $attendance = Attendance::checkIn($employeeId, $shiftId, 'manual', $data);

            Log::info('تم تسجيل الحضور يدوياً', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'attendance_id' => $attendance->id,
                'approver_id' => $approverId,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل تسجيل الحضور يدوياً', [
                'employee_id' => $employeeId,
                'shift_id' => $shiftId,
                'approver_id' => $approverId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * تسجيل الانصراف
     */
    public function checkOut(int $attendanceId, string $method = 'web_kiosk', array $data = []): Attendance
    {
        try {
            $attendance = Attendance::findOrFail($attendanceId);

            // تسجيل الانصراف
            $attendance->checkOut($method, $data);

            Log::info('تم تسجيل الانصراف', [
                'attendance_id' => $attendanceId,
                'method' => $method,
                'employee_id' => $attendance->employee_id,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل تسجيل الانصراف', [
                'attendance_id' => $attendanceId,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * بدء استراحة
     */
    public function startBreak(int $attendanceId): Attendance
    {
        try {
            $attendance = Attendance::findOrFail($attendanceId);

            // بدء الاستراحة
            $attendance->startBreak();

            Log::info('تم بدء الاستراحة', [
                'attendance_id' => $attendanceId,
                'employee_id' => $attendance->employee_id,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل بدء الاستراحة', [
                'attendance_id' => $attendanceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * إنهاء استراحة
     */
    public function endBreak(int $attendanceId): Attendance
    {
        try {
            $attendance = Attendance::findOrFail($attendanceId);

            // إنهاء الاستراحة
            $attendance->endBreak();

            Log::info('تم إنهاء الاستراحة', [
                'attendance_id' => $attendanceId,
                'employee_id' => $attendance->employee_id,
            ]);

            return $attendance;
        } catch (\Exception $e) {
            Log::error('فشل إنهاء الاستراحة', [
                'attendance_id' => $attendanceId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * التحقق من صحة بيانات تسجيل الحضور
     */
    private function validateCheckInData(int $employeeId, int $shiftId): void
    {
        // التحقق من وجود الموظف
        $employee = Employee::findOrFail($employeeId);
        if (! $employee->isActive()) {
            throw new \Exception('الموظف غير نشط');
        }

        // التحقق من وجود الشِفت
        $shift = Shift::findOrFail($shiftId);
        if (! $shift->isActive()) {
            throw new \Exception('الشِفت غير نشط');
        }

        // التحقق من عدم وجود تسجيل سابق اليوم
        $existing = Attendance::where('employee_id', $employeeId)
            ->where('date', now()->toDateString())
            ->first();

        if ($existing) {
            throw new \Exception('تم تسجيل الحضور مسبقاً لهذا اليوم');
        }
    }

    /**
     * التحقق من صحة PIN
     */
    private function validatePin(int $employeeId, string $pin): void
    {
        // يمكن إضافة منطق التحقق من PIN هنا
        // مثال: التحقق من قاعدة البيانات أو نظام خارجي
        if (strlen($pin) < 4) {
            throw new \Exception('PIN غير صحيح');
        }
    }

    /**
     * التحقق من صحة موقع GPS
     */
    private function validateGPSLocation(int $employeeId, float $lat, float $lng): void
    {
        $employee = Employee::findOrFail($employeeId);
        $branch = $employee->branch;

        if (! $branch) {
            throw new \Exception('الموظف غير مرتبط بفرع');
        }

        // يمكن إضافة منطق التحقق من موقع الفرع هنا
        // مثال: التحقق من أن GPS ضمن نطاق الفرع
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            throw new \Exception('إحداثيات GPS غير صحيحة');
        }
    }

    /**
     * التحقق من صلاحية المعتمد
     */
    private function validateApprover(string $approverId): void
    {
        // يمكن إضافة منطق التحقق من صلاحية المعتمد هنا
        // مثال: التحقق من أن المعتمد له صلاحية الموافقة
        if (empty($approverId)) {
            throw new \Exception('معرف المعتمد مطلوب');
        }
    }

    /**
     * تسجيل حضور موظف
     */
    public function recordAttendance(int $employeeId, string $type, ?string $notes = null): Attendance
    {
        $employee = Employee::findOrFail($employeeId);

        $attendance = Attendance::create([
            'employee_id' => $employeeId,
            'type' => $type,
            'check_in_time' => $type === 'check_in' ? now() : null,
            'check_out_time' => $type === 'check_out' ? now() : null,
            'notes' => $notes,
            'branch_id' => $employee->branch_id,
            'user_id' => auth()->id(),
        ]);

        return $attendance;
    }

    /**
     * تحديث حضور موظف
     */
    public function updateAttendance(int $attendanceId, array $data): Attendance
    {
        $attendance = Attendance::findOrFail($attendanceId);

        $attendance->update($data);

        return $attendance;
    }

    /**
     * حذف حضور موظف
     */
    public function deleteAttendance(int $attendanceId): bool
    {
        $attendance = Attendance::findOrFail($attendanceId);

        return $attendance->delete();
    }

    /**
     * الحصول على تقرير الحضور
     */
    public function getAttendanceReport(int $employeeId, string $startDate, string $endDate): array
    {
        $employee = Employee::findOrFail($employeeId);
        $shift = Shift::findOrFail($employee->shift_id);

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereBetween('check_in_time', [$startDate, $endDate])
            ->get();

        $report = [
            'employee' => $employee,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'total_days' => $attendance->count(),
            'present_days' => $attendance->where('type', 'check_in')->count(),
            'absent_days' => 0,
            'late_minutes' => 0,
            'overtime_minutes' => 0,
        ];

        foreach ($attendance as $record) {
            if ($record->type === 'check_in') {
                $checkInTime = \Carbon\Carbon::parse($record->check_in_time);
                $shiftStartTime = \Carbon\Carbon::parse($shift->start_time);

                if ($checkInTime->gt($shiftStartTime)) {
                    $report['late_minutes'] += $checkInTime->diffInMinutes($shiftStartTime);
                }
            }
        }

        return $report;
    }

    /**
     * الحصول على إحصائيات الحضور
     */
    public function getAttendanceStatistics(int $employeeId, string $period): array
    {
        $employee = Employee::findOrFail($employeeId);

        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('check_in_time', 'like', $period.'%')
            ->get();

        $statistics = [
            'total_days' => $attendance->count(),
            'present_days' => $attendance->where('type', 'check_in')->count(),
            'absent_days' => 0,
            'late_days' => 0,
            'overtime_days' => 0,
        ];

        return $statistics;
    }

    /**
     * الحصول على تقرير الحضور لموظف
     */
    public function getEmployeeAttendanceReport(int $employeeId, string $startDate, string $endDate): array
    {
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['shift'])
            ->orderBy('date')
            ->get();

        $report = [
            'employee_id' => $employeeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $attendance->count(),
            'present_days' => $attendance->where('status', 'present')->count(),
            'absent_days' => $attendance->where('status', 'absent')->count(),
            'late_days' => $attendance->where('late_minutes', '>', 0)->count(),
            'total_late_minutes' => $attendance->sum('late_minutes'),
            'total_overtime_minutes' => $attendance->sum('overtime_minutes'),
            'total_work_hours' => $attendance->sum('work_hours'),
            'attendance_details' => $attendance,
        ];

        return $report;
    }

    /**
     * الحصول على تقرير الحضور لفرع
     */
    public function getBranchAttendanceReport(int $branchId, string $date): array
    {
        $attendance = Attendance::whereHas('employee', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
            ->where('date', $date)
            ->with(['employee', 'shift'])
            ->get();

        $report = [
            'branch_id' => $branchId,
            'date' => $date,
            'total_employees' => $attendance->unique('employee_id')->count(),
            'present_employees' => $attendance->where('status', 'present')->unique('employee_id')->count(),
            'absent_employees' => $attendance->where('status', 'absent')->unique('employee_id')->count(),
            'late_employees' => $attendance->where('late_minutes', '>', 0)->unique('employee_id')->count(),
            'total_late_minutes' => $attendance->sum('late_minutes'),
            'total_overtime_minutes' => $attendance->sum('overtime_minutes'),
            'attendance_details' => $attendance,
        ];

        return $report;
    }

    /**
     * تصدير تقرير الحضور إلى CSV
     */
    public function exportAttendanceToCSV(array $attendanceData, ?string $filename = null): string
    {
        if (! $filename) {
            'attendance_report_'.now()->format('Y-m-d_H-i-s').'.csv';
        }

        $headers = [
            'Employee ID',
            'Employee Name',
            'Date',
            'Check In',
            'Check Out',
            'Status',
            'Late Minutes',
            'Overtime Minutes',
            'Work Hours',
            'Method',
        ];

        $csvContent = implode(',', $headers)."\n";

        foreach ($attendanceData as $record) {
            $row = [
                $record->employee->employee_number,
                $record->employee->name,
                $record->date->format('Y-m-d'),
                $record->check_in_time ? $record->check_in_time->format('H:i:s') : '',
                $record->check_out_time ? $record->check_out_time->format('H:i:s') : '',
                $record->status_text,
                $record->late_minutes,
                $record->overtime_minutes,
                $record->work_hours,
                $record->check_in_method_text,
            ];

            $csvContent .= implode(',', $row)."\n";
        }

        return $csvContent;
    }
}
