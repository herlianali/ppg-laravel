<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            // ========== DASHBOARD (SEMUA ROLE) ==========
            [
                'name' => 'Dashboard',
                'icon' => 'fa-solid fa-house',
                'route' => 'home.index',
                'parent_id' => null,
                'order' => 1,
                'permissions' => ['mahasiswa', 'admin', 'verifikator']
            ],

            // ========== MAHASISWA ==========
            [
                'name' => 'Lapor Diri',
                'icon' => 'fa-solid fa-file-lines',
                'route' => null,
                'parent_id' => null,
                'order' => 2,
                'permissions' => ['mahasiswa']
            ],
            [
                'name' => 'Data Saya',
                'icon' => 'fa-solid fa-list',
                'route' => 'lapor.my.index',
                'parent_id' => 2, // akan diupdate setelah seeding jika pakai ID dinamis
                'order' => 1,
                'permissions' => ['mahasiswa']
            ],

            // ========== VERIFIKATOR ==========
            [
                'name' => 'Verifikasi Lapor Diri',
                'icon' => 'fa-solid fa-check-circle',
                'route' => 'verifikasi.index',
                'parent_id' => null,
                'order' => 3,
                'permissions' => ['verifikator', 'admin']
            ],

            // ========== ADMINISTRATOR ==========
            [
                'name' => 'Data Master',
                'icon' => 'fa-solid fa-server',
                'route' => 'masterdata.index',
                'parent_id' => null,
                'order' => 4,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Lapor Diri Verval ID',
                'icon' => 'fa-solid fa-id-card',
                'route' => 'lapor.admin.index',
                'parent_id' => null,
                'order' => 5,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Menu Management',
                'icon' => 'fa-solid fa-bars',
                'route' => 'admin.menus.index',
                'parent_id' => null,
                'order' => 6,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Laporan',
                'icon' => 'fa-solid fa-chart-bar',
                'route' => 'laporan.index',
                'parent_id' => null,
                'order' => 7,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Bidang Studi Management',
                'icon' => 'fa-solid fa-chart-bar',
                'route' => 'admin.bidang-studi.index',
                'parent_id' => null,
                'order' => 8,
                'permissions' => ['admin']
            ],
            [
                'name' => 'Mahasiswa',
                'icon' => 'fa-solid fa-user-plus',
                'route' => 'admin.mahasiswa.index',
                'parent_id' => null,
                'order' => 8,
                'permissions' => ['admin']
            ],
        ];

        // simpan semua menu
        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        /**
         * Catatan:
         * Jika `parent_id` perlu dihubungkan dinamis (bukan angka statis),
         * kamu bisa ambil menu "Lapor Diri" yang sudah dibuat, lalu update "Data Saya".
         * Misalnya:
         *
         * $parent = Menu::where('name', 'Lapor Diri')->first();
         * Menu::where('name', 'Data Saya')->update(['parent_id' => $parent->id]);
         */
    }
}
