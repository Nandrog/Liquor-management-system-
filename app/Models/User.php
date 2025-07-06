<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname', // Add this
        'lastname',  // Add this
        'username',
        'email',
        'password',
        'employee_id', // Add this
        'factory_id',
    ];
     /**
     * Get the user's full name.
     * This creates a "virtual" attribute called "name".
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
   
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



public function sentMessage(){
    return $this->hasMany(Message::class,'sender_id');
}
public function receivedMessage(){
    return $this->hasMany(Message::class,'receiver_id');
}
public function customerProfile()
{
    return $this->hasOne(Customer::class);
}

public function productionPlant(): BelongsTo
{
    return $this->belongsTo(Factory::class, 'factory_id');
}

/*public function suppliedPurchases(): HasMany
{
    // The foreign key on the 'purchases' table is 'supplier_id'.
    // The local key on the 'users' table is 'id'.
   // return $this->hasMany(Purchase::class, 'supplier_id', 'id');
}*/
  // ... existing properties

    public function supplier()
    {
        return $this->hasOne(\App\Models\Supplier::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}


