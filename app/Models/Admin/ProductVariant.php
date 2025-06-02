<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name']; // e.g., Color, Size

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
