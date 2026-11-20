<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Cek berdasarkan email agar tidak duplikat jika seeder dijalankan ulang
            [
                'name' => 'Administrator',
                'role' => 'admin', // Hapus baris ini jika tidak memakai kolom role
                'phone' => '081234567890',
                'address' => 'Kantor Pusat',
                'password' => Hash::make('tahta123'), // Ganti dengan password yang kuat
                'email_verified_at' => now(),
            ]
        );
    }
}