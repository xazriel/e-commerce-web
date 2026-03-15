<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel utama untuk menyimpan daftar tag
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama tag: 'New Arrival', 'Pre-Order', dll.
            $table->string('slug')->unique(); // Untuk keperluan URL atau filter
            $table->timestamps();
        });

        // Tabel pivot untuk menghubungkan Produk dengan Tag (Many-to-Many)
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('tags');
    }
};