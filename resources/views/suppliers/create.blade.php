@extends('layouts.app')

@section('title', 'Tambahkan Supplier - Inventaris Dewi Catering')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-truck-field me-2"></i>Tambah Supplier Baru</h5>
    </div>
    <div class="card-body p-4">
        
        <!-- Notifikasi Error Validasi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Kode Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="kode_supplier" class="form-control" value="{{ old('kode_supplier') }}" placeholder="Contoh: SUP-001" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="nama_supplier" class="form-control" value="{{ old('nama_supplier') }}" placeholder="Contoh: Supplier Daging Ayam" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kontak Person (PJ)</label>
                    <input type="text" name="kontak_person" class="form-control" value="{{ old('kontak_person') }}" placeholder="Contoh: Bpk. Ahmad">
                </div>

                <div class="col-md-6">
                    <label class="form-label">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat kantor / gudang supplier...">{{ old('alamat') }}</textarea>
                </div>

                <div class="col-12 text-end mt-4">
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Supplier</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection