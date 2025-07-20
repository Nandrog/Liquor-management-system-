<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    protected $fillable = ['user_id','name','contact'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }
}
