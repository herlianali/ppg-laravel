<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporDiriExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID Simpkb', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin',
            'Agama', 'No KK', 'NUPTK', 'Asal Perguruan Tinggi', 'Asal Prodi', 'IPK', 'Nomor HP',
            'Email', 'Bidang Studi', 'ABK', 'Alamat', 'Kelurahan', 'Kecamatan', 'Kabupaten',
            'Provinsi', 'Kode Pos', 'Nama Ayah', 'Nama Ibu', 'NIK Ayah', 'NIK Ibu', 'Gaji Ayah',
            'Gaji Ibu', 'Pekerjaan Ayah', 'Pekerjaan Ibu', 'Tanggal Lahir Ayah', 'Tanggal Lahir Ibu',
            'Pendidikan Ayah', 'Pendidikan Ibu', 'Tanggal Dibuat',
        ];
    }
}
