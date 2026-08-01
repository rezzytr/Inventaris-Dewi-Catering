<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: Bulan ini jika tidak ada input tanggal
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data PO berdasarkan rentang tanggal menggunakan created_at
        $query = PurchaseOrder::with(['supplier', 'product'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Filter opsional berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchaseOrders = $query->latest('created_at')->get();

        // Hitung ringkasan
        $totalPengeluaran = $purchaseOrders->where('status', 'received')->sum('total_price');
        $totalPO = $purchaseOrders->count();
        $poSelesai = $purchaseOrders->where('status', 'received')->count();
        $poPending = $purchaseOrders->where('status', 'pending')->count();

        return view('reports.index', compact(
            'purchaseOrders', 
            'startDate', 
            'endDate', 
            'totalPengeluaran', 
            'totalPO', 
            'poSelesai', 
            'poPending'
        ));
    }
}