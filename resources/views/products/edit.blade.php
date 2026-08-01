@extends('layouts.app')

@section('title', 'Edit Produk - Inventaris Dewi Catering')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Produk</h4>
    <small class="text-muted">Perbarui informasi data produk/bahan baku</small>
</div>

<div class="card border-0 shadow-sm col-md-8">
    <div class="card-body p-4">

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

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. Nama Produk --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk / Bahan Baku <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? $product->nama_produk ?? $product->nama) }}" required>
            </div>

            <div class="row g-3 mb-3">
                {{-- 2. Harga Beli --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $product->purchase_price ?? $product->harga_beli ?? $product->price ?? $product->harga ?? 0) }}" required>
                </div>

                {{-- 3. Stok --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? $product->stok ?? 0) }}" required>
                </div>
            </div>

            {{-- 4. Supplier --}}
            @if(isset($suppliers) && count($suppliers) > 0)
            <div class="mb-4">
                <label class="form-label fw-semibold">Supplier Utama</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- Pilih Supplier (Opsional) --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id ?? $product->id_supplier) == $supplier->id) ? 'selected' : '' }}>
                            {{ $supplier->name ?? $supplier->nama_supplier ?? $supplier->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection