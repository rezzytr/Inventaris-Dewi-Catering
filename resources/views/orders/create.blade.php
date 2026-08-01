@extends('layouts.app')

@section('title', 'Buat Pesanan Pembelian (PO) - Inventaris Dewi Catering')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-cart-plus me-2"></i>Buat Pesanan Pembelian (PO)</h4>
    <small class="text-muted">Form pengajuan pesanan bahan baku ke supplier</small>
</div>

<div class="card border-0 shadow-sm col-md-9">
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

        <form action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pilih Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name ?? $supplier->nama_supplier ?? $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal PO <span class="text-danger">*</span></label>
                    <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Pilih Produk / Bahan Baku <span class="text-danger">*</span></label>
                    <!-- Input Produk ID (Mengirim product_id & produk_id) -->
                    <select name="product_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name ?? $product->nama_produk ?? $product->nama }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Hidden fallback untuk database dengan nama kolom produk_id --}}
                    <input type="hidden" name="produk_id" id="hidden_produk_id">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah / Kuantitas <span class="text-danger">*</span></label>
                    <!-- Input Jumlah (Mengirim quantity & jumlah) -->
                    <input type="number" name="quantity" id="input_quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
                    {{-- Hidden fallback untuk database dengan nama kolom jumlah --}}
                    <input type="hidden" name="jumlah" id="hidden_jumlah" value="1">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan Pesanan</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Catatan opsional...">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-paper-plane me-1"></i> Simpan & Buat PO
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Sinkronkan nilai produk_id dan jumlah secara otomatis
    document.addEventListener('DOMContentLoaded', function() {
        const selectProduct = document.querySelector('select[name="product_id"]');
        const hiddenProduct = document.getElementById('hidden_produk_id');
        const inputQty = document.getElementById('input_quantity');
        const hiddenJumlah = document.getElementById('hidden_jumlah');

        if(selectProduct && hiddenProduct) {
            selectProduct.addEventListener('change', function() {
                hiddenProduct.value = this.value;
            });
        }

        if(inputQty && hiddenJumlah) {
            inputQty.addEventListener('input', function() {
                hiddenJumlah.value = this.value;
            });
        }
    });
</script>
@endsection