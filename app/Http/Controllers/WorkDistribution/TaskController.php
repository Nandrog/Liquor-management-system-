<?php

namespace App\Http\Controllers\WorkDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkDistribution\Task;
use App\Models\WorkDistribution\Employee;
use App\Models\StockMovement;
use App\Notifications\TaskAssigned;

class TaskController extends Controller
{
    public function index()
    {
        // ✅ Eager-load employee, stockMovement, and the nested order too
        $tasks = Task::with(['employee', 'stockMovement.order'])->get();

        return view('work-distribution.task-list', compact('tasks'));
    }
    public function destroy(Task $task)
{
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
}

    // Show create task form
    public function create()
    {
        $employees = Employee::with('warehouse')->get();
        // ✅ Eager-load product AND order so your select shows both
        $stockMovements = StockMovement::with(['product', 'order'])->get();

        return view('work-distribution.create-task', compact('employees', 'stockMovements'));
    }

    // Save task + notify
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string',
            'priority' => 'required|string',
            'deadline' => 'required|date',
            'stock_movement_id' => 'nullable|exists:stock_movements,id',
        ]);

        $task = Task::create([
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => 'pending',
            'stock_movement_id' => $request->stock_movement_id,
        ]);

        $employee = $task->employee;

        if ($employee && $employee->email) {
            $employee->notify(new TaskAssigned($task));
        }

        return redirect()->back()->with('success', 'Task assigned successfully & employee notified!');
    }
}