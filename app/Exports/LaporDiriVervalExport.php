<?php

namespace App\Exports;

use App\Models\LaporDiri;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporDiriVervalExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return LaporDiri::with('verifikasi')
            ->whereHas('verifikasi', function ($query) {
                $query->where('status', 'diterima');
            })
            ->get()
            ->map(function ($item) {
                return [
                    'simpkb_id'    => $item->simpkb_id,
                    'nama_lengkap' => $item->nama_lengkap,
                    'nuptk'        => $item->nuptk,
                    'status'       => $item->verifikasi->status ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'SIMPKB ID',
            'Nama Lengkap',
            'NUPTK',
            'Status',
        ];
    }
}
