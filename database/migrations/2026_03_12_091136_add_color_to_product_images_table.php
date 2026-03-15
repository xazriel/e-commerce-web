<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('product_images', function (Blueprint $table) {
        $table->string('color')->nullable()->after('product_id'); // Menyimpan nama warna, misal: 'Black'
    });
    }
    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('product_images', function (Blueprint $table) {
        $table->dropColumn('color'); // Ini untuk menghapus kolom jika kamu melakukan migrate:rollback
    });
}
};
