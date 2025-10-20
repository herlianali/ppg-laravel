@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dashboard Mahasiswa</h5>
                        <small class="text-muted">Selamat datang, {{ Auth::user()->name }}!</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Mahasiswa -->
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="bg-primary rounded-circle p-3">
                                    <i class="fas fa-file-upload text-white"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Pengajuan</span>
                        <h3 class="card-title mb-2">{{ $totalPengajuan }}</h3>
                        <small class="text-primary fw-semibold">Pengajuan lapor diri</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="bg-{{ $statusTerakhir ? 'success' : 'secondary' }} rounded-circle p-3">
                                    <i class="fas fa-{{ $statusTerakhir ? 'check-circle' : 'clock' }} text-white"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Status Terakhir</span>
                        <h3 class="card-title mb-2">
                            @if ($statusTerakhir->verifikasi->status === 'diterima')
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-secondary">Belum Ada</span>
                            @endif
                        </h3>
                        <small class="text-muted">
                            @if ($statusTerakhir)
                                {{ $statusTerakhir->created_at->format('d M Y') }}
                            @else
                                Belum ada pengajuan
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="bg-info rounded-circle p-3">
                                    <i class="fas fa-history text-white"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Aksi Cepat</span>
                        <h3 class="card-title mb-2">
                            <a href="{{ route('lapor.create') }}" class="btn btn-primary btn-sm">Ajukan Baru</a>
                        </h3>
                        <small class="text-info fw-semibold">Buat pengajuan baru</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengajuan Terkini -->
        <div class="row">
            <div class="col-lg-8 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Pengajuan Terkini</h5>
                        <a href="{{ route('lapor.my.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @if ($pengajuanTerkini->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($pengajuanTerkini as $pengajuan)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $pengajuan->nama_lengkap }}</h6>
                                                <p class="mb-1 text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $pengajuan->created_at->format('d M Y H:i') }}
                                                </p>
                                                <small class="text-muted">
                                                    Status:
                                                    <span
                                                        class="badge bg-{{ $pengajuan->verifikasi->status == 'diterima' ? 'success' : ($pengajuan->verifikasi->status == 'ditolak' ? 'danger' : 'warning') }}">
                                                        {{ $pengajuan->verifikasi->status ?? 'Menunggu' }}
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('lapor.my.show', $pengajuan->id) }}">
                                                            <i class="fas fa-eye me-2"></i>Lihat Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('lapor.edit', $pengajuan->id) }}">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                <h5>Belum ada pengajuan</h5>
                                <p class="text-muted">Mulai dengan membuat pengajuan lapor diri pertama Anda</p>
                                <a href="{{ route('lapor.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Buat Pengajuan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Guide -->
            <div class="col-lg-4 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Panduan Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">1</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Buat Pengajuan</h6>
                                        <small class="text-muted">Isi form lapor diri dengan data lengkap</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-warning">2</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Tunggu Verifikasi</h6>
                                        <small class="text-muted">Admin akan memverifikasi data Anda</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="d-flex">
                                    <div class="avatar avatar-sm me-3">
                                        <span class="avatar-initial rounded-circle bg-label-success">3</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Hasil Verifikasi</h6>
                                        <small class="text-muted">Cek status pengajuan secara berkala</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Buat Lapor Diri
                            </a>
                            <a href="" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Lihat Data Saya
                            </a>
                            {{-- <a href="" class="btn btn-outline-success">
                                <i class="fas fa-book me-2"></i>Info PPL
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
