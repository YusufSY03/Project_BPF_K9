<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Relasi ke Order (Satu pesanan dinilai satu kali)
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Relasi ke User (Siapa yang menilai)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Bintang (1 sampai 5)
            $table->unsignedTinyInteger('rating_stars'); 
            
            // Komentar (Boleh kosong jika user malas ngetik)
            $table->text('comment')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};