<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
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
}
