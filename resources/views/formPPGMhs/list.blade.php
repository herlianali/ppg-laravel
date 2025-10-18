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
                    <div>
                        <a href="{{ route('verifikasi.index') }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-clipboard-list me-1"></i> List Verifikasi
                        </a>
                        <a href="{{ route('lapor.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Data
                        </a>
                    </div>
                </div>

                <div class="card-body mt-4">
                    <!-- ... alert messages ... -->

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
                                    <th class="text-white">Status Verifikasi</th>
                                    <th class="text-white">Tanggal Daftar</th>
                                    <th class="text-white text-center" width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lapor as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration + ($lapor->currentPage() - 1) * $lapor->perPage() }}
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $item->nama_lengkap }}</div>
                                            <small
                                                class="text-muted">{{ $item->nuptk ? 'NUPTK: ' . $item->nuptk : 'Tidak ada NUPTK' }}</small>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $item->email }}" class="text-decoration-none">
                                                {{ $item->email }}
                                            </a>
                                        </td>
                                        <td>{{ $item->no_hp ?? '-' }}</td>
                                        <td>
                                            @if ($item->asal_pt)
                                                <span class="badge bg-info">{{ Str::limit($item->asal_pt, 20) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
                                                $status = $item->status_verifikasi;
                                                $config = $statusConfig[$status] ?? $statusConfig['diproses'];
                                            @endphp
                                            <span class="badge {{ $config[0] }}">
                                                <i class="{{ $config[1] }} me-1"></i>{{ $config[2] }}
                                            </span>
                                            @if ($item->tanggal_verifikasi)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->tanggal_verifikasi->format('d M Y') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $item->created_at->format('d M Y') }}<br>
                                                <span class="text-muted">{{ $item->created_at->format('H:i') }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('lapor.show', $item->id) }}" class="btn btn-info"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('lapor.edit', $item->id) }}" class="btn btn-warning"
                                                    title="Edit Data">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-primary btn-verifikasi"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama_lengkap }}"
                                                    data-status="{{ $item->status_verifikasi }}"
                                                    data-komentar="{{ $item->komentar_verifikasi ?? '' }}"
                                                    title="Verifikasi Data">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </button>
                                                <form action="{{ route('lapor.destroy', $item->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $item->nama_lengkap }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
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

                    <!-- ... pagination dan summary ... -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="verifikasiModalLabel">
                        <i class="fas fa-clipboard-check me-2"></i>Verifikasi Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="verifikasiForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="namaPendaftar" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Status Verifikasi <span
                                            class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required id="statusVerifikasi">
                                        <option value="diproses">Diproses</option>
                                        <option value="diterima">Diterima</option>
                                        <option value="ditolak">Ditolak</option>
                                        <option value="revisi">Perlu Revisi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Tanggal Verifikasi</label>
                                    <input type="text" class="form-control" value="{{ now()->format('d F Y H:i') }}"
                                        readonly>
                                    <input type="hidden" name="tanggal_verifikasi" value="{{ now() }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Komentar / Catatan Verifikasi</label>
                            <textarea name="komentar" class="form-control" rows="5"
                                placeholder="Berikan komentar atau catatan verifikasi..." id="komentarVerifikasi"></textarea>
                            <div class="form-text">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Komentar akan ditampilkan kepada pendaftar untuk informasi lebih lanjut
                                </small>
                            </div>
                        </div>

                        <!-- Preview komentar berdasarkan status -->
                        <div id="komentarPreview" class="alert d-none">
                            <div id="previewContent"></div>
                        </div>

                        <!-- Required fields untuk status tertentu -->
                        <div id="requiredFields" class="alert alert-warning d-none">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="requiredMessage"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Verifikasi
                        </button>
                    </div>
                </form>
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

        .card .card-body h4 {
            margin-bottom: 0.25rem;
            font-weight: bold;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .badge {
            font-size: 0.75rem;
        }

        .pagination .page-link {
            color: #0d6efd;
            border-color: #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Status badge colors */
        .bg-diproses {
            background-color: #6c757d !important;
        }

        .bg-diterima {
            background-color: #198754 !important;
        }

        .bg-ditolak {
            background-color: #dc3545 !important;
        }

        .bg-revisi {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifikasiModal = new bootstrap.Modal(document.getElementById('verifikasiModal'));
            const verifikasiForm = document.getElementById('verifikasiForm');
            const statusVerifikasi = document.getElementById('statusVerifikasi');
            const komentarVerifikasi = document.getElementById('komentarVerifikasi');
            const komentarPreview = document.getElementById('komentarPreview');
            const previewContent = document.getElementById('previewContent');
            const requiredFields = document.getElementById('requiredFields');
            const requiredMessage = document.getElementById('requiredMessage');

            // Template komentar berdasarkan status
            const komentarTemplates = {
                diproses: "Data sedang dalam proses verifikasi. Harap menunggu informasi lebih lanjut.",
                diterima: "Selamat! Data Anda telah diverifikasi dan diterima. Silakan melanjutkan ke tahap berikutnya.",
                ditolak: "Maaf, data Anda tidak dapat diterima karena tidak memenuhi persyaratan yang ditetapkan.",
                revisi: "Data Anda memerlukan revisi. Silakan perbaiki data sesuai dengan ketentuan yang berlaku."
            };

            // Event listener untuk tombol verifikasi
            document.querySelectorAll('.btn-verifikasi').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const status = this.getAttribute('data-status');
                    const komentar = this.getAttribute('data-komentar');

                    // Set form action
                    verifikasiForm.action = `/lapor/${id}/verifikasi`;

                    // Set nilai form
                    document.getElementById('namaPendaftar').value = nama;
                    statusVerifikasi.value = status;
                    komentarVerifikasi.value = komentar;

                    // Update preview
                    updatePreview(status);

                    // Tampilkan modal
                    verifikasiModal.show();
                });
            });

            // Update preview ketika status berubah
            statusVerifikasi.addEventListener('change', function() {
                updatePreview(this.value);
            });

            function updatePreview(status) {
                const template = komentarTemplates[status];

                if (template && !komentarVerifikasi.value) {
                    previewContent.innerHTML = `<strong>Preview Komentar:</strong><br>${template}`;
                    komentarPreview.className = `alert alert-info`;
                    komentarPreview.classList.remove('d-none');
                } else {
                    komentarPreview.classList.add('d-none');
                }

                // Tampilkan pesan required fields
                if (status === 'ditolak' || status === 'revisi') {
                    requiredMessage.textContent = status === 'ditolak' ?
                        'Wajib memberikan alasan penolakan pada kolom komentar.' :
                        'Wajib memberikan detail revisi yang diperlukan pada kolom komentar.';
                    requiredFields.classList.remove('d-none');
                } else {
                    requiredFields.classList.add('d-none');
                }
            }

            // Validasi form sebelum submit
            verifikasiForm.addEventListener('submit', function(e) {
                const status = statusVerifikasi.value;
                const komentar = komentarVerifikasi.value.trim();

                if ((status === 'ditolak' || status === 'revisi') && !komentar) {
                    e.preventDefault();
                    alert('Harap isi komentar verifikasi untuk status ' + status + '.');
                    komentarVerifikasi.focus();
                    return false;
                }

                // Tampilkan loading
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                submitBtn.disabled = true;
            });

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
                    const status = row.cells[5].textContent.toLowerCase();

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

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endsection
