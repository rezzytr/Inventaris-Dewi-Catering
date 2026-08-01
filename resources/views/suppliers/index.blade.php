@extends('layouts.app')

@section('title', 'Daftar Supplier - Inventaris Dewi Catering')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-truck me-2"></i>Daftar Supplier</h5>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Tambah Supplier
        </a>
    </div>
    <div class="card-body p-4">

        <!-- Pesan Sukses -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Kode</th>
                        <th>Nama Supplier</th>
                        <th>Kontak Person</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $index => $item)
                        <tr>
                            <td>{{ $suppliers->firstItem() + $index }}</td>
                            <td><span class="badge bg-secondary">{{ $item->kode_supplier }}</span></td>
                            <td class="fw-bold">{{ $item->nama_supplier }}</td>
                            <td>{{ $item->kontak_person ?? '-' }}</td>
                            <td>{{ $item->no_telepon ?? '-' }}</td>
                            <td>{{ $item->alamat ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('supplier.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data supplier. Klik button "Tambah Supplier" untuk mengisi data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection