<?php

namespace App\Http\Controllers\WorkDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkDistribution\Employee;
use App\Models\WorkDistribution\ShiftSchedule;

class ShiftController extends Controller
{
    public function index()
{
    $shifts = ShiftSchedule::with('employee')->get();
    return view('work-distribution.shift-list', compact('shifts'));
}

    public function create()
    {
        $employees = Employee::all();
        return view('work-distribution.create-shift', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'break_hours' => 'nullable|numeric|min:0',
        ]);

        ShiftSchedule::create($request->all());

        return redirect()->back()->with('success', 'Shift scheduled!');
    }
}
