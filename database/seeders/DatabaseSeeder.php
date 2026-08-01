<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin / Pemilik
        User::create([
            'name' => 'Rezzy Taufik Ramadhan',
            'email' => 'admin@dewicatering.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // User Staf Gudang
        User::create([
            'name' => 'Staf Gudang',
            'email' => 'staf@dewicatering.com',
            'password' => Hash::make('password123'),
            'role' => 'staf',
        ]);
    }
}