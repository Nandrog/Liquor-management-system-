<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'capacity', 'manager_id'];

    /**
     * The products that are in stock in this warehouse.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'inventory')
                    ->withPivot('quantity') // Makes the 'quantity' from the pivot table accessible
                    ->withTimestamps();
    }

     public function stockLevels(): HasMany // <<< THIS IS THE METHOD THAT WAS MISSING
    {
        return $this->hasMany(StockLevel::class);
    }

    public function factory(): HasOne
  {
    return $this->hasOne(Factory::class);
  }
}