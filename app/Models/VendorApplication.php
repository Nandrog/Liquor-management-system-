<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    protected $fillable = [
        'vendor_name',
        'contact_email',
        'pdf_path',
    ];
}
