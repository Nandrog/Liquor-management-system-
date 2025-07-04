<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // <<< ADD THIS ENTIRE PROPERTY
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'moved_at',
        'notes',
    ];

    protected $casts = [ // <<< ADD THIS ENTIRE PROPERTY
        'moved_at' => 'datetime',
    ];

    // It's also good practice to define the relationships
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
}