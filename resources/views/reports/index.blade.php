@extends('layouts.app')

@section('title', 'Laporan Pembelian - Inventaris Dewi Catering')

@section('content')
<!-- CSS Khusus Cetak / Print -->
<style>
    @media print {
        body { background-color: #fff !important; }
        .sidebar, .navbar, .btn-print, .filter-card, .no-print { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
        .print-header { display: block !important; margin-bottom: 20px; }
    }
    .print-header { display: none; }
</style>

<!-- Kop Cetak (Hanya Muncul Saat Print) -->
<div class="print-header text-center">
    <h2>DEWI CATERING</h2>
    <p class="mb-1">Laporan Rekapitulasi Pesanan Pembelian (PO)</p>
    <small>Periode: {{ Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</small>
    <hr>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-0">Laporan Pembelian</h4>
        <small class="text-muted">Rekapitulasi data transaksi belanja bahan baku</small>
    </div>
    <button onclick="window.print()" class="btn btn-danger btn-print">
        <i class="fa-solid fa-file-pdf me-2"></i>Cetak / Export PDF
    </button>
</div>

<!-- Filter Card -->
<div class="card border-0 shadow-sm mb-4 filter-card">
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status Transaksi</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Selesai (Received)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal (Cancelled)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('laporan.index') }}" class="btn btn-light border w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <span class="text-muted small">Total Transaksi</span>
            <h4 class="fw-bold mb-0">{{ $totalPO }} Order</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <span class="text-muted small">Pesanan Selesai</span>
            <h4 class="fw-bold text-success mb-0">{{ $poSelesai }} Order</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <span class="text-muted small">Pesanan Pending</span>
            <h4 class="fw-bold text-warning mb-0">{{ $poPending }} Order</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <span class="text-muted small">Total Pengeluaran (Selesai)</span>
            <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No. PO</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td class="ps-3 fw-bold">{{ $po->po_number }}</td>
                        <td>{{ Carbon\Carbon::parse($po->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $po->supplier->name ?? '-' }}</td>
                        <td>{{ $po->product->name ?? '-' }}</td>
                        <td>{{ $po->quantity }}</td>
                        <td>Rp {{ number_format($po->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($po->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($po->status == 'received')
                                <span class="badge bg-success">Received</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data transaksi pada rentang tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection