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
       Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id'); // Primary Key
            $table->unsignedBigInteger('order_id'); // Foreign Key
            $table->dateTime('payment_date');
            $table->string('payment_method'); // Contoh: QRIS, Transfer, dll
            $table->string('payment_status')->default('Waiting for Payment'); // Waiting, Success, Failed
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            // Foreign Key ke tabel orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
