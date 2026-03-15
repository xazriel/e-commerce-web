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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel products, jika produk dihapus varian ikut terhapus
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            
            // Menyimpan nama warna, misal: 'Midnight', 'Parchment'
            $table->string('color'); 
            
            // Menyimpan ukuran, misal: 'S', 'M', 'L', 'XL'
            $table->string('size'); 
            
            // Stok spesifik untuk kombinasi warna & ukuran tersebut
            $table->integer('stock')->default(0); 
            
            // Harga opsional jika ingin membedakan harga per ukuran (biasanya untuk size besar)
            $table->decimal('additional_price', 15, 2)->default(0)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};