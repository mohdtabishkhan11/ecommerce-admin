<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'options'];

    protected $casts = [
        'options' => 'array', // Stores variation options as JSON
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
