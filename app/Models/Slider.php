<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data ke kolom berikut
    protected $fillable = [
        'image_path',
        'image_mobile_path',
        'title',
        'order',
        'is_active'
    ];
}