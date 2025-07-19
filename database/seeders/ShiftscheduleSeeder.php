<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkDistribution\ShiftSchedule;
use App\Models\WorkDistribution\Employee;
use Illuminate\Support\Carbon;

class ShiftScheduleSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::pluck('id');

        foreach ($employees as $employeeId) {
            // Random number of shifts between 1 and 5
            $numShifts = rand(1, 5);

            for ($i = 0; $i < $numShifts; $i++) {
                // Each shift is on a different day, recent days going backwards
                $date = Carbon::now()->subDays($i)->startOfDay();

                ShiftSchedule::create([
                    'employee_id' => $employeeId,
                    'start_time' => $date->copy()->setTime(8, 0, 0),
                    'end_time' => $date->copy()->setTime(17, 0, 0),
                    'break_hours' => rand(5, 20) / 10,  // 0.5 to 2 hours break
                ]);
            }
        }
    }
}
