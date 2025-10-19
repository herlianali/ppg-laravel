<?php

namespace App\Http\Controllers;

use App\Models\LaporDiri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporDiriController extends Controller
{
    /**
     * Tampilkan semua data (Read)
     */
    public function index()
    {
        if (!Auth::user()->isMahasiswa()) {
            abort(403, 'Hanya User Biasa yang dapat mengakses halaman ini.');
        }
        
        // Hanya tampilkan data milik user yang login
        $lapor = LaporDiri::where('user_id', Auth::id())->latest()->paginate(10);
        return view('formPPGMhs.index', compact('lapor'));
    }

    public function list()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isVerifikator()) {
            abort(403, 'Hanya Admin dan Verifikator yang dapat mengakses halaman ini.');
        }
        $lapor = LaporDiri::with('user')->latest()->paginate(10);
        return view('formPPGMhs.list', compact('lapor'));
    }
    /**
     * Tampilkan form create
     */
    public function create()
    {
        if (!Auth::user()->isMahasiswa()) {
            abort(403, 'Hanya user biasa yang dapat mengisi form lapor diri.');
        }
        return view('formPPGMhs.index');
    }

    /**
     * Simpan data baru (Create) dengan logging detail
     */
    public function store(Request $request)
    {
        Log::info('=== MULAI PROSES STORE DATA ===');
        Log::info('IP Address: ' . $request->ip());
        Log::info('User Agent: ' . $request->userAgent());

        if (!Auth::user()->isMahasiswa()) {
            return redirect()->back()
                ->with('error', 'Hanya user biasa yang dapat mengisi form lapor diri.');
        }

        // Log semua input data (kecuali file)
        $inputData = $request->except([
            'file_pakta_integritas',
            'file_biodata_mahasiswa',
            'file_ijazah',
            'file_transkrip',
            'file_ktp_sim',
            'file_surat_sehat',
            'file_skck',
            'file_npwp',
            'file_napza',
            'file_ijin_ks',
            'file_foto',
            'file_surat_ket_mengajar'
        ]);

        Log::info('Data Input:', $inputData);

        // Log file yang diupload
        $fileData = [];
        $fileFields = [
            'file_pakta_integritas',
            'file_biodata_mahasiswa',
            'file_ijazah',
            'file_transkrip',
            'file_ktp_sim',
            'file_surat_sehat',
            'file_skck',
            'file_npwp',
            'file_napza',
            'file_ijin_ks',
            'file_foto',
            'file_surat_ket_mengajar'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileData[$field] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ];
            }
        }

        Log::info('File yang diupload:', $fileData);

        // Validasi lengkap semua field
        $validationRules = [
            // Step 1: Biodata
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'no_kk' => 'nullable|string|max:16',
            'nuptk' => 'nullable|string|max:50',
            'asal_pt' => 'nullable|string|max:255',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',

            // Step 2: Alamat
            'alamat' => 'nullable|string|max:500',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',

            // Step 3: Orang Tua
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'tgl_lahir_ayah' => 'nullable|date',
            'tgl_lahir_ibu' => 'nullable|date',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pendidikan_ibu' => 'nullable|string|max:100',

            // Step 4: File Uploads (semua required kecuali yang optional)
            'file_pakta_integritas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_biodata_mahasiswa' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_transkrip' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ktp_sim' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_surat_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_skck' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_npwp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_napza' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ijin_ks' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_foto' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'file_surat_ket_mengajar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];

        Log::info('Memulai validasi...');

         try {
            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                Log::error('VALIDATION FAILED:', $errors);
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan validasi. Silakan periksa kembali data Anda.')
                    ->with('current_step', $request->input('current_step', 1));
            }

            Log::info('Validasi berhasil!');

            // Mulai transaction database
            DB::beginTransaction();
            Log::info('Memulai database transaction...');

            $data = $request->except($fileFields);
            
            // TAMBAHKAN USER_ID DARI AUTH USER
            $data['user_id'] = Auth::id();
            
            Log::info('Data setelah exclude file dan tambah user_id:', $data);

            // Upload semua file
            $uploadedFiles = [];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    try {
                        $file = $request->file($field);
                        $filename = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('uploads/lapor_diri', $filename, 'public');
                        $data[$field] = $path;
                        $uploadedFiles[$field] = $path;

                        Log::info("File {$field} berhasil diupload: {$path}");
                    } catch (\Exception $fileError) {
                        Log::error("Error uploading file {$field}: " . $fileError->getMessage());
                        throw $fileError;
                    }
                } else {
                    Log::warning("File {$field} tidak diupload");
                }
            }

            Log::info('Data sebelum create:', $data);

            // Coba create data
            try {
                $laporDiri = LaporDiri::create($data);
                Log::info('Data berhasil disimpan dengan ID: ' . $laporDiri->id);

                // Commit transaction
                DB::commit();
                Log::info('Database transaction committed!');

                return redirect()->route('home.index')
                    ->with('success', 'Data lapor diri berhasil disimpan!');

            } catch (\Exception $createError) {
                Log::error('Error saat create data: ' . $createError->getMessage());
                
                // Rollback transaction
                DB::rollBack();
                Log::error('Database transaction rolled back!');

                // Hapus file yang sudah terupload jika create gagal
                foreach ($uploadedFiles as $filePath) {
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                        Log::info("File dihapus karena create gagal: {$filePath}");
                    }
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $createError->getMessage())
                    ->with('current_step', $request->input('current_step', 1));
            }

        } catch (\Exception $e) {
            Log::error('=== ERROR STORE DATA ===');
            Log::error('Error Message: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->with('current_step', $request->input('current_step', 1));
        } finally {
            Log::info('=== SELESAI PROSES STORE DATA ===');
        }
    }

    /**
     * Detail data (Read detail)
     */
    public function show($id)
    {
        $lapor = LaporDiri::findOrFail($id);
        
        // Cek apakah user boleh melihat data ini
        if (Auth::user()->isMahasiswa() && $lapor->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat melihat data sendiri.');
        }
        
        return view('formPPGMhs.show', compact('lapor'));
    }

    /**
     * Form Edit (Update)
     */
    public function edit($id)
    {
        $lapor = LaporDiri::findOrFail($id);
        
        // Cek apakah user boleh mengedit data ini
        if (Auth::user()->isMahasiswa() && $lapor->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengedit data sendiri.');
        }
        
        return view('formPPGMhs.edit', compact('lapor'));
    }

    /**
     * Proses Update
     */
    public function update(Request $request, $id)
    {
        $lapor = LaporDiri::findOrFail($id);

        if (Auth::user()->isMahasiswa() && $lapor->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengupdate data sendiri.');
        }
        $request->validate([
            // Validasi sama seperti store, tapi file tidak required
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'no_kk' => 'nullable|string|max:16',
            'nuptk' => 'nullable|string|max:50',
            'asal_pt' => 'nullable|string|max:255',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'tgl_lahir_ayah' => 'nullable|date',
            'tgl_lahir_ibu' => 'nullable|date',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pendidikan_ibu' => 'nullable|string|max:100',

            // File validation (optional untuk update)
            'file_pakta_integritas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_biodata_mahasiswa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_transkrip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ktp_sim' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_surat_sehat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_skck' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_npwp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_napza' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_ijin_ks' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'file_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'file_surat_ket_mengajar' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $data = $request->except([
                'file_pakta_integritas',
                'file_biodata_mahasiswa',
                'file_ijazah',
                'file_transkrip',
                'file_ktp_sim',
                'file_surat_sehat',
                'file_skck',
                'file_npwp',
                'file_napza',
                'file_ijin_ks',
                'file_foto',
                'file_surat_ket_mengajar'
            ]);

            // Upload file yang baru (jika ada)
            $fileFields = [
                'file_pakta_integritas',
                'file_biodata_mahasiswa',
                'file_ijazah',
                'file_transkrip',
                'file_ktp_sim',
                'file_surat_sehat',
                'file_skck',
                'file_npwp',
                'file_napza',
                'file_ijin_ks',
                'file_foto',
                'file_surat_ket_mengajar'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Hapus file lama jika ada
                    if ($lapor->$field) {
                        Storage::disk('public')->delete($lapor->$field);
                    }

                    $file = $request->file($field);
                    $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/lapor_diri', $filename, 'public');
                    $data[$field] = $path;
                }
            }

            $lapor->update($data);

            return redirect()->route('lapor.index')
                ->with('success', 'Data berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data (Delete)
     */
    public function destroy($id)
    {
        try {
            $lapor = LaporDiri::findOrFail($id);

            if (Auth::user()->isMahasiswa() && $lapor->user_id !== Auth::id()) {
                abort(403, 'Anda hanya dapat menghapus data sendiri.');
            }
            // Hapus file-file yang terkait
            $fileFields = [
                'file_pakta_integritas',
                'file_biodata_mahasiswa',
                'file_ijazah',
                'file_transkrip',
                'file_ktp_sim',
                'file_surat_sehat',
                'file_skck',
                'file_npwp',
                'file_napza',
                'file_ijin_ks',
                'file_foto',
                'file_surat_ket_mengajar'
            ];

            foreach ($fileFields as $field) {
                if ($lapor->$field) {
                    Storage::disk('public')->delete($lapor->$field);
                }
            }

            $lapor->delete();

            return redirect()->route('lapor.index')
                ->with('success', 'Data berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->route('lapor.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * View file di browser (Preview)
     */
    public function viewFile($id, $field)
    {
        $lapor = LaporDiri::findOrFail($id);

        $allowedFields = [
            'file_pakta_integritas',
            'file_biodata_mahasiswa',
            'file_ijazah',
            'file_transkrip',
            'file_ktp_sim',
            'file_surat_sehat',
            'file_skck',
            'file_npwp',
            'file_napza',
            'file_ijin_ks',
            'file_foto',
            'file_surat_ket_mengajar'
        ];

        if (!in_array($field, $allowedFields) || !$lapor->$field) {
            abort(404, 'File tidak ditemukan');
        }

        // Cek apakah file exists
        if (!Storage::disk('public')->exists($lapor->$field)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        $filePath = Storage::disk('public')->path($lapor->$field);

        // Dapatkan mime type
        $mimeType = mime_content_type($filePath);

        // Dapatkan file content
        $fileContent = Storage::disk('public')->get($lapor->$field);

        // Return response dengan header yang tepat
        return response($fileContent)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($lapor->$field) . '"')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Download file
     */
    public function downloadFile($id, $field)
    {
        $lapor = LaporDiri::findOrFail($id);

        $allowedFields = [
            'file_pakta_integritas',
            'file_biodata_mahasiswa',
            'file_ijazah',
            'file_transkrip',
            'file_ktp_sim',
            'file_surat_sehat',
            'file_skck',
            'file_npwp',
            'file_napza',
            'file_ijin_ks',
            'file_foto',
            'file_surat_ket_mengajar'
        ];

        if (!in_array($field, $allowedFields) || !$lapor->$field) {
            abort(404, 'File tidak ditemukan');
        }

        // Cek apakah file exists
        if (!Storage::disk('public')->exists($lapor->$field)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        $filePath = Storage::disk('public')->path($lapor->$field);
        $originalName = pathinfo($lapor->$field, PATHINFO_BASENAME);

        return Response::download($filePath, $originalName);
    }

    /**
     * Debug method untuk melihat log
     */
    public function debugLog()
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $logContent = file_get_contents($logPath);
            return response($logContent)->header('Content-Type', 'text/plain');
        }

        return 'Log file tidak ditemukan';
    }
}
