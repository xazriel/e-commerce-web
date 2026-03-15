<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    // Penting agar mass-assignment tidak error
    protected $fillable = ['product_id', 'color', 'size', 'stock', 'additional_price'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}