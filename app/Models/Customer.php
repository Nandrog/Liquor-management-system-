<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
protected $fillable = ['user_id', 'company_name', 'phone_number'];

public function user()
{
    return $this->belongsTo(User::class);
}


}
