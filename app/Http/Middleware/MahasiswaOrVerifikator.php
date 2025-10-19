<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MahasiswaOrVerifikator
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->role === 'mahasiswa' || auth()->user()->role === 'verifikator')) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}