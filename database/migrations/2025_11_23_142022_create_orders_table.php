<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->decimal('total_amount', 10, 2);
            $table->decimal('shipping_price', 10, 2)->default(0); // Tambahan: Ongkir
            $table->string('status', 50)->default('pending');
            $table->string('payment_method', 50)->default('transfer');
            $table->string('payment_proof', 255)->nullable();
            
            // Tambahan: Koordinat Lokasi
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};