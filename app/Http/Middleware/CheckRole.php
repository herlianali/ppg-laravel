<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek jika user tidak login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Debug: uncomment baris berikut untuk melihat role
        // dd($user->role, $roles);

        // Cek jika user memiliki salah satu role yang diizinkan
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Jika tidak memiliki role yang diizinkan
        abort(403, 'Unauthorized action. Role Anda (' . ($user->role ?? 'tidak ada') . ') tidak memiliki akses ke halaman ini. Dibutuhkan role: ' . implode(', ', $roles));
    }
}
