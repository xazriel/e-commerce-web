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
    const TYPE_KHIBAN   = 'khiban';
    const TYPE_DEFECT   = 'defect';

    protected $fillable = [
        'name', 
        'slug', 
        'type',
        'custom_sizes'
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
     * Helper untuk cek apakah kategori ini bertipe Khiban.
     * Penggunaan: if($category->isKhiban()) { ... }
     */
    public function isKhiban(): bool
    {
        return $this->type === self::TYPE_KHIBAN;
    }

    /**
     * Helper untuk cek apakah kategori ini bertipe Defect.
     * Penggunaan: if($category->isDefect()) { ... }
     */
    public function isDefect(): bool
    {
        return $this->type === self::TYPE_DEFECT;
    }

    /**
     * Relasi ke Product (Asumsi nama modelnya Product)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}