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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 150);
            $table->string('brand', 80);
            $table->enum('size', ['S', 'M', 'L', 'XL', 'XXL']);
            $table->integer('length')->nullable(); // Panjang baju (cm)
            $table->integer('width')->nullable();  // Lebar baju / P2P (cm)
            $table->string('material', 100)->nullable();
            $table->string('condition_rating', 10)->nullable(); // Contoh: 95%
            $table->text('defect_notes')->nullable();          // Catatan minus
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('image_url', 255)->nullable();
            $table->enum('status', ['in_stock', 'sold_out'])->default('in_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
