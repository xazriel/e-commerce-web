<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek apakah kolom destination_id belum ada
            if (!Schema::hasColumn('orders', 'destination_id')) {
                $table->string('destination_id')->after('receiver_address')->nullable();
            }
            
            // Cek apakah kolom courier_name belum ada
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->after('destination_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['destination_id', 'courier_name']);
        });
    }
};