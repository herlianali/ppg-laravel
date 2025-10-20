<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporDiri extends Model
{
    use HasFactory;

    protected $table = 'lapor_diris';

    protected $fillable = [
        // Relasi user
        'user_id',

        // Step 1: Biodata
        'simpkb_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'no_kk',
        'nuptk',
        'asal_pt',
        'asal_prodi',
        'ipk',
        'no_hp',
        'email',
        'bidang_studi',
        'abk',

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
        'nik_ayah',
        'nik_ibu',
        'gaji_ayah',
        'gaji_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
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
     * Boot method untuk model events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika lapor diri dibuat, buat record verifikasi otomatis
        static::created(function ($laporDiri) {
            $laporDiri->createVerifikasiRecord();
        });
    }

    /**
     * Method untuk membuat record verifikasi
     */
    public function createVerifikasiRecord()
    {
        return $this->verifikasi()->create([
            'status' => 'diproses',
            'verifikator' => null,
            'komentar' => null,
            'tanggal_verifikasi' => null,
        ]);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    /**
     * Accessor untuk status label dengan Bootstrap class
     */
    public function getStatusLabelAttribute()
    {
        $status = $this->status_verifikasi;

        $labels = [
            'diproses' => ['label' => 'Diproses', 'class' => 'warning'],
            'diterima' => ['label' => 'Diterima', 'class' => 'success'],
            'ditolak' => ['label' => 'Ditolak', 'class' => 'danger'],
            'revisi' => ['label' => 'Perlu Revisi', 'class' => 'info'],
        ];

        return $labels[$status] ?? ['label' => 'Diproses', 'class' => 'secondary'];
    }

    /**
     * Scope untuk data milik user tertentu
     */
    public function scopeMilikUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk data yang sudah diterima
     */
    public function scopeDiterima($query)
    {
        return $query->whereHas('verifikasi', function ($q) {
            $q->where('status', 'diterima');
        });
    }

    /**
     * Scope untuk data yang ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->whereHas('verifikasi', function ($q) {
            $q->where('status', 'ditolak');
        });
    }

    /**
     * Scope untuk data yang menunggu verifikasi (diproses)
     */
    public function scopeMenungguVerifikasi($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('verifikasi', function ($subQ) {
                $subQ->where('status', 'diproses');
            })->orDoesntHave('verifikasi');
        });
    }

    /**
     * Scope untuk data yang perlu revisi
     */
    public function scopePerluRevisi($query)
    {
        return $query->whereHas('verifikasi', function ($q) {
            $q->where('status', 'revisi');
        });
    }
}
