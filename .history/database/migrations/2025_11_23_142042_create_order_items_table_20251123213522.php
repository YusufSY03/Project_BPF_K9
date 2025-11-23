<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel orders
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // Hubungkan ke tabel menu_items
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');
            
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Harga saat dibeli (untuk history jika harga menu berubah)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};