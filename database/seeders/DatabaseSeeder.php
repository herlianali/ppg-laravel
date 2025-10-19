<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder sesuai urutan yang diinginkan
        $this->call([
            MenuSeeder::class,
            UserSeeder::class,
        ]);
    }
}
