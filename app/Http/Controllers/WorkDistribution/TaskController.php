<?php

namespace App\Http\Controllers\WorkDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkDistribution\Task;
use App\Models\WorkDistribution\Employee;
use App\Models\StockMovement; // ✅ Add this

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['employee', 'stockMovement'])->get(); // eager load stockMovement too
        return view('work-distribution.task-list', compact('tasks'));
    }

    // Show task form
    public function create()
    {
        $employees = Employee::with('warehouse')->get(); // helpful for dropdown labels
        $stockMovements = StockMovement::with('product')->get(); // pass to view

        return view('work-distribution.create-task', compact('employees', 'stockMovements'));
    }

    // Save the task
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string',
            'priority' => 'required|string',
            'deadline' => 'required|date',
            'stock_movement_id' => 'nullable|exists:stock_movements,id',
        ]);

        Task::create([
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => 'pending',
            'stock_movement_id' => $request->stock_movement_id, // ✅ store link
        ]);

        return redirect()->back()->with('success', 'Task assigned successfully!');
    }
}