<?php

namespace App\Models\WorkDistribution;

use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
        'break_hours',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
}