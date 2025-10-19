@extends('layouts.app')

@section('title', 'Form Lapor Diri')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary mb-5">
                        <h5 class="mb-0 text-white">Form Lapor Diri PPG Tahap 3 Tahun 2025</h5>
                    </div>
                    <div class="card-body">
                        {{-- Progress Bar --}}
                        <div class="progress mb-4" style="height: 30px;">
                            <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 20%;"
                                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                Step 1 dari 5
                            </div>
                        </div>

                        {{-- Alert untuk error validasi --}}
                        <div id="validationAlert" class="alert alert-danger d-none" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span id="alertMessage">Harap lengkapi semua field yang wajib diisi sebelum melanjutkan.</span>
                        </div>

                        <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data"
                            id="multiStepForm">
                            @csrf
                            
                            {{-- Hidden field untuk menyimpan step terakhir --}}
                            <input type="hidden" name="current_step" id="currentStepInput" value="{{ old('current_step', 1) }}">

                            {{-- STEP 1: Biodata --}}
                            <div class="step" id="step-1">
                                <h5 class="mb-3">1. Biodata Pribadi</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap (Tanpa Gelar) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" class="form-control required" 
                                           value="{{ old('nama_lengkap') }}" required>
                                    @error('nama_lengkap')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control"
                                               value="{{ old('tempat_lahir') }}">
                                        @error('tempat_lahir')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control required" 
                                               value="{{ old('tanggal_lahir') }}" required>
                                        @error('tanggal_lahir')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span
                                                class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select required" required>
                                            <option value="">--Pilih--</option>
                                            <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Agama <span
                                                class="text-danger">*</span></label>
                                        <select name="agama" class="form-select required" required>
                                            <option value="">--Pilih Agama--</option>
                                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        </select>
                                        @error('agama')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nomor KK</label>
                                        <input type="text" name="no_kk" class="form-control" maxlength="16"
                                               value="{{ old('no_kk') }}">
                                        @error('no_kk')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">NUPTK</label>
                                        <input type="text" name="nuptk" class="form-control"
                                               value="{{ old('nuptk') }}">
                                        @error('nuptk')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Asal Perguruan Tinggi</label>
                                        <input type="text" name="asal_pt" class="form-control"
                                               value="{{ old('asal_pt') }}">
                                        @error('asal_pt')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">IPK</label>
                                        <input type="number" step="0.01" name="ipk" class="form-control"
                                               value="{{ old('ipk') }}">
                                        @error('ipk')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nomor HP</label>
                                    <input type="text" name="no_hp" class="form-control"
                                           value="{{ old('no_hp') }}">
                                    @error('no_hp')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-primary"
                                        onclick="validateStep(1)">Next</button>
                                </div>
                            </div>

                            {{-- STEP 2: Alamat --}}
                            <div class="step d-none" id="step-2">
                                <h5 class="mb-3">2. Alamat Domisili</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Kelurahan</label>
                                        <input type="text" name="kelurahan" class="form-control"
                                               value="{{ old('kelurahan') }}">
                                        @error('kelurahan')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Kecamatan</label>
                                        <input type="text" name="kecamatan" class="form-control"
                                               value="{{ old('kecamatan') }}">
                                        @error('kecamatan')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Kabupaten/Kota</label>
                                        <input type="text" name="kabupaten" class="form-control"
                                               value="{{ old('kabupaten') }}">
                                        @error('kabupaten')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control"
                                               value="{{ old('provinsi') }}">
                                        @error('provinsi')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Kode Pos</label>
                                        <input type="text" name="kode_pos" class="form-control"
                                               value="{{ old('kode_pos') }}">
                                        @error('kode_pos')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="prevStep()">Previous</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="validateStep(2)">Next</button>
                                </div>
                            </div>

                            {{-- STEP 3: Orang Tua --}}
                            <div class="step d-none" id="step-3">
                                <h5 class="mb-3">3. Data Orang Tua</h5>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" class="form-control"
                                               value="{{ old('nama_ayah') }}">
                                        @error('nama_ayah')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" class="form-control"
                                               value="{{ old('nama_ibu') }}">
                                        @error('nama_ibu')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir Ayah</label>
                                        <input type="date" name="tgl_lahir_ayah" class="form-control"
                                               value="{{ old('tgl_lahir_ayah') }}">
                                        @error('tgl_lahir_ayah')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir Ibu</label>
                                        <input type="date" name="tgl_lahir_ibu" class="form-control"
                                               value="{{ old('tgl_lahir_ibu') }}">
                                        @error('tgl_lahir_ibu')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Ayah</label>
                                        <input type="text" name="pendidikan_ayah" class="form-control"
                                               value="{{ old('pendidikan_ayah') }}">
                                        @error('pendidikan_ayah')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Ibu</label>
                                        <input type="text" name="pendidikan_ibu" class="form-control"
                                               value="{{ old('pendidikan_ibu') }}">
                                        @error('pendidikan_ibu')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="prevStep()">Previous</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="validateStep(3)">Next</button>
                                </div>
                            </div>

                            {{-- STEP 4: Upload Dokumen --}}
                            <div class="step d-none" id="step-4">
                                <h5 class="mb-3">4. Upload Dokumen</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">1. Pakta Integritas (PDF/JPG/PNG) <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="file_pakta_integritas" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_pakta_integritas')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">2. Biodata Mahasiswa <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="file_biodata_mahasiswa" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_biodata_mahasiswa')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">3. Scan Ijazah S1/D4 (Legalisir) <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="file_ijazah" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_ijazah')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">4. Transkrip Nilai S1/D4 <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="file_transkrip" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_transkrip')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">5. Kartu Identitas (KTP/SIM)</label>
                                    <input type="file" name="file_ktp_sim" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_ktp_sim')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">7. Unggah Scan Surat Keterangan Sehat (dari
                                        fasilitas layanan kesehatan) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_surat_sehat" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_surat_sehat')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">8. Unggah Scan SKCK Surat Keterangan Catatan
                                        Kepolisian dari Kepolisian <span class="text-danger">*</span></label>
                                    <input type="file" name="file_skck" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_skck')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">9. Unggah Scan NPWP (bagi yang memiliki)</label>
                                    <input type="file" name="file_npwp" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_npwp')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">10. Unggah Scan Surat Bebas Narkotika,
                                        Psikotropika, dan Zat adiktif lainnya/NAPZA (dari Puskesmas/RSUD
                                        setempat/Kepolisian/BNN) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_napza" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_napza')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">11. Unggah Surat Keterangan Izin Dari Kepala
                                        Sekolah <span class="text-danger">*</span></label>
                                    <input type="file" name="file_ijin_ks" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_ijin_ks')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">12. Unggah Pas Foto Berwarna dimensi 4 x 6
                                        (Sesuai Ketentuan) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_foto" class="form-control required"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_foto')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">13. Bukti Keterangan Mengajar Mata Pelajaran
                                        Sesuai Bidang Study dari Kepala Sekolah (Wajib Mengunggah Bagi Yang Tidak Linear)
                                    </label>
                                    <input type="file" name="file_surat_ket_mengajar" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Maks. 10MB</small>
                                    @error('file_surat_ket_mengajar')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="prevStep()">Previous</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="validateStep(4)">Next</button>
                                </div>
                            </div>

                            {{-- STEP 5: Konfirmasi --}}
                            <div class="step d-none" id="step-5">
                                <h5 class="mb-3">5. Konfirmasi & Kirim</h5>
                                <p>✅ Pastikan semua data dan dokumen sudah benar.</p>
                                <p class="text-danger">Setelah data dikirim, Anda tidak dapat mengedit kembali.</p>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="prevStep()">Previous</button>
                                    <button type="submit" class="btn btn-success">Kirim Data</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = {{ old('current_step', 1) }};
        const totalSteps = 5;

        function showStep(step) {
            // Sembunyikan semua step
            document.querySelectorAll('.step').forEach((el) => {
                el.classList.add('d-none');
            });

            // Tampilkan step yang aktif
            const activeStep = document.getElementById(`step-${step}`);
            if (activeStep) {
                activeStep.classList.remove('d-none');
            }

            // Update progress bar
            const progressPercentage = (step / totalSteps) * 100;
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                progressBar.style.width = `${progressPercentage}%`;
                progressBar.textContent = `Step ${step} dari ${totalSteps}`;
                progressBar.setAttribute('aria-valuenow', progressPercentage);
            }

            // Update hidden input untuk current step
            document.getElementById('currentStepInput').value = step;

            // Sembunyikan alert validasi
            hideValidationAlert();

            // Scroll ke atas form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            // Dapatkan semua input required di step saat ini
            const currentStepElement = document.getElementById(`step-${step}`);
            const requiredInputs = currentStepElement.querySelectorAll('.required');

            let isValid = true;
            let firstInvalidInput = null;

            // Validasi setiap input required
            requiredInputs.forEach(input => {
                // Reset tampilan error
                input.classList.remove('is-invalid');

                // Cek apakah input kosong
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');

                    // Simpan input pertama yang invalid untuk fokus
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }

                // Validasi khusus untuk select
                if (input.tagName === 'SELECT' && input.value === '') {
                    isValid = false;
                    input.classList.add('is-invalid');

                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }

                // Validasi khusus untuk file
                if (input.type === 'file' && !input.files.length) {
                    isValid = false;
                    input.classList.add('is-invalid');

                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                }
            });

            if (isValid) {
                // Jika valid, lanjut ke step berikutnya
                nextStep();
            } else {
                // Jika tidak valid, tampilkan alert dan fokus ke input pertama yang kosong
                showValidationAlert('Harap lengkapi semua field yang wajib diisi sebelum melanjutkan.');

                if (firstInvalidInput) {
                    firstInvalidInput.focus();

                    // Scroll ke input yang invalid
                    firstInvalidInput.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function showValidationAlert(message) {
            const alert = document.getElementById('validationAlert');
            const alertMessage = document.getElementById('alertMessage');

            if (alert && alertMessage) {
                alertMessage.textContent = message;
                alert.classList.remove('d-none');

                // Scroll ke atas form untuk melihat alert
                alert.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        function hideValidationAlert() {
            const alert = document.getElementById('validationAlert');
            if (alert) {
                alert.classList.add('d-none');
            }

            // Hapus kelas error dari semua input
            document.querySelectorAll('.is-invalid').forEach(input => {
                input.classList.remove('is-invalid');
            });
        }

        // Inisialisasi step berdasarkan session atau default
        document.addEventListener('DOMContentLoaded', function() {
            showStep(currentStep);

            // Tambahkan event listener untuk menghilangkan error saat input diisi
            document.querySelectorAll('.required').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });

                // Untuk select
                input.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.remove('is-invalid');
                    }
                });

                // Untuk file
                input.addEventListener('change', function() {
                    if (this.files.length) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Tampilkan error validasi server side jika ada
            @if($errors->any())
                showValidationAlert('Terjadi kesalahan validasi. Silakan periksa form Anda.');
                
                // Highlight field yang error
                @foreach($errors->keys() as $key)
                    const errorField = document.querySelector('[name="{{ $key }}"]');
                    if (errorField) {
                        errorField.classList.add('is-invalid');
                    }
                @endforeach
            @endif
        });
    </script>

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .required:after {
            content: " *";
            color: #dc3545;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }
    </style>
@endsection