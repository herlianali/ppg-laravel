<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BidangStudiSeeder extends Seeder
{
    public function run()
    {
        DB::table('bidang_studi')->insert([
            [
                'name' => 'Bahasa Inggris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ilmu Pengetahuan Alam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan Guru Anak Usia Dini',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan Guru Sekolah Dasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
