<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Order;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'employee_id',
        'factory_id',
    ];

    /**
     * Virtual attribute: full name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->firstname} {$this->lastname}"
        );
    }

    /**
     * Attributes hidden from JSON responses.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts for attribute conversion.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function customerProfile(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function supplierProfile(): HasOne
    {
        return $this->hasOne(Supplier::class, 'user_id');
    }

    public function suppliedPurchases(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Order::class,
            \App\Models\Supplier::class,
            'user_id',      // Foreign key on Supplier table...
            'supplier_id',  // Foreign key on Order table...
            'id',           // Local key on User table...
            'id'            // Local key on Supplier table...
        );
    }    

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(Vendor::class, 'user_id', 'id');
    }

    public function productionPlant(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function customer(): HasOne
{
    return $this->hasOne(Customer::class);
}

}
