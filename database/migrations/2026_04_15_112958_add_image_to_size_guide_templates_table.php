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
    Schema::table('size_guide_templates', function (Blueprint $table) {
        $table->string('image')->nullable()->after('name');
        // biarkan kolom 'content' tetap ada jika ingin buat jaga-jaga, 
        // atau boleh di-comment jika ingin benar-benar ganti ke gambar.
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('size_guide_templates', function (Blueprint $table) {
            //
        });
    }
};
