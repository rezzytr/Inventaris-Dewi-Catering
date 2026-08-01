@extends('layouts.app')

@section('title', 'Purchase Order (PO) - Inventaris Dewi Catering')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Purchase Order (PO)</h4>
        <small class="text-muted">Pengelolaan pesanan belanja bahan baku ke supplier</small>
    </div>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary fw-semibold">
        <i class="fa-solid fa-cart-plus me-1"></i> Buat PO Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. PO</th>
                        <th>Supplier</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    @php
                        // Ambil item dari tabel purchase_order_items
                        $item = \Illuminate\Support\Facades\DB::table('purchase_order_items')
                            ->where('purchase_order_id', $po->id)
                            ->first();

                        $productName = '-';
                        $qty = 0;

                        if ($item) {
                            $productId = $item->product_id ?? $item->produk_id ?? $item->id_produk ?? null;
                            $qty       = $item->quantity ?? $item->jumlah ?? $item->qty ?? 0;

                            if ($productId) {
                                $pObj = \App\Models\Product::find($productId);
                                if ($pObj) {
                                    $productName = $pObj->name ?? $pObj->nama_produk ?? $pObj->nama ?? '-';
                                }
                            }
                        }

                        $totalPrice = $po->total_price ?? $po->total_harga ?? 0;

                        // Normalisasi Status untuk Select Option
                        $st = strtolower($po->status ?? '');
                        $isCancelled = str_contains($st, 'cancel') || str_contains($st, 'batal');
                        $isReceived  = str_contains($st, 'receive') || str_contains($st, 'selesai') || str_contains($st, 'terima');
                        $isPending   = !$isCancelled && !$isReceived;
                    @endphp
                    <tr>
                        <td class="ps-3 fw-bold text-primary">
                            {{ $po->po_number ?? $po->nomor_po ?? 'PO-'.$po->id }}
                        </td>
                        <td>
                            {{ $po->supplier->name ?? $po->supplier->nama_supplier ?? $po->supplier->nama ?? '-' }}
                        </td>
                        <td class="fw-semibold">
                            {{ $productName }}
                        </td>
                        <td>
                            <span class="badge bg-secondary px-2 py-1">{{ $qty }}</span>
                        </td>
                        <td class="fw-semibold">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </td>
                        <td style="min-width: 180px;">
                            <form action="{{ route('purchase-orders.update-status', $po->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm border-secondary fw-semibold" onchange="this.form.submit()">
                                    <option value="pending" {{ $isPending ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="received" {{ $isReceived ? 'selected' : '' }}>
                                        Received (Selesai)
                                    </option>
                                    <option value="cancelled" {{ $isCancelled ? 'selected' : '' }}>
                                        Cancelled (Batal)
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end pe-3">
                            <form action="{{ route('purchase-orders.destroy', $po->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus PO ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data Purchase Order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection