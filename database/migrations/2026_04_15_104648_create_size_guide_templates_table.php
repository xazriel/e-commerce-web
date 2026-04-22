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
        Schema::create('size_guide_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama template, misal: "Abaya Lyra Standard"
            $table->text('content'); // Berisi HTML tabel size guide
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_guide_templates');
    }
};
