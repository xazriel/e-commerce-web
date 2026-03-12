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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('order_number')->unique(); // Contoh: FRH-20240312-001
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Data Nominal
        $table->decimal('total_amount', 12, 2);
        $table->decimal('shipping_cost', 12, 2)->default(0);
        $table->decimal('grand_total', 12, 2);
        
        // Status & Pembayaran
        $table->string('status')->default('pending'); // pending, success, expired, shipped, completed
        $table->string('payment_method')->nullable(); // VA_BCA, QRIS, CC
        $table->string('payment_token')->nullable();  // Untuk menyimpan token dari Midtrans/Xendit
        $table->timestamp('payment_deadline');        // Ini untuk fitur 2 jam tadi
        
        // Pengiriman (JNE)
        $table->string('shipping_service')->nullable(); // JNE OKE, REG, atau YES
        $table->string('tracking_number')->nullable();  // Resi JNE
        
        // Data Penerima (Seringkali berbeda dengan data akun)
        $table->string('receiver_name');
        $table->string('receiver_phone');
        $table->text('receiver_address');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
