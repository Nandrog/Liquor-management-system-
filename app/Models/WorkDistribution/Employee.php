<?php

namespace App\Models\WorkDistribution;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'role',
        'email',
        'skillset',
        'warehouse_id',
    ];

    public function warehouse()
    {
        // Explicitly specify foreign key and owner key since Warehouse uses warehouse_id
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function shifts()
    {
        return $this->hasMany(ShiftSchedule::class);
    }
}
