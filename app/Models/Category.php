<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // Mendefinisikan konstanta agar lebih rapi dan konsisten
    const TYPE_STANDARD = 'standard';
    const TYPE_KIDS     = 'kids';

    protected $fillable = [
        'name', 
        'slug', 
        'type'
    ];

    /**
     * Boot function untuk otomatis membuat slug dari name jika belum ada.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Helper untuk cek apakah kategori ini bertipe Kids.
     * Penggunaan: if($category->isKids()) { ... }
     */
    public function isKids(): bool
    {
        return $this->type === self::TYPE_KIDS;
    }

    /**
     * Relasi ke Product (Asumsi nama modelnya Product)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}