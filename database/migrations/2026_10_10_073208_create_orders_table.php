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
    $table->string('order_number', 50)->unique(); // Dikirim sebagai order_id ke Midtrans
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

    $table->decimal('subtotal', 12, 2);
    $table->decimal('discount', 12, 2)->default(0.00);
    $table->decimal('total_amount', 12, 2);

    // Pakai string nullable agar tidak error jika metode baru dipilih di Snap Midtrans
    $table->string('payment_method', 50)->nullable();
    $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'expired'])->default('unpaid');
    $table->enum('order_status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('pending');

    // Kolom khusus integrasi Midtrans
    $table->string('snap_token')->nullable();
    $table->text('payment_url')->nullable();
    $table->string('midtrans_transaction_id')->nullable();
    $table->timestamp('paid_at')->nullable();

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
