<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung total data dasar
        $totalProduk = Product::count();
        $totalSupplier = Supplier::count();
        $totalPesanan = PurchaseOrder::count();
        $totalPO = $totalPesanan; // Alias variabel

        // 2. Hitung Stok Menipis (Mengecek nama kolom 'stok' vs 'stock')
        if (Schema::hasColumn('products', 'stok')) {
            $stokMenipis = Product::where('stok', '<=', 5)->count();
        } elseif (Schema::hasColumn('products', 'stock')) {
            $stokMenipis = Product::where('stock', '<=', 5)->count();
        } else {
            $stokMenipis = 0;
        }

        // 3. Hitung Total Pengeluaran (Mengecek nama kolom 'total_harga' vs 'total_price')
        if (Schema::hasColumn('purchase_orders', 'total_harga')) {
            $totalPengeluaran = PurchaseOrder::where('status', 'received')->sum('total_harga');
        } elseif (Schema::hasColumn('purchase_orders', 'total_price')) {
            $totalPengeluaran = PurchaseOrder::where('status', 'received')->sum('total_price');
        } else {
            $totalPengeluaran = 0;
        }

        // 4. Ambil 5 Transaksi PO Terbaru untuk Tabel Dasbor
        $latestPOs = PurchaseOrder::with(['supplier', 'product'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // 5. Data untuk Pie Chart (Status PO: Pending, Received, Cancelled)
        $statusCounts = PurchaseOrder::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pieChartData = [
            ['name' => 'Pending', 'y' => $statusCounts['pending'] ?? 0],
            ['name' => 'Received / Selesai', 'y' => $statusCounts['received'] ?? 0],
            ['name' => 'Cancelled / Batal', 'y' => $statusCounts['cancelled'] ?? 0],
        ];

        // 6. Data untuk Bar Chart (Top Supplier Berdasarkan Jumlah PO)
        $supplierData = Supplier::withCount('purchaseOrders')
            ->orderBy('purchase_orders_count', 'desc')
            ->take(5)
            ->get();

        $supplierNames = $supplierData->pluck('name')->toArray();
        $supplierPoCounts = $supplierData->pluck('purchase_orders_count')->toArray();

        // Send all required variables to dashboard view
        return view('dashboard', compact(
            'totalProduk',
            'totalSupplier',
            'totalPesanan',
            'totalPO',
            'stokMenipis',
            'totalPengeluaran',
            'latestPOs',
            'pieChartData',
            'supplierNames',
            'supplierPoCounts'
        ));
    }
}