<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporDiri extends Model
{
    use HasFactory;

    protected $table = 'lapor_diris';

    protected $fillable = [
        // Step 1: Biodata
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'no_kk',
        'nuptk',
        'asal_pt',
        'ipk',
        'no_hp',
        'email',

        // Step 2: Alamat
        'alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',

        // Step 3: Orang Tua
        'nama_ayah',
        'nama_ibu',
        'tgl_lahir_ayah',
        'tgl_lahir_ibu',
        'pendidikan_ayah',
        'pendidikan_ibu',

        // Step 4: File paths
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
        'file_surat_ket_mengajar',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_lahir_ayah' => 'date',
        'tgl_lahir_ibu' => 'date',
        'ipk' => 'decimal:2',
    ];

    /**
     * Relasi ke Verifikasi
     */
    public function verifikasi()
    {
        return $this->hasOne(Verifikasi::class);
    }

    /**
     * Accessor untuk status verifikasi
     */
    public function getStatusVerifikasiAttribute()
    {
        return $this->verifikasi ? $this->verifikasi->status : 'diproses';
    }

    /**
     * Accessor untuk komentar verifikasi
     */
    public function getKomentarVerifikasiAttribute()
    {
        return $this->verifikasi ? $this->verifikasi->komentar : null;
    }

    /**
     * Accessor untuk tanggal verifikasi
     */
    public function getTanggalVerifikasiAttribute()
    {
        return $this->verifikasi ? $this->verifikasi->tanggal_verifikasi : null;
    }

    /**
     * Accessor untuk verifikator
     */
    public function getVerifikatorAttribute()
    {
        return $this->verifikasi ? $this->verifikasi->verifikator : null;
    }
}
