<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@ppg.app',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verifikator',
                'email' => 'verifikator@ppg.app',
                'password' => Hash::make('password123'),
                'role' => 'verifikator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa',
                'email' => 'mahasiswa@ppg.app',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
