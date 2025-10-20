<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

class CheckMenuPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userRole = $user->getRole(); // 'admin', 'verifikator', atau 'mahasiswa'
        $currentRoute = $request->route()->getName();

        // Cek apakah route ini terdaftar di menu
        $menu = Menu::where('route', $currentRoute)->first();

        // Kalau tidak ada menu untuk route ini, boleh lanjut (opsional tergantung kebutuhanmu)
        if (!$menu) {
            return $next($request);
        }

        // Ambil permissions dari field JSON `permissions`
        $permissions = $menu->permissions ?? [];

        // Jika kosong → berarti semua boleh akses
        if (empty($permissions) || in_array($userRole, $permissions)) {
            return $next($request);
        }

        // Jika tidak punya akses
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
