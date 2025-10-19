<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    public function getMenuForUser()
    {
        $user = Auth::user();
        $userRole = $user->getRole(); // 'admin', 'verifikator', atau 'user'

        // Ambil menu parent yang aktif dan sesuai permission
        $menus = Menu::with(['children' => function($query) use ($userRole) {
                $query->where('is_active', true) // Hanya children yang aktif
                      ->where(function($q) use ($userRole) {
                          $q->whereJsonContains('permissions', $userRole)
                            ->orWhereNull('permissions')
                            ->orWhere('permissions', '[]');
                      })
                      ->orderBy('order');
            }])
            ->where('is_active', true) // Hanya parent yang aktif
            ->whereNull('parent_id')
            ->where(function($query) use ($userRole) {
                $query->whereJsonContains('permissions', $userRole)
                      ->orWhereNull('permissions')
                      ->orWhere('permissions', '[]');
            })
            ->orderBy('order')
            ->get();

        return $menus;
    }

    public function isMenuActive($menu, $currentRoute)
    {
        if ($menu->route && $menu->route == $currentRoute) {
            return true;
        }

        if ($menu->children) {
            foreach ($menu->children as $child) {
                if ($this->isMenuActive($child, $currentRoute)) {
                    return true;
                }
            }
        }

        return false;
    }
}