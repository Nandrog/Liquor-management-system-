<?php

namespace App\Models\WorkDistribution;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'priority',
        'deadline',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}