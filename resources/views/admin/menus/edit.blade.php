@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Menu: {{ $menu->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Menu <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="icon" class="form-label">Icon</label>
                                        <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                            id="icon" name="icon" value="{{ old('icon', $menu->icon) }}"
                                            placeholder="Contoh: fas fa-home">
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror>
                                        <small class="text-muted">
                                            Gunakan class FontAwesome. Contoh: fas fa-home, fas fa-user, dll.
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="route" class="form-label">Route Name</label>
                                        <input type="text" class="form-control @error('route') is-invalid @enderror"
                                            id="route" name="route" value="{{ old('route', $menu->route) }}"
                                            placeholder="Contoh: home.index">
                                        @error('route')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" class="form-control @error('url') is-invalid @enderror"
                                            id="url" name="url" value="{{ old('url', $menu->url) }}"
                                            placeholder="Contoh: /dashboard">
                                        @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Isi Route atau URL, tidak perlu kedua-duanya.
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_id" class="form-label">Parent Menu</label>
                                        <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id"
                                            name="parent_id">
                                            <option value="">-- Pilih Parent Menu --</option>
                                            @foreach ($parentMenus as $parent)
                                                <option value="{{ $parent->id }}"
                                                    {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="order" class="form-label">Order <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('order') is-invalid @enderror"
                                            id="order" name="order" value="{{ old('order', $menu->order) }}" required
                                            min="0">
                                        @error('order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Urutan tampilan menu (angka kecil akan tampil lebih dulu).
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Permissions</label>
                                        <div class="border rounded p-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="permission_admin"
                                                    name="permissions[]" value="admin"
                                                    {{ in_array('admin', old('permissions', $menu->permissions ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_admin">
                                                    Admin
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="permission_verifikator"
                                                    name="permissions[]" value="verifikator"
                                                    {{ in_array('verifikator', old('permissions', $menu->permissions ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_verifikator">
                                                    Verifikator
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="permission_user"
                                                    name="permissions[]" value="mahasiswa"
                                                    {{ in_array('mahasiswa', old('permissions', $menu->permissions ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_user">
                                                    Mahasiswa
                                                </label>
                                            </div>
                                            <small class="text-muted">
                                                Kosongkan semua untuk mengizinkan semua role mengakses menu ini.
                                            </small>
                                        </div>
                                        @error('permissions')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Menu Aktif
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            Nonaktifkan untuk menyembunyikan menu dari sidebar.
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <strong>Menu Aktif</strong>
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            Nonaktifkan untuk menyembunyikan menu dari sidebar.
                                            <br>Status saat ini:
                                            <span class="badge bg-{{ $menu->is_active ? 'success' : 'danger' }}">
                                                {{ $menu->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Menu
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $menu->id }})">
                                        <i class="fas fa-trash me-2"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </form>

                        <form id="delete-form-{{ $menu->id }}"
                            action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(menuId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Menu yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + menuId).submit();
                }
            });
        }
    </script>
@endpush
