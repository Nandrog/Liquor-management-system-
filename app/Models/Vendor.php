<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['name','contact'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}
