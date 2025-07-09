<?php

namespace App\Http\Controllers\WorkDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkDistribution\Task;
use App\Models\WorkDistribution\Employee;

class TaskController extends Controller
{
 public function index()
{
    $tasks = Task::with('employee')->get();
    return view('work-distribution.task-list', compact('tasks'));
}

    // Show task form
    public function create()
    {
        $employees = Employee::all(); // for the select dropdown
        return view('work-distribution.create-task', compact('employees'));
    }

    // Save the task
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string',
            'priority' => 'required|string',
            'deadline' => 'required|date',
        ]);

        Task::create([
            'employee_id' => $request->employee_id,
            'type'        => $request->type,
            'priority'    => $request->priority,
            'deadline'    => $request->deadline,
            'status'      => 'pending',
        ]);

        return redirect()->back()->with('success', 'Task assigned successfully!');
    }
}
