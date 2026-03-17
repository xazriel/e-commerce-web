<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $group) {
            // Menambahkan field untuk kebutuhan pengiriman & Komerce
            $group->string('phone')->nullable()->after('email');
            $group->text('address')->nullable()->after('phone');
            
            // destination_id untuk menyimpan ID Kecamatan dari API Komerce
            $group->string('destination_id')->nullable()->after('address');
            
            // destination_name untuk menyimpan label teks (Contoh: "Cinere, Depok")
            $group->string('destination_name')->nullable()->after('destination_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $group) {
            $group->dropColumn(['phone', 'address', 'destination_id', 'destination_name']);
        });
    }
};