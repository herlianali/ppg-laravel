<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\LaporDiri;
use App\Models\Verifikasi;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->isAdmin()) {
            $data = $this->getAdminData();
            return view('home.admin', $data);
        } elseif ($user->isVerifikator()) {
            $data = $this->getVerifikatorData();
            return view('home.verifikator', $data);
        } else {
            $data = $this->getMahasiswaData();
            return view('home.mahasiswa', $data);
        }
    }

    private function getAdminData()
    {
        $totalUsers = User::count();
        $totalLaporDiri = LaporDiri::count();
        
        // Statistik berdasarkan status verifikasi
        $statistikVerifikasi = [
            'diproses' => LaporDiri::menungguVerifikasi()->count(),
            'diterima' => LaporDiri::diterima()->count(),
            'ditolak' => LaporDiri::ditolak()->count(),
            'revisi' => LaporDiri::perluRevisi()->count(),
        ];

        return [
            'totalUsers' => $totalUsers,
            'totalLaporDiri' => $totalLaporDiri,
            'statistikVerifikasi' => $statistikVerifikasi,
            'laporDiriTerkini' => LaporDiri::with('verifikasi')->latest()->take(5)->get(),
            'chartData' => $this->getChartData()
        ];
    }

    private function getVerifikatorData()
    {
        $totalMenunggu = LaporDiri::menungguVerifikasi()->count();
        $totalDiterima = LaporDiri::diterima()->count();
        $totalDitolak = LaporDiri::ditolak()->count();
        $totalRevisi = LaporDiri::perluRevisi()->count();

        $verifikasiTerkini = LaporDiri::menungguVerifikasi()->latest()->take(5)->get();

        return [
            'totalMenunggu' => $totalMenunggu,
            'totalDiterima' => $totalDiterima,
            'totalDitolak' => $totalDitolak,
            'totalRevisi' => $totalRevisi,
            'verifikasiTerkini' => $verifikasiTerkini
        ];
    }

    private function getMahasiswaData()
    {
        $user = Auth::user();
        
        // Ambil data milik user yang login
        $totalPengajuan = LaporDiri::where('user_id', $user->id)->count();
        $statusTerakhir = LaporDiri::with('verifikasi')
            ->where('user_id', $user->id)
            ->latest()
            ->first();
        $pengajuanTerkini = LaporDiri::with('verifikasi')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return [
            'totalPengajuan' => $totalPengajuan,
            'statusTerakhir' => $statusTerakhir,
            'pengajuanTerkini' => $pengajuanTerkini
        ];
    }

    private function getChartData()
    {
        // Data chart berdasarkan bulan
        $currentYear = date('Y');
        $monthlyData = [];
        
        for ($i = 1; $i <= 6; $i++) {
            $month = date('M', mktime(0, 0, 0, $i, 1, $currentYear));
            $count = LaporDiri::whereYear('created_at', $currentYear)
                             ->whereMonth('created_at', $i)
                             ->count();
            $monthlyData[$month] = $count;
        }

        return [
            'labels' => array_keys($monthlyData),
            'data' => array_values($monthlyData)
        ];
    }
}