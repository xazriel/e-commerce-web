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
        Schema::table('categories', function (Blueprint $table) {
            // Kita tambahkan kolom type setelah kolom name
            // Defaultnya 'standard' supaya data kategori yang sudah ada tidak error
            $table->string('type')->default('standard')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('type');
        });
    }
};