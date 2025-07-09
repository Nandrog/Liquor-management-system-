<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRun extends Model
{
    /** @use HasFactory<\Database\Factories\ProductionRunFactory> */
    use HasFactory;

    protected $fillable = [
    'user_id',
    'factory_id',
    'product_id',
    'quantity_produced',
    'cost_of_materials',
    'completed_at',
];

   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
