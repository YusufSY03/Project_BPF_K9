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
        Schema::table('users', function (Blueprint $table) {
            // Kita tambahkan kolom 'role'
            // Enum membatasi isinya hanya boleh 'admin', 'owner', atau 'user'
            // Defaultnya adalah 'user'
            $table->enum('role', ['admin', 'owner', 'user'])
                  ->default('user')
                  ->after('password'); // Posisinya setelah kolom password
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ini untuk membatalkan (rollback) migrasi
            $table->dropColumn('role');
        });
    }
};