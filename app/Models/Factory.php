<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'warehouse_id'];

    /**
     * Get the warehouse associated with the factory.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    /**
     * Get all of the manufacturers (users) that work at this factory.
     */
    public function manufacturers(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
