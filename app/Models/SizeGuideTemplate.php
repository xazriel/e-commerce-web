<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGuideTemplate extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * (Opsional, karena Laravel otomatis menebak jamak dari nama model)
     */
    protected $table = 'size_guide_templates';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * Sangat penting agar data dari form Admin bisa tersimpan.
     */
    protected $fillable = [
    'name',
    'image',
    'type',
    'data', // Tambahkan ini kalau belum ada
    ];

    /**
     * Relasi ke Model Product.
     * Satu Template bisa digunakan oleh banyak Produk (One-to-Many).
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'size_guide_template_id');
    }
}