<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'image',
    ];

    /**
     * Many-to-Many relationship with Category (for multi-category support)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * One-to-Many relationship with ProductVariation
     * Each variation has SKU, price, stock, and option values (as JSON)
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    /**
     * One-to-Many relationship with ProductVariant
     * Typically represents option names like "Color", "Size" (optional if used)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    /**
     * One-to-Many relationship with ProductImage
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * (Optional) Belongs to a primary Category (used if you store a single `category_id`)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    
}
