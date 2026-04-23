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
    Schema::create('user_addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('label')->nullable(); 
    $table->string('recipient_name');
    $table->string('phone');
    $table->text('address'); 
    $table->string('province_name')->nullable(); // Tambah ini
    $table->string('city_name'); 
    $table->string('district_name')->nullable(); // Tambah ini (PENTING buat JNE)
    $table->string('city_code')->nullable(); 
    $table->string('postal_code', 10);
    $table->boolean('is_default')->default(false); 
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
