@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dashboard Administrator</h5>
                        <small class="text-muted">Selamat datang, {{ Auth::user()->name }}!</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="bg-primary rounded-circle p-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Pengguna</span>
                        <h3 class="card-title mb-2">{{ $totalUsers }}</h3>
                        <small class="text-success fw-semibold">Semua pengguna</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <div class="bg-warning rounded-circle p-3">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ $statistikVerifikasi['diproses'] }}</span>
                        </div>
                        <span class="fw-semibold d-block mb-1">Diproses</span>
                        <h3 class="card-title text-warning mb-2">{{ $statistikVerifikasi['diproses'] }}</h3>
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
                            <span class="badge bg-success rounded-pill">{{ $statistikVerifikasi['diterima'] }}</span>
                        </div>
                        <span class="fw-semibold d-block mb-1">Diterima</span>
                        <h3 class="card-title text-success mb-2">{{ $statistikVerifikasi['diterima'] }}</h3>
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
                            <span
                                class="badge bg-danger rounded-pill">{{ $statistikVerifikasi['ditolak'] + $statistikVerifikasi['revisi'] }}</span>
                        </div>
                        <span class="fw-semibold d-block mb-1">Perlu Tindakan</span>
                        <h3 class="card-title text-danger mb-2">
                            {{ $statistikVerifikasi['ditolak'] + $statistikVerifikasi['revisi'] }}</h3>
                        <small class="text-danger fw-semibold">Ditolak & Perlu Revisi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts dan Data Terkini -->
        <div class="row">
            <!-- Chart -->
            <div class="col-lg-8 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistik Pengajuan Lapor Diri</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statsChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Terkini -->
            <div class="col-lg-4 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Data Terkini</h5>
                        <span class="badge bg-primary">{{ $laporDiriTerkini->count() }} data</span>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach ($laporDiriTerkini as $data)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $data->nama_lengkap }}</h6>
                                            <small class="text-muted">{{ $data->created_at->format('d M Y') }}</small>
                                        </div>
                                        <span class="badge bg-label-warning">Baru</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('lapor.admin.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <a href="" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-database me-2"></i>
                                    Data Mahasiswa
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="" class="btn btn-outline-success w-100">
                                    <i class="fas fa-server me-2"></i>
                                    Data Master
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="" class="btn btn-outline-info w-100">
                                    <i class="fas fa-gears me-2"></i>
                                    Pengaturan
                                </a>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <a href="" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Lapor Diri
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart.js implementation
            const ctx = document.getElementById('statsChart').getContext('2d');
            const statsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Pengajuan Lapor Diri',
                        data: @json($chartData['data']),
                        borderColor: '#696cff',
                        backgroundColor: 'rgba(105, 108, 255, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
