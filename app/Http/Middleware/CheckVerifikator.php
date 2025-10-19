<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVerifikator
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isVerifikator()) {
            abort(403, 'Unauthorized. Hanya Verifikator yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}