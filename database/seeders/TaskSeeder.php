<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkDistribution\Task;
use App\Models\WorkDistribution\Employee;
use App\Models\StockMovement;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Routine Check',
            'Inventory Count',
            'Shipment Preparation',
            'Stock Reconciliation',
            'Receiving Goods',
            'Dispatch Order',
            'Warehouse Cleaning',
            'Expiry Check',
            'Packing and Labelling',
            'Returns Processing',
        ];

        $statuses = ['pending', 'in_progress', 'completed','overdue'];
        $priorities = ['Low', 'Medium', 'High'];

        $employeeIds = Employee::pluck('id');
        $stockMovementIds = StockMovement::pluck('id');

        if ($employeeIds->isEmpty()) {
            $this->command->warn('No employees found. TaskSeeder skipped.');
            return;
        }

        foreach (range(1, 30) as $i) {
            Task::create([
                'type'              => $types[array_rand($types)],
                'status'            => $statuses[array_rand($statuses)],
                'priority'          => $priorities[array_rand($priorities)],
                'deadline'          => now()->addDays(rand(1, 10)),
                'employee_id'       => $employeeIds->random(),
                'stock_movement_id' => $stockMovementIds->isNotEmpty() && rand(0, 1)
                                        ? $stockMovementIds->random()
                                        : null,
            ]);
        }
    }
}
