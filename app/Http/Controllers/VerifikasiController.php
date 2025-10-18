<?php

namespace App\Http\Controllers;

use App\Models\LaporDiri;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:verifikasi')->only(['verifikasi', 'listVerifikasi']);
    }

    /**
     * Proses Verifikasi
     */
    public function verifikasi(Request $request, $id)
    {
        // Cek authorization
        if (!Auth::user()->isAdmin() && !Auth::user()->isVerifikator()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:diproses,diterima,ditolak,revisi',
            'komentar' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $laporDiri = LaporDiri::findOrFail($id);

            // Cari atau buat data verifikasi
            $verifikasi = Verifikasi::firstOrNew(['lapor_diri_id' => $id]);

            $verifikasi->status = $request->status;
            $verifikasi->komentar = $request->komentar;
            $verifikasi->verifikator = Auth::user()->name;
            $verifikasi->tanggal_verifikasi = now();

            $verifikasi->save();

            DB::commit();

            return redirect()->route('lapor.index')
                ->with('success', 'Data berhasil diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Halaman List Verifikasi
     */
    public function listVerifikasi()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isVerifikator()) {
            abort(403, 'Unauthorized action.');
        }

        $verifikasi = Verifikasi::with('laporDiri')
            ->latest()
            ->paginate(10);

        return view('formPPGMhs.verifikasi_index', compact('verifikasi'));
    }
}
