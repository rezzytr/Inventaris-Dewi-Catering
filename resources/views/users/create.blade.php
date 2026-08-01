@extends('layouts.app')

@section('title', 'Tambah Pengguna - Inventaris Dewi Catering')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0">Tambah Pengguna Baru</h4>
    <small class="text-muted">Buat akun baru untuk akses sistem</small>
</div>

<div class="card border-0 shadow-sm col-md-6">
    <div class="card-body p-4">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="6">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Role / Hak Akses</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="staf" {{ old('role') == 'staf' ? 'selected' : '' }}>Staf Gudang</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                <a href="{{ route('users.index') }}" class="btn btn-light border px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection