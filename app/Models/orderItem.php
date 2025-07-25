<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


    class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function sale()
{
    return $this->belongsTo(\App\Models\Sale::class, 'order_id');
}



}
