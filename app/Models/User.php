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

    public function suppliedPurchases(): HasMany
    {
        // This tells Eloquent: "Look at the 'purchases' table. Find all records
        // where the 'supplier_id' column on that table matches the 'id'
        // of this User model."
        return $this->hasMany(Order::class, 'supplier_id', 'id');
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function productionPlant(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }


}
