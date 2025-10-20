<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporDiri;
use Illuminate\Http\Request;

class LaporVervalIdController extends Controller
{
    public function index()
    {
        $vervalLapor = LaporDiri::with('verifikasi')
            ->whereHas('verifikasi', function ($query) {
                $query->where('status', 'diterima');
            })
            ->get();

        return view('admin.vervalId.index', compact('vervalLapor'));
    }
}
