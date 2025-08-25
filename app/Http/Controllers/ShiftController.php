<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function update(Request $request, Shift $shift): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'break_start' => 'required|date_format:H:i:s',
            'break_end' => 'required|date_format:H:i:s|after:break_start',
            'late_threshold_minutes' => 'required|integer|min:0',
            'overtime_threshold_minutes' => 'required|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $shift->update($request->all());

        return redirect()->route('shifts.index')->with('success', 'تم تحديث الشِفت بنجاح');
    }
}
