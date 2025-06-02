<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationOption extends Model
{
    use HasFactory;
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

}
