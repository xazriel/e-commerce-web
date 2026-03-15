<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    // Hilangkan 'stock', 'colors', dan 'sizes' karena data ini sudah pindah ke tabel variants
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'description', 
        'price'
    ];

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

    /**
     * Relasi ke Varian Produk (Stok per warna & ukuran)
     * Ini yang akan memperbaiki error 'variants' not found tadi.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relasi ke Tag/Label (Many-to-Many)
     * Memungkinkan produk punya label seperti 'New Arrival', 'Pre-Order', dll.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Accessor untuk menghitung total stok dari semua varian.
     * Jadi kamu tetap bisa memanggil $product->total_stock di view.
     */
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }
}