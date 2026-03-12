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
    Schema::table('sliders', function (Blueprint $table) {
        // Mengubah kolom image_path agar boleh kosong (nullable)
        $table->string('image_path')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('sliders', function (Blueprint $table) {
        // Jika di-rollback, kembalikan menjadi wajib isi
        $table->string('image_path')->nullable(false)->change();
    });
}
};
