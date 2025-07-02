<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



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

public function orders()
{
    return $this->hasMany(Order::class);
}

public function supplierProfile()
{
    return $this->hasOne(Supplier::class);
}

public function customerProfile()
{
    return $this->hasOne(Customer::class);
}

public function productionPlant(): BelongsTo
{
    return $this->belongsTo(Factory::class, 'factory_id');
}
}


