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
            // Menghubungkan ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 50)->default('pending'); // pending, paid, completed, cancelled
            $table->string('payment_method', 50)->default('transfer'); // transfer, cash
            $table->string('payment_proof', 255)->nullable(); // URL gambar bukti bayar
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};