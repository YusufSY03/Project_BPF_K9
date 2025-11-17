<?php

namespace Database\Seeders;

use App\Models\User; // <-- Import model User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Import Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kita hanya akan membuat akun OWNER
        // Kita gunakan 'updateOrCreate' agar tidak error duplikat
        // jika seeder dijalankan berkali-kali.

        // Membuat Akun OWNER
        // Email: owner@nyamaw.com
        // Password: ownerNyamaw
        User::updateOrCreate(
            [
                'email' => 'owner@nyamaw.com', // Kunci unik untuk dicari
            ],
            [
                'name' => 'Owner Nyamaw',
                'email' => 'owner@nyamaw.com',
                'password' => Hash::make('ownerNyamaw'), // Password sesuai permintaan Anda
                'role' => 'owner', // Set rolenya
            ]
        );

        // Akun Admin akan dibuat oleh Owner via CRUD nanti
    }
}
