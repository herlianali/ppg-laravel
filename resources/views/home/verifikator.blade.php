@extends('layouts.app')

@section('title', 'Dashboard Verifikator')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dashboard Verifikator</h5>
                    <small class="text-muted">Selamat datang, {{ Auth::user()->name }}!</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Verifikasi -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="bg-warning rounded-circle p-3">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <span class="badge bg-warning rounded-pill">{{ $totalMenunggu }}</span>
                    </div>
                    <span class="fw-semibold d-block mb-1">Diproses</span>
                    <h3 class="card-title text-warning mb-2">{{ $totalMenunggu }}</h3>
                    <small class="text-warning fw-semibold">Menunggu verifikasi</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $totalDiterima }}</span>
                    </div>
                    <span class="fw-semibold d-block mb-1">Diterima</span>
                    <h3 class="card-title text-success mb-2">{{ $totalDiterima }}</h3>
                    <small class="text-success fw-semibold">Sudah diverifikasi</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="bg-danger rounded-circle p-3">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $totalDitolak }}</span>
                    </div>
                    <span class="fw-semibold d-block mb-1">Ditolak</span>
                    <h3 class="card-title text-danger mb-2">{{ $totalDitolak }}</h3>
                    <small class="text-danger fw-semibold">Tidak memenuhi syarat</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <div class="bg-info rounded-circle p-3">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                        </div>
                        <span class="badge bg-info rounded-pill">{{ $totalRevisi }}</span>
                    </div>
                    <span class="fw-semibold d-block mb-1">Perlu Revisi</span>
                    <h3 class="card-title text-info mb-2">{{ $totalRevisi }}</h3>
                    <small class="text-info fw-semibold">Butuh perbaikan data</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Data yang Perlu Diverifikasi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data yang Perlu Verifikasi</h5>
                    <a href="{{ route('verifikasi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($verifikasiTerkini->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($verifikasiTerkini as $data)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($data->nama_lengkap, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $data->nama_lengkap }}</h6>
                                                <small class="text-muted">{{ $data->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $data->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-warning">Menunggu</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('lapor.show', $data->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('lapor.verifikasi', $data->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Tidak ada data yang perlu diverifikasi</h5>
                        <p class="text-muted">Semua pengajuan sudah diproses</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-6 mb-3">
                            <a href="{{ route('verifikasi.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list-check me-2"></i>
                                Verifikasi Data
                            </a>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <a href="{{ route('datamhs.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-database me-2"></i>
                                Data Mahasiswa
                            </a>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <a href="{{ route('lapor.verifikator.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-file-alt me-2"></i>
                                Semua Lapor Diri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection