<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These should match the columns in your products table migration.
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'type',
        'unit_price',
        'unit_of_measure',
        // 'stock', // REMOVED: Stock quantity is managed in the 'stock_levels' table.
       // 'category', // Kept for simplicity, can be removed if relying only on category_id
        'reorder_level',
        'category_id', // ADDED: This is needed for mass assignment.
        //'vendor_id',   // ADDED: This is needed for mass assignment.
        //'supplier_id',
    ];

    /**
     * Get the warehouses that have this product in stock.
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'stock_levels')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Get the current stock levels for this product across all warehouses.
     * This is a useful direct relationship to the pivot model.
     */
    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    /**
     * Get the supplier that provides this product (if it's a raw material).
     * THIS IS THE MISSING RELATIONSHIP THAT CAUSED YOUR ERROR.
     */
    public function supplier(): BelongsTo
    {
        // A product's 'supplier_id' column points to the 'id' on the 'users' table.
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the vendor that sells this product (if it's a finished good).
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Get the category for the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the order items associated with this product. (Keeping this from your version)
     */
    public function orderItems(): HasMany
    {
        // Assuming you have a general OrderItem model for later use.
        // If not, you can remove this or specify SaleItem/PurchaseItem.
        return $this->hasMany(OrderItem::class);
    }
}