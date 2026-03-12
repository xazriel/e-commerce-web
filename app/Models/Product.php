<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock'];

    // Relasi ke Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Banyak Gambar (Multi-image)
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}