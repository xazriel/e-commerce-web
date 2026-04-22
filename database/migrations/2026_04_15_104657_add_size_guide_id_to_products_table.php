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
    Schema::table('products', function (Blueprint $table) {
        // Menghubungkan produk ke template
        $table->foreignId('size_guide_template_id')->nullable()->constrained()->onDelete('set null');
        // Tambahan kolom jika ingin menulis size guide manual tanpa template
        $table->text('custom_size_guide')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
