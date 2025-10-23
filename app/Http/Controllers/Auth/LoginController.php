<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showLoginFormAdmin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Deteksi apakah login mahasiswa atau admin berdasarkan input
        $isMahasiswaLogin = $request->has('simpkb_id');
        if ($isMahasiswaLogin) {
            // Validasi login mahasiswa
            $request->validate([
                'simpkb_id' => 'required',
                'password' => 'required',
            ]);

            $credentials = [
                'simpkb_id' => $request->simpkb_id,
                'password' => $request->password,
            ];
        } else {
            // Validasi login admin/verifikator
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin' || $user->role === 'verifikator') {
                return redirect()->route('home.index');
            } elseif ($user->role === 'mahasiswa') {
                return redirect()->route('lapor.create');
            }

            return redirect()->intended('/home');
        }

        // Error message sesuai jenis login
        throw ValidationException::withMessages([
            $isMahasiswaLogin ? 'simpkb_id' : 'email' => __('auth.failed'),
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
