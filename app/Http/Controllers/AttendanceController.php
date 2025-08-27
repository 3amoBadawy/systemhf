<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * عرض قائمة الحضور والانصراف
     */
    public function index(): View
    {
        $attendances = Attendance::with(['employee', 'branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attendance.index', compact('attendances'));
    }

    /**
     * تقرير الحضور والانصراف
     */
    public function report(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');

        $query = Attendance::with(['employee', 'branch'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(50);

        $employees = Employee::where('status', 'active')->get();

        return view('attendance.report', compact('attendances', 'employees', 'startDate', 'endDate', 'branchId', 'employeeId'));
    }

    /**
     * تصدير تقرير الحضور
     */
    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $branchId = $request->get('branch_id');
        $employeeId = $request->get('employee_id');

        $query = Attendance::with(['employee', 'branch'])
            ->whereBetween('date', [$startDate, $endDate]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // إنشاء ملف Excel أو CSV
        $filename = 'attendance_report_'.now()->format('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($attendances) {
            $file = fopen('php://output', 'w');

            // رأس الجدول
            fputcsv($file, ['التاريخ', 'الموظف', 'الفرع', 'وقت الحضور', 'وقت الانصراف', 'الحالة', 'الملاحظات']);

            // البيانات
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->date,
                    $attendance->employee->name_ar ?? $attendance->employee->name,
                    $attendance->branch->name_ar ?? $attendance->branch->name,
                    $attendance->check_in_time,
                    $attendance->check_out_time,
                    $attendance->status,
                    $attendance->notes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * تسجيل الحضور
     */
    public function checkIn(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'method' => 'required|in:web_kiosk,qr_pin,gps,manual',
            'branch_id' => 'required|exists:branches,id',
            'notes' => 'nullable|string',
        ]);

        try {
            // استخدام الطريقة المناسبة حسب نوع التسجيل
            $method = $request->input('method');
            $employeeId = $request->input('employee_id');
            $branchId = $request->input('branch_id');
            $notes = $request->input('notes');

            // افتراضي استخدام web_kiosk إذا لم يتم تحديد shift_id
            $shiftId = 1; // يمكن تغيير هذا حسب الحاجة

            if ($method === 'web_kiosk') {
                $attendance = $this->attendanceService->webKioskCheckIn($employeeId, $shiftId, [
                    'branch_id' => $branchId,
                    'notes' => $notes,
                ]);
            } else {
                // إنشاء تسجيل حضور بسيط
                $attendance = Attendance::create([
                    'employee_id' => $employeeId,
                    'branch_id' => $branchId,
                    'shift_id' => $shiftId,
                    'date' => now()->toDateString(),
                    'check_in_time' => now(),
                    'status' => 'present',
                    'method' => $method,
                    'notes' => $notes,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الحضور بنجاح',
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * تسجيل الانصراف
     */
    public function checkOut(Request $request): JsonResponse
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'method' => 'required|in:web_kiosk,qr_pin,gps,manual',
        ]);

        try {
            $attendance = $this->attendanceService->checkOut(
                $request->input('attendance_id'),
                $request->input('method')
            );

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الانصراف بنجاح',
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * بدء استراحة
     */
    public function startBreak(Request $request): JsonResponse
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        try {
            $attendance = $this->attendanceService->startBreak($request->input('attendance_id'));

            return response()->json([
                'success' => true,
                'message' => 'تم بدء الاستراحة بنجاح',
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * إنهاء استراحة
     */
    public function endBreak(Request $request): JsonResponse
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        try {
            $attendance = $this->attendanceService->endBreak($request->input('attendance_id'));

            return response()->json([
                'success' => true,
                'message' => 'تم إنهاء الاستراحة بنجاح',
                'attendance' => $attendance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * واجهة الكيوسك
     */
    public function kiosk(): View
    {
        return view('attendance.kiosk');
    }
}
