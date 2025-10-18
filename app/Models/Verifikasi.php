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
}
