@extends('layouts.app')

@section('title', 'Tambahkan Produk Baru - Inventaris Dewi Catering')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-box-open me-2"></i>Tambahkan Produk Baru</h4>
    <small class="text-muted">Form pendaftaran bahan baku / produk inventaris</small>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        {{-- Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i> Mohon periksa kembali inputan Anda:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', 'PRD-' . sprintf('%03d', rand(1, 999))) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Daging Ayam" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori</label>
                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', 'Bahan Baku') }}" placeholder="Contoh: Bahan Baku">
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}" placeholder="50000" required>
                    @error('purchase_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Satuan (Unit)</label>
                    <select name="unit" class="form-select @error('unit') is-invalid @enderror">
                        <option value="Kg" {{ old('unit') == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Liter" {{ old('unit') == 'Liter' ? 'selected' : '' }}>Liter</option>
                        <option value="Pcs" {{ old('unit') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="Ikat" {{ old('unit') == 'Ikat' ? 'selected' : '' }}>Ikat</option>
                        <option value="Pack" {{ old('unit') == 'Pack' ? 'selected' : '' }}>Pack</option>
                        <option value="Dus" {{ old('unit') == 'Dus' ? 'selected' : '' }}>Dus</option>
                    </select>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Supplier (Opsional)</label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                        <option value="">-- Tanpa Supplier / Pilih Nanti --</option>
                        @if(isset($suppliers))
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name ?? $supplier->nama_supplier ?? $supplier->nama }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('supplier_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan / Deskripsi</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Deskripsi opsional...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-1"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection