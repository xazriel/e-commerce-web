<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    // Mass assignment untuk kolom-kolom baru (Opsi A)
    protected $fillable = [
        'category_id', 
        'size_guide_template_id', // Tambahkan ini
        'name', 
        'slug', 
        'description', 
        'price',
        'is_preorder',
        'is_limited',
        'release_date',
        'custom_tag',
    ];

    /**
     * Casting data agar tipe data konsisten.
     * release_date diubah jadi objek datetime agar bisa pakai ->format() atau ->diff()
     * is_preorder & is_limited diubah jadi boolean.
     */
    protected $casts = [
        'release_date' => 'datetime',
        'is_preorder' => 'boolean',
        'is_limited' => 'boolean',
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
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Accessor untuk menghitung total stok dari semua varian.
     * Kamu bisa panggil dengan $product->total_stock
     */
    public function getTotalStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }

    /**
     * Helper untuk cek apakah produk masih baru (kurang dari 7 hari)
     * Digunakan untuk tag 'New Arrival' otomatis.
     */
    public function getIsNewArrivalAttribute(): bool
    {
        return $this->created_at->diffInDays() < 7;
    }

    public function sizeGuide(): BelongsTo
{
    return $this->belongsTo(SizeGuideTemplate::class, 'size_guide_template_id');
}

}