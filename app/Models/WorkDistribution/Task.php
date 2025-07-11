<?php

namespace App\Models\WorkDistribution;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement; // ✅ Correct namespace


class Task extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'priority',
        'deadline',
        'status',
        'stock_movement_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function stockMovement() {
        return 
        $this->belongsTo(StockMovement::class);
    }
}
