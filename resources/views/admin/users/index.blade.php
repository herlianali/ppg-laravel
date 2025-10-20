@extends('layouts.app')

@section('title', 'Management User')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Management User</h5>
                        <div>
                            <!-- Import Button -->
                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-file-import me-2"></i>Import Excel
                            </button>
                            <!-- Add User Button -->
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah User
                            </a>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        @if ($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Dibuat</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if ($user->role === 'admin')
                                                        <span class="badge bg-danger">Admin</span>
                                                    @elseif($user->role === 'verifikator')
                                                        <span class="badge bg-warning text-dark">Verifikator</span>
                                                    @else
                                                        <span class="badge bg-success">Mahasiswa</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->phone ?? '-' }}</td>
                                                <td>{{ $user->address ?? '-' }}</td>
                                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <!-- Edit -->
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                                            class="btn btn-sm btn-info" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- Hapus -->
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                <h5>Belum ada user</h5>
                                <p class="text-muted">Mulai dengan menambahkan user pertama Anda</p>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                        data-bs-target="#importModal">
                                        <i class="fas fa-file-import me-2"></i>Import Excel
                                    </button>
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah User
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel/CSV</label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">
                                Format file yang didukung: .xlsx, .xls, .csv
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Import:</h6>
                            <ul class="mb-0">
                                <li>Download template terlebih dahulu</li>
                                <li>Gunakan kolom: nama, email, password, role, telepon, alamat</li>
                                <li>Role yang valid: admin, verifikator, mahasiswa</li>
                                <li>Password akan di-hash secara otomatis</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('admin.users.download-template') }}" class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Download Template
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Import Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(button) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                button.closest('form').submit();
            }
        }

        // Show file name when selected
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('label[for="file"]');
                label.textContent = `File terpilih: ${fileName}`;
            }
        });
    </script>
@endpush

<style>
    .action-buttons {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .action-buttons .btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>
