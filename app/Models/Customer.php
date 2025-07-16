<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
protected $fillable = ['user_id', 'city','state','phone_number','location'];

public function user()
{
    return $this->belongsTo(User::class);
}


}
