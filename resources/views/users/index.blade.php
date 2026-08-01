@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Inventaris Dewi Catering')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Manajemen Pengguna</h4>
        <small class="text-muted">Kelola akun akses sistem inventaris</small>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Terdaftar</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->role == 'admin')
                                <span class="badge bg-danger">ADMIN</span>
                            @else
                                <span class="badge bg-secondary">STAF</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-3">
                            @if(auth()->user()->id !== $u->id)
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection