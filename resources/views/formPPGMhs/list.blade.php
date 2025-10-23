@extends('layouts.app')

@section('title', 'Data Lapor Diri PPG')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-list me-2"></i>Data Lapor Diri PPG Tahap 3 Tahun 2025
                    </h5>
                    @if (Auth::user()->role === 'admin')
                        <div class="d-flex gap-2">
                            <a href="{{ route('verifikasi.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-clipboard-list me-1"></i> List Verifikasi
                            </a>

                            <a href="{{ route('verifikasi.export', ['format' => 'xlsx']) }}" 
                                id="exportExcel" 
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </a>

                            <a href="{{ route('verifikasi.export', ['format' => 'csv']) }}" 
                                id="exportCsv" 
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-file-csv me-1"></i> Export CSV
                            </a>


                            <a href="{{ route('lapor.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Data
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-body mt-4">
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter dan Search -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Cari nama atau email...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="statusFilter" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="diproses">Diproses</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="revisi">Perlu Revisi</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="resetFilter" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-refresh me-1"></i> Reset
                            </button>
                        </div>
                    </div>

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="dataTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white" width="50">No</th>
                                    <th class="text-white">Nama Lengkap</th>
                                    <th class="text-white">Email</th>
                                    <th class="text-white">No. HP</th>
                                    <th class="text-white">Asal PT</th>
                                    <th class="text-white">Bidang Study</th>
                                    <th class="text-white">Status Verifikasi asdasdasd</th>
                                    <th class="text-white">Tanggal Daftar</th>
                                    <th class="text-white text-center" width="120">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lapor as $item)
                                    @if ($item->laporDiri)
                                        {{-- Pastikan relasi ada --}}
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration + ($lapor->currentPage() - 1) * $lapor->perPage() }}
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $item->laporDiri->nama_lengkap }}</div>
                                                <small class="text-muted">
                                                    {{ $item->laporDiri->nuptk ? 'NUPTK: ' . $item->laporDiri->nuptk : 'Tidak ada NUPTK' }}
                                                </small>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $item->laporDiri->email }}" class="text-decoration-none">
                                                    {{ $item->laporDiri->email }}
                                                </a>
                                            </td>
                                            <td>{{ $item->laporDiri->no_hp ?? '-' }}</td>
                                            <td>
                                                @if ($item->laporDiri->asal_pt)
                                                    <span
                                                        class="badge bg-info">{{ Str::limit($item->laporDiri->asal_pt, 20) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <b>{{ $item->laporDiri->bidang_studi }}</b>
                                            </td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'diproses' => ['bg-secondary', 'fas fa-clock', 'Diproses'],
                                                        'diterima' => ['bg-success', 'fas fa-check', 'Diterima'],
                                                        'ditolak' => ['bg-danger', 'fas fa-times', 'Ditolak'],
                                                        'revisi' => [
                                                            'bg-warning',
                                                            'fas fa-exclamation-triangle',
                                                            'Perlu Revisi',
                                                        ],
                                                    ];
                                                    $status = $item->status;
                                                    $config = $statusConfig[$status] ?? $statusConfig['diproses'];
                                                @endphp
                                                <span class="badge {{ $config[0] }}">
                                                    <i class="{{ $config[1] }} me-1"></i>{{ $config[2] }}
                                                </span>
                                                @if ($item->tanggal_verifikasi)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($item->tanggal_verifikasi)->format('d M Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $item->laporDiri->created_at->format('d M Y') }}<br>
                                                    <span
                                                        class="text-muted">{{ $item->laporDiri->created_at->format('H:i') }}</span>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('lapor.show', $item->lapor_diri_id) }}"
                                                    class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <h5>Belum ada data</h5>
                                                <p>Silakan tambah data baru dengan mengklik tombol "Tambah Data"</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($lapor->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Menampilkan {{ $lapor->firstItem() }} - {{ $lapor->lastItem() }} dari
                                {{ $lapor->total() }} data
                            </div>
                            <div>
                                {{ $lapor->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const resetFilter = document.getElementById('resetFilter');
            const dataTable = document.getElementById('dataTable');
            const rows = dataTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const status = row.cells[6].textContent.toLowerCase();

                    const nameMatch = name.includes(searchTerm);
                    const emailMatch = email.includes(searchTerm);
                    const statusMatch = statusValue === '' || status.includes(statusValue);

                    if ((nameMatch || emailMatch) && statusMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }

            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);

            resetFilter.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                filterTable();
            });
            // Export filter data
            const exportExcel = document.getElementById('exportExcel');
            const exportCsv = document.getElementById('exportCsv');

            function updateExportLinks() {
                const searchValue = encodeURIComponent(searchInput.value.trim());
                const statusValue = encodeURIComponent(statusFilter.value.trim());

                const query = `?search=${searchValue}&status=${statusValue}`;

                exportExcel.href = "{{ route('verifikasi.export', ['format' => 'xlsx']) }}" + query;
                exportCsv.href = "{{ route('verifikasi.export', ['format' => 'csv']) }}" + query;
            }

            // Update link setiap kali filter berubah
            searchInput.addEventListener('input', updateExportLinks);
            statusFilter.addEventListener('change', updateExportLinks);
            resetFilter.addEventListener('click', updateExportLinks);

            // Jalankan sekali di awal
            updateExportLinks();

        });
    </script>
@endsection
