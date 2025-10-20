@extends('layouts.app')

@section('title', 'Detail Data Lapor Diri')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-user-circle me-2"></i>Detail Data Lapor Diri -
                            {{ $lapor->nama_lengkap }}
                        </h5>
                        <div>
                            <a href="{{ route('lapor.edit', $lapor->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="card-body mt-4">

                        <!-- Progress Status -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Status Data:</strong>
                                    <span class="badge bg-success">Terkirim</span>
                                    <small class="text-muted d-block">Data dikirim pada:
                                        {{ $lapor->created_at->format('d F Y H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="section-card mb-4">
                            <div class="section-header bg-light-warning d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-check me-2"></i>Verifikasi Data oleh Verifikator
                                </h6>
                                @if ($lapor->status_verifikasi === 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif ($lapor->status_verifikasi === 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diverifikasi</span>
                                @endif
                            </div>
                            <div class="section-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Catatan Verifikator</label>
                                    <p class="form-control-static">
                                        {{ $lapor->catatan_verifikator ?? '-' }}
                                    </p>
                                </div>

                                @if (auth()->user()->role === 'verifikator')
                                    <form action="{{ route('lapor.verifikasi', $lapor->id) }}" method="POST"
                                        class="mt-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="catatan" class="form-label fw-semibold">Tulis Catatan</label>
                                            <textarea name="catatan_verifikator" id="catatan" rows="3" class="form-control"
                                                placeholder="Catatan hasil verifikasi...">{{ $lapor->catatan_verifikator ?? '' }}</textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="status" value="disetujui" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i> Setujui
                                            </button>
                                            <button type="submit" name="status" value="ditolak" class="btn btn-danger">
                                                <i class="fas fa-times me-1"></i> Tolak
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Step 1: Biodata Pribadi -->
                        <div class="section-card mb-4">
                            <div class="section-header bg-light-primary">
                                <h6 class="mb-0">
                                    <i class="fas fa-user me-2"></i>1. Biodata Pribadi
                                </h6>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-muted">Nama Lengkap</label>
                                        <p class="form-control-static">{{ $lapor->nama_lengkap }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-muted">Tempat, Tanggal Lahir</label>
                                        <p class="form-control-static">
                                            {{ $lapor->tempat_lahir ?? '-' }},
                                            {{ $lapor->tanggal_lahir ? \Carbon\Carbon::parse($lapor->tanggal_lahir)->format('d F Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-muted">Jenis Kelamin</label>
                                        <p class="form-control-static">
                                            @if ($lapor->jenis_kelamin)
                                                <span class="badge bg-primary">{{ $lapor->jenis_kelamin }}</span>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-muted">Agama</label>
                                        <p class="form-control-static">{{ $lapor->agama ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold text-muted">Nomor KK</label>
                                        <p class="form-control-static">{{ $lapor->no_kk ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">NUPTK</label>
                                        <p class="form-control-static">{{ $lapor->nuptk ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Asal Perguruan Tinggi</label>
                                        <p class="form-control-static">{{ $lapor->asal_pt ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Bidang Study</label>
                                        <p class="form-control-static">{{ $lapor->bidang_studi ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">IPK</label>
                                        <p class="form-control-static">
                                            @if ($lapor->ipk)
                                                <span class="badge bg-info">{{ $lapor->ipk }}</span>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-muted">Nomor HP</label>
                                        <p class="form-control-static">{{ $lapor->no_hp ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-muted">Email</label>
                                        <p class="form-control-static">
                                            <a href="mailto:{{ $lapor->email }}">{{ $lapor->email }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Alamat Domisili -->
                        <div class="section-card mb-4">
                            <div class="section-header bg-light-info">
                                <h6 class="mb-0">
                                    <i class="fas fa-home me-2"></i>2. Alamat Domisili
                                </h6>
                            </div>
                            <div class="section-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted">Alamat Lengkap</label>
                                    <p class="form-control-static">{{ $lapor->alamat ?? '-' }}</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Kelurahan</label>
                                        <p class="form-control-static">{{ $lapor->kelurahan ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Kecamatan</label>
                                        <p class="form-control-static">{{ $lapor->kecamatan ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Kabupaten/Kota</label>
                                        <p class="form-control-static">{{ $lapor->kabupaten ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-muted">Provinsi</label>
                                        <p class="form-control-static">{{ $lapor->provinsi ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-muted">Kode Pos</label>
                                        <p class="form-control-static">{{ $lapor->kode_pos ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Data Orang Tua -->
                        <div class="section-card mb-4">
                            <div class="section-header bg-light-warning">
                                <h6 class="mb-0">
                                    <i class="fas fa-users me-2"></i>3. Data Orang Tua
                                </h6>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6 class="border-bottom pb-2">Ayah</h6>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Nama Ayah</label>
                                            <p class="form-control-static">{{ $lapor->nama_ayah ?? '-' }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Tanggal Lahir</label>
                                            <p class="form-control-static">
                                                {{ $lapor->tgl_lahir_ayah ? \Carbon\Carbon::parse($lapor->tgl_lahir_ayah)->format('d F Y') : '-' }}
                                            </p>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Pendidikan</label>
                                            <p class="form-control-static">{{ $lapor->pendidikan_ayah ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <h6 class="border-bottom pb-2">Ibu</h6>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Nama Ibu</label>
                                            <p class="form-control-static">{{ $lapor->nama_ibu ?? '-' }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Tanggal Lahir</label>
                                            <p class="form-control-static">
                                                {{ $lapor->tgl_lahir_ibu ? \Carbon\Carbon::parse($lapor->tgl_lahir_ibu)->format('d F Y') : '-' }}
                                            </p>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold text-muted">Pendidikan</label>
                                            <p class="form-control-static">{{ $lapor->pendidikan_ibu ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Dokumen -->
                        <div class="section-card mb-4">
                            <div class="section-header bg-light-success">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-alt me-2"></i>4. Dokumen
                                </h6>
                            </div>
                            <div class="section-body">
                                <div class="row">
                                    <!-- Dokumen Wajib -->
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Dokumen Wajib</h6>

                                        @php
                                            $requiredDocuments = [
                                                'file_pakta_integritas' => 'Pakta Integritas',
                                                'file_biodata_mahasiswa' => 'Biodata Mahasiswa',
                                                'file_ijazah' => 'Ijazah S1/D4 (Legalisir)',
                                                'file_transkrip' => 'Transkrip Nilai S1/D4',
                                                'file_surat_sehat' => 'Surat Keterangan Sehat',
                                                'file_skck' => 'SKCK',
                                                'file_napza' => 'Surat Bebas NAPZA',
                                                'file_ijin_ks' => 'Surat Izin Kepala Sekolah',
                                                'file_foto' => 'Pas Foto 4x6',
                                            ];
                                        @endphp

                                        @foreach ($requiredDocuments as $field => $label)
                                            <div class="document-item mb-3 p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $loop->iteration }}. {{ $label }}
                                                        </h6>
                                                        <small class="text-muted">
                                                            @if ($lapor->$field)
                                                                <i
                                                                    class="fas fa-check-circle text-success me-1"></i>Tersedia
                                                            @else
                                                                <i class="fas fa-times-circle text-danger me-1"></i>Belum
                                                                diunggah
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div>
                                                        @if ($lapor->$field)
                                                            @php
                                                                // Ambil ekstensi file dari path di database
                                                                $fileExtension = pathinfo(
                                                                    $lapor->$field,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm preview-file"
                                                                data-file-url="{{ route('lapor.view', ['id' => $lapor->id, 'field' => $field]) }}"
                                                                data-file-name="{{ $label }}"
                                                                data-file-extension="{{ $fileExtension }}">
                                                                <i class="fas fa-eye me-1"></i> Preview
                                                            </button>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Dokumen Tambahan -->
                                    <div class="col-md-6">
                                        <h6 class="text-info mb-3">Dokumen Tambahan</h6>

                                        @php
                                            $optionalDocuments = [
                                                'file_ktp_sim' => 'Kartu Identitas (KTP/SIM)',
                                                'file_npwp' => 'NPWP',
                                                'file_surat_ket_mengajar' => 'Surat Keterangan Mengajar',
                                            ];
                                        @endphp

                                        @foreach ($optionalDocuments as $field => $label)
                                            <div class="document-item mb-3 p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $label }}</h6>
                                                        <small class="text-muted">
                                                            @if ($lapor->$field)
                                                                <i
                                                                    class="fas fa-check-circle text-success me-1"></i>Tersedia
                                                            @else
                                                                <i
                                                                    class="fas fa-info-circle text-warning me-1"></i>Opsional
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <div>
                                                        @if ($lapor->$field)
                                                            @php
                                                                // Ambil ekstensi file dari path di database
                                                                $fileExtension = pathinfo(
                                                                    $lapor->$field,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                            @endphp
                                                            <button type="button"
                                                                class="btn btn-outline-primary btn-sm preview-file"
                                                                data-file-url="{{ route('lapor.view', ['id' => $lapor->id, 'field' => $field]) }}"
                                                                data-file-name="{{ $label }}"
                                                                data-file-extension="{{ $fileExtension }}">
                                                                <i class="fas fa-eye me-1"></i> Preview
                                                            </button>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Terakhir diupdate: {{ $lapor->updated_at->format('d F Y H:i') }}
                                </small>
                            </div>
                            <div>
                                <a href="{{ route('lapor.edit', $lapor->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit Data
                                </a>
                                <form action="{{ route('lapor.destroy', $lapor->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Preview File -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="filePreview">
                        <!-- Konten preview akan dimuat di sini -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="downloadLink" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .section-card {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
        }

        .section-body {
            padding: 1.25rem;
        }

        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .form-control-static {
            padding: 0.375rem 0;
            margin-bottom: 0;
            font-size: 1rem;
            line-height: 1.5;
            color: #212529;
            background-color: transparent;
            border: solid transparent;
            border-width: 1px 0;
        }

        .document-item {
            transition: all 0.3s ease;
        }

        .document-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .file-preview-container {
            width: 100%;
            height: 600px;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .file-preview-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .image-preview {
            max-width: 100%;
            max-height: 500px;
            display: block;
            margin: 0 auto;
        }

        .unsupported-file {
            padding: 2rem;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            const filePreview = document.getElementById('filePreview');
            const previewModalLabel = document.getElementById('previewModalLabel');
            const downloadLink = document.getElementById('downloadLink');

            // Event listener untuk tombol preview
            document.querySelectorAll('.preview-file').forEach(button => {
                button.addEventListener('click', function() {
                    const fileUrl = this.getAttribute('data-file-url');
                    const fileName = this.getAttribute('data-file-name');
                    const fileExtension = this.getAttribute('data-file-extension').toLowerCase();

                    console.log('File URL:', fileUrl);
                    console.log('File Extension:', fileExtension);

                    // Set judul modal
                    previewModalLabel.textContent = `Preview: ${fileName}`;

                    // Set link download - perbaiki URL download
                    const downloadUrl = fileUrl.replace('/view/', '/download/');
                    downloadLink.href = downloadUrl;

                    // Tampilkan loading
                    filePreview.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat dokumen...</p>
                </div>
            `;

                    // Tampilkan modal
                    previewModal.show();

                    // Load preview berdasarkan tipe file
                    loadFilePreview(fileUrl, filePreview, fileExtension);
                });
            });

            function loadFilePreview(fileUrl, container, fileExtension) {
                console.log('Loading preview for extension:', fileExtension);

                if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(fileExtension)) {
                    // Preview gambar
                    container.innerHTML = `
                <div class="text-center">
                    <img src="${fileUrl}" alt="Preview" class="image-preview"
                         onerror="this.onerror=null; this.src='//via.placeholder.com/400x300?text=Gambar+Tidak+Dapat+Dimuat'">
                    <p class="mt-2 text-muted">Gambar - ${fileExtension.toUpperCase()}</p>
                </div>
            `;
                } else if (fileExtension === 'pdf') {
                    // Preview PDF
                    container.innerHTML = `
                <div class="file-preview-container">
                    <iframe src="${fileUrl}" title="PDF Preview"></iframe>
                </div>
            `;
                } else {
                    // File tidak didukung untuk preview
                    container.innerHTML = `
                <div class="unsupported-file">
                    <i class="fas fa-file fa-3x text-muted mb-3"></i>
                    <h5>Preview tidak tersedia</h5>
                    <p class="text-muted">File dengan format .${fileExtension} tidak dapat ditampilkan preview-nya.</p>
                    <p>Silakan download file untuk melihat isinya.</p>
                </div>
            `;
                }
            }

            // Reset modal ketika ditutup
            document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
                filePreview.innerHTML = '';
            });
        });
    </script>
@endsection
