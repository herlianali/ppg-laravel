@extends('layouts.app')

@section('title', 'Data Saya')

@section('content')
    <div class="mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Laporan Saya</h5>
            </div>

            <div class="card-body">
                @if ($laporans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIM</th>
                                    <th>Tanggal</th>
                                    <th>Status Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $lapor)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lapor->nama_lengkap }}</td>
                                        <td>{{ $lapor->nim ?? '-' }}</td>
                                        <td>{{ $lapor->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if ($lapor->status_verifikasi === 'disetujui')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($lapor->status_verifikasi === 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-secondary">Menunggu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('lapor.my.show', $lapor->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $laporans->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                        <h5>Belum ada data laporan</h5>
                        <p class="text-muted">Silakan buat laporan diri terlebih dahulu.</p>
                        <a href="{{ route('lapor.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Laporan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
