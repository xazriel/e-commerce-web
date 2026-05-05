<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('destination_id')->nullable()->after('city_code');
            $table->string('zip_code', 10)->nullable()->after('destination_id');
            $table->string('address_label')->nullable()->after('zip_code');
        });
    }

    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(['destination_id', 'zip_code', 'address_label']);
        });
    }
};