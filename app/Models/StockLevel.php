<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
    ];

    /**
     * Get the product that this stock level belongs to.
     */
    public function product(): BelongsTo
    {
        // Assuming products table primary key is 'id'
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Get the warehouse that this stock level belongs to.
     */
    public function warehouse(): BelongsTo
    {
        // Explicitly state foreign key and custom primary key on warehouses table
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
