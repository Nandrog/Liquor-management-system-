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

    // It is NOT recommended to use a custom primary key name unless absolutely necessary.
    // The Laravel convention is simply 'id'. If you change this back to 'id' in your
    // migration and model, you won't need to specify keys in your relationships.
    protected $primaryKey = 'warehouse_id';

    protected $fillable = ['name', 'location', 'capacity', 'manager_id', 'contact_info', 'manager_name'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'stock_levels', 'warehouse_id', 'product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * This is the corrected relationship.
     * We explicitly tell Eloquent what the foreign and local keys are.
     */
    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class, 'warehouse_id', 'warehouse_id');
    }

    public function factory(): HasOne
    {
        return $this->hasOne(Factory::class, 'warehouse_id', 'warehouse_id');
    }

    public function employees(): HasMany
    {
        // This will also likely need explicit keys if the 'employees' table
        // has a 'warehouse_id' and not a 'warehouse_warehouse_id'
        return $this->hasMany(Employee::class, 'warehouse_id', 'warehouse_id');
    }
}