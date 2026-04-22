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
            // Kita tambahkan setelah kolom price agar rapi di database
            $table->boolean('is_preorder')->default(false)->after('price');
            $table->boolean('is_limited')->default(false)->after('is_preorder');
            $table->timestamp('release_date')->nullable()->after('is_limited');
            $table->string('custom_tag')->nullable()->after('release_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_preorder', 'is_limited', 'release_date', 'custom_tag']);
        });
    }
};