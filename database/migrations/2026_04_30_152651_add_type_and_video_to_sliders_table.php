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
        // 'image' untuk gambar/gif, kita tambah kolom type untuk deteksi
        $table->string('type')->default('image')->after('title'); 
    });
}

public function down(): void
{
    Schema::table('sliders', function (Blueprint $table) {
        $table->dropColumn(['type']);
    });
}
};
