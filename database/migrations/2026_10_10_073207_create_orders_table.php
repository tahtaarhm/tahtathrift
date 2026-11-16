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
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['QRIS', 'Bank BCA', 'Bank Mandiri', 'COD']);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('paid');
            $table->enum('order_status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('processing');
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
