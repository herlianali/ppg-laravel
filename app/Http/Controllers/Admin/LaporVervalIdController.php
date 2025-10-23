<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\LaporDiriVervalExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporVervalIdController extends Controller
{
    public function index()
    {
        $vervalLapor = \App\Models\LaporDiri::with('verifikasi')
            ->whereHas('verifikasi', function ($query) {
                $query->where('status', 'diterima');
            })
            ->get();

        return view('admin.vervalId.index', compact('vervalLapor'));
    }

    public function export($format)
    {
        $fileName = 'lapor_diri_verval.' . $format;
        return Excel::download(new LaporDiriVervalExport, $fileName);
    }
}
