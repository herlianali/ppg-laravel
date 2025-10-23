@extends('layouts.app')

@section('title', 'Data Lapor Diri Divalidasi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Data Lapor Diri yang Sudah Divalidasi</h5>

                        {{-- Tombol Export --}}
                        <a href="{{ route('lapor.admin.export', ['format' => 'xlsx']) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($vervalLapor->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>SIMPKB ID</th>
                                            <th>Nama Lengkap</th>
                                            <th>NUPTK</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vervalLapor as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->simpkb_id ?? '-' }}</td>
                                                <td>{{ $item->nama_lengkap }}</td>
                                                <td>{{ $item->nuptk ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $item->verifikasi->status ?? 'diterima' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination jika ada --}}
                            @if (method_exists($vervalLapor, 'links'))
                                <div class="mt-3">
                                    {{ $vervalLapor->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>Belum ada data yang divalidasi</h5>
                                <p class="text-muted">Tidak ada data lapor diri dengan status diterima</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
