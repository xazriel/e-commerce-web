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
    Schema::table('size_guide_templates', function (Blueprint $table) {
        $table->string('type')->after('name'); // abaya, khimar, kids
        $table->json('data')->after('type');   // Untuk menyimpan angka-angkanya
        $table->dropColumn('content');         // Hapus kolom HTML lama
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
