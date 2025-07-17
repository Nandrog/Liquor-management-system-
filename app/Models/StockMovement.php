<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\WorkDistribution\Employee;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'moved_at',
        'notes',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function order()
{
    return $this->belongsTo(Order::class);
}
public function employee()
{
    return $this->belongsTo(Employee::class);
}
}