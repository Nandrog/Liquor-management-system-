<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = ['output_product_id', 'name', 'description'];

    public function outputProduct()
    {
        return $this->belongsTo(Product::class, 'output_product_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Product::class, 'recipe_materials', 'recipe_id', 'material_product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}