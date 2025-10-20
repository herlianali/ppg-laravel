@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Tambah Bidang Studi</h2>
        <form action="{{ route('admin.bidang-studi.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Bidang Studi</label>
                <input type="text" class="form-control" name="name" required value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('admin.bidang-studi.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
