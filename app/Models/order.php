<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus; 

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'supplier_id',
        'customer_id',
        'type',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'type' => \App\Enums\OrderType::class,
        'status' => \App\Enums\OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'delivered_at'   => 'datetime',
        'paid_at'        => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);

    }

    public function recipientSupplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function scopeSupplierOrders($query)
    {
        return $query->where('type', \App\Enums\OrderType::SUPPLIER_ORDER);
    }
     public function products()
    {
         return $this->belongsToMany(Product::class, 'order_items') // <-- Add table name
                ->withPivot('quantity', 'price');
    }
}
