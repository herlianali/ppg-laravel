<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';

    protected $fillable = [
        'lapor_diri_id',
        'status',
        'komentar',
        'verifikator',
        'tanggal_verifikasi'
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Relasi ke LaporDiri
     */
    public function laporDiri()
    {
        return $this->belongsTo(LaporDiri::class);
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'diproses' => ['label' => 'Diproses', 'class' => 'warning'],
            'diterima' => ['label' => 'Diterima', 'class' => 'success'],
            'ditolak' => ['label' => 'Ditolak', 'class' => 'danger'],
            'revisi' => ['label' => 'Perlu Revisi', 'class' => 'info'],
        ];

        return $labels[$this->status] ?? ['label' => $this->status, 'class' => 'secondary'];
    }

    /**
     * Method untuk melakukan verifikasi
     */
    public static function verifikasiData($laporDiriId, $status, $komentar = null, $verifikator = null)
    {
        $verifikasi = self::where('lapor_diri_id', $laporDiriId)->first();
        
        if (!$verifikasi) {
            $verifikasi = new self();
            $verifikasi->lapor_diri_id = $laporDiriId;
        }
        
        $verifikasi->status = $status;
        $verifikasi->komentar = $komentar;
        $verifikasi->verifikator = $verifikator;
        
        if ($status != 'diproses') {
            $verifikasi->tanggal_verifikasi = now();
        }
        
        return $verifikasi->save();
    }
}