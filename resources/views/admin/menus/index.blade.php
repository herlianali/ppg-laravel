@extends('layouts.app')

@section('title', 'Management Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Management Menu</h5>
                    <div>
                        <!-- Batch Operations -->
                        <div class="btn-group me-2">
                            <form action="{{ route('admin.menus.activate-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Semua Menu">
                                    <i class="fas fa-eye me-1"></i>Aktifkan Semua
                                </button>
                            </form>
                            <form action="{{ route('admin.menus.deactivate-all') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" title="Nonaktifkan Semua Menu">
                                    <i class="fas fa-eye-slash me-1"></i>Nonaktifkan Semua
                                </button>
                            </form>
                        </div>
                        
                        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($menus->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Nama Menu</th>
                                        <th>Icon</th>
                                        <th>Route/URL</th>
                                        <th>Parent</th>
                                        <th>Order</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                        <th width="200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menus as $menu)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $menu->name }}</strong>
                                                @if($menu->children->count() > 0)
                                                    <span class="badge bg-info ms-2">{{ $menu->children->count() }} submenu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($menu->icon)
                                                    <i class="{{ $menu->icon }}"></i>
                                                    <small class="text-muted d-block">{{ $menu->icon }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($menu->route)
                                                    <span class="badge bg-primary">Route: {{ $menu->route }}</span>
                                                @elseif($menu->url)
                                                    <span class="badge bg-secondary">URL: {{ $menu->url }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($menu->parent)
                                                    <span class="badge bg-light text-dark">{{ $menu->parent->name }}</span>
                                                @else
                                                    <span class="badge bg-success">Parent</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $menu->order }}</span>
                                            </td>
                                            <td>
                                                @if($menu->permissions && count($menu->permissions) > 0)
                                                    @foreach($menu->permissions as $permission)
                                                        <span class="badge bg-info me-1 mb-1">{{ $permission }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">All Roles</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $menu->is_active ? 'success' : 'danger' }}">
                                                    {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Tombol Toggle Status -->
                                                    <form action="{{ route('admin.menus.toggle-status', $menu->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-{{ $menu->is_active ? 'warning' : 'success' }}" 
                                                                title="{{ $menu->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <i class="fas fa-{{ $menu->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" 
                                                       class="btn btn-sm btn-info" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Tombol Hapus -->
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete({{ $menu->id }})" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <form id="delete-form-{{ $menu->id }}" 
                                                      action="{{ route('admin.menus.destroy', $menu->id) }}" 
                                                      method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        
                                        <!-- Tampilkan children menus -->
                                        @foreach($menu->children as $child)
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <i class="fas fa-level-down-alt text-muted me-2"></i>
                                                    {{ $child->name }}
                                                </td>
                                                <td>
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }}"></i>
                                                        <small class="text-muted d-block">{{ $child->icon }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($child->route)
                                                        <span class="badge bg-primary">Route: {{ $child->route }}</span>
                                                    @elseif($child->url)
                                                        <span class="badge bg-secondary">URL: {{ $child->url }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $menu->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">{{ $child->order }}</span>
                                                </td>
                                                <td>
                                                    @if($child->permissions && count($child->permissions) > 0)
                                                        @foreach($child->permissions as $permission)
                                                            <span class="badge bg-info me-1 mb-1">{{ $permission }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">All Roles</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $child->is_active ? 'success' : 'danger' }}">
                                                        {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <!-- Tombol Toggle Status untuk Child -->
                                                        <form action="{{ route('admin.menus.toggle-status', $child->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-{{ $child->is_active ? 'warning' : 'success' }}" 
                                                                    title="{{ $child->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                                <i class="fas fa-{{ $child->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('admin.menus.edit', $child->id) }}" 
                                                           class="btn btn-sm btn-info" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="confirmDelete({{ $child->id }})" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <form id="delete-form-{{ $child->id }}" 
                                                          action="{{ route('admin.menus.destroy', $child->id) }}" 
                                                          method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list-alt fa-4x text-muted mb-3"></i>
                            <h5>Belum ada menu</h5>
                            <p class="text-muted">Mulai dengan membuat menu pertama Anda</p>
                            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Menu
                            </a>
                        </div>
                    @endif
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

    // Auto close alert setelah 3 detik
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);
    });
</script>
@endpush

<style>
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>