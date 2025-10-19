<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'route' => 'home.index',
                'parent_id' => null,
                'order' => 1,
                'permissions' => ['admin', 'verifikator', 'mahasiswa']
            ],

            [
                'name' => 'Data Mahasiswa',
                'icon' => 'fa-solid fa-database',
                'route' => 'datamhs.index',
                'parent_id' => null,
                'order' => 2,
                'permissions' => ['admin', 'verifikator']
            ],
            [
                'name' => 'Data Master',
                'icon' => 'fa-solid fa-server',
                'route' => 'masterdata.index',
                'parent_id' => null,
                'order' => 3,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Setting',
                'icon' => 'fa-solid fa-gears',
                'route' => 'setting.index',
                'parent_id' => null,
                'order' => 10,
                'permissions' => ['admin']
            ],

            [
                'name' => 'Verifikasi',
                'icon' => 'fa-solid fa-check-circle',
                'route' => 'verifikasi.index',
                'parent_id' => null,
                'order' => 4,
                'permissions' => ['verifikator']
            ],

            [
                'name' => 'Lapor Diri',
                'icon' => 'fa-solid fa-file-lines',
                'route' => null,
                'parent_id' => null,
                'order' => 5,
                'permissions' => ['mahasiswa']
            ],
            [
                'name' => 'Buat Lapor Diri',
                'icon' => 'fa-solid fa-plus',
                'route' => 'lapor.create',
                'parent_id' => 6,
                'order' => 1,
                'permissions' => ['mahasiswa']
            ],
            [
                'name' => 'Data Saya',
                'icon' => 'fa-solid fa-list',
                'route' => 'lapor.my.index',
                'parent_id' => 6,
                'order' => 2,
                'permissions' => ['mahasiswa']
            ],

            [
                'name' => 'PPL',
                'icon' => 'fa-solid fa-file',
                'route' => 'ppl.index',
                'parent_id' => null,
                'order' => 6,
                'permissions' => ['admin', 'verifikator', 'mahasiswa']
            ],
            [
                'name' => 'Mata Kuliah',
                'icon' => 'fa-solid fa-book',
                'route' => 'matkur.index',
                'parent_id' => null,
                'order' => 7,
                'permissions' => ['admin', 'verifikator', 'mahasiswa']
            ],
            [
                'name' => 'Laporan',
                'icon' => 'fa-solid fa-chart-bar',
                'route' => 'laporan.index',
                'parent_id' => null,
                'order' => 8,
                'permissions' => ['admin', 'verifikator']
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}