<?php

namespace App\Models\WorkDistribution;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Factory;
use App\Models\Employee;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'manager_id',
        'contact_info',
        'manager_name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'inventory')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public function factory(): HasOne
    {
        return $this->hasOne(Factory::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
