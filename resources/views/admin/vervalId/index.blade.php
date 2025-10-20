@extends('layouts.app')

@section('title', 'Data Lapor Diri Divalidasi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Data Lapor Diri yang Sudah Divalidasi</h5>
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
                                            {{-- <th>Aksi</th> --}}
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
                                                {{-- <td>
                                                    <a href="{{ route('lapor.show', $item->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Jika menggunakan pagination -->
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
