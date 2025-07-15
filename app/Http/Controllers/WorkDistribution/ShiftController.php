<?php

namespace App\Http\Controllers\WorkDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkDistribution\ShiftSchedule;
use App\Models\WorkDistribution\Employee;
use App\Notifications\ShiftScheduled;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = ShiftSchedule::with(['employee', 'warehouse'])->get();
        return view('work-distribution.shift-list', compact('shifts'));
    }

    // Show create shift form
    public function create()
    {
        $employees = Employee::with('warehouse')->get();
        return view('work-distribution.create-shift', compact('employees'));
    }

    // Store new shift & send notification
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string',
        ]);

        $shift = ShiftSchedule::create([
            'employee_id' => $request->employee_id,
            'warehouse_id' => $request->warehouse_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'notes' => $request->notes,
        ]);

        $employee = $shift->employee;
        if ($employee && $employee->email) {
            $employee->notify(new ShiftScheduled($shift));
        }

        return redirect()->route('shifts.index')->with('success', 'Shift scheduled and employee notified!');
    }
}