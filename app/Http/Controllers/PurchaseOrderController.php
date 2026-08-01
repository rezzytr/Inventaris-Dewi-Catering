<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier'])->latest()->get();

        return view('orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'product_id'  => 'required',
            'quantity'    => 'required|numeric|min:1',
        ]);

        $productId = $request->product_id;
        $qty       = $request->quantity;

        // Ambil data produk untuk hitung total harga
        $product = Product::find($productId);
        $price = 0;
        if ($product) {
            $price = $product->purchase_price 
                ?? $product->harga_beli 
                ?? $product->price 
                ?? $product->harga 
                ?? 0;
        }

        $totalPrice = $price * $qty;
        $data = [];

        // 1. Nomor PO
        $poNum = 'PO-' . time();
        if (Schema::hasColumn('purchase_orders', 'po_number')) $data['po_number'] = $poNum;
        if (Schema::hasColumn('purchase_orders', 'nomor_po')) $data['nomor_po'] = $poNum;

        // 2. Supplier
        if (Schema::hasColumn('purchase_orders', 'supplier_id')) $data['supplier_id'] = $request->supplier_id;
        if (Schema::hasColumn('purchase_orders', 'id_supplier')) $data['id_supplier'] = $request->supplier_id;

        // 3. Tanggal PO
        $inputDate = $request->order_date ?? date('Y-m-d');
        if (Schema::hasColumn('purchase_orders', 'tanggal_po')) $data['tanggal_po'] = $inputDate;
        if (Schema::hasColumn('purchase_orders', 'order_date')) $data['order_date'] = $inputDate;
        if (Schema::hasColumn('purchase_orders', 'tanggal'))    $data['tanggal']    = $inputDate;

        // 4. Total Harga
        if (Schema::hasColumn('purchase_orders', 'total_price')) $data['total_price'] = $totalPrice;
        if (Schema::hasColumn('purchase_orders', 'total_harga')) $data['total_harga'] = $totalPrice;

        // 5. Catatan
        if (Schema::hasColumn('purchase_orders', 'notes'))   $data['notes']   = $request->notes;
        if (Schema::hasColumn('purchase_orders', 'catatan')) $data['catatan'] = $request->notes;

        // 6. Status Default
        if (Schema::hasColumn('purchase_orders', 'status')) {
            $data['status'] = $this->getExactEnumFor('pending');
        }

        // Simpan ke tabel purchase_orders
        $po = PurchaseOrder::create($data);

        // 7. Simpan detail ke tabel purchase_order_items
        if (Schema::hasTable('purchase_order_items')) {
            $itemData = [
                'purchase_order_id' => $po->id,
                'created_at'        => now(),
                'updated_at'        => now()
            ];

            // Deteksi kolom Produk ID
            if (Schema::hasColumn('purchase_order_items', 'product_id')) $itemData['product_id'] = $productId;
            if (Schema::hasColumn('purchase_order_items', 'produk_id'))  $itemData['produk_id']  = $productId;
            if (Schema::hasColumn('purchase_order_items', 'id_produk'))  $itemData['id_produk']  = $productId;

            // Deteksi kolom Kuantitas / Jumlah
            if (Schema::hasColumn('purchase_order_items', 'quantity')) $itemData['quantity'] = $qty;
            if (Schema::hasColumn('purchase_order_items', 'jumlah'))   $itemData['jumlah']   = $qty;
            if (Schema::hasColumn('purchase_order_items', 'qty'))      $itemData['qty']      = $qty;

            // Deteksi kolom Harga & Subtotal
            if (Schema::hasColumn('purchase_order_items', 'harga_satuan')) $itemData['harga_satuan'] = $price;
            if (Schema::hasColumn('purchase_order_items', 'price'))        $itemData['price']        = $price;
            if (Schema::hasColumn('purchase_order_items', 'harga'))        $itemData['harga']        = $price;
            if (Schema::hasColumn('purchase_order_items', 'subtotal'))     $itemData['subtotal']     = $totalPrice;

            DB::table('purchase_order_items')->insert($itemData);
        }

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(string $id)
    {
        $po = PurchaseOrder::with(['supplier'])->findOrFail($id);
        return view('orders.show', compact('po'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate(['status' => 'required']);

        DB::transaction(function () use ($request, $id) {
            $po = PurchaseOrder::findOrFail($id);
            $oldStatus = strtolower($po->status ?? '');
            
            $exactStatus = $this->getExactEnumFor($request->status);
            $newStatusLower = strtolower($exactStatus);

            // Ambil data item dari purchase_order_items
            $item = DB::table('purchase_order_items')->where('purchase_order_id', $po->id)->first();

            if ($item) {
                $productId = $item->product_id ?? $item->produk_id ?? $item->id_produk ?? null;
                $qty       = $item->quantity ?? $item->jumlah ?? $item->qty ?? 0;

                // Jika status berubah ke Received/Selesai -> Stok Bertambah
                if (!in_array($oldStatus, ['received', 'diterima', 'selesai', 'done']) && in_array($newStatusLower, ['received', 'diterima', 'selesai', 'done']) && $productId) {
                    $product = Product::find($productId);
                    if ($product) {
                        if (Schema::hasColumn('products', 'stok')) $product->increment('stok', $qty);
                        elseif (Schema::hasColumn('products', 'stock')) $product->increment('stock', $qty);
                    }
                }

                // Jika status berubah dari Received kembali ke Pending/Cancelled -> Stok Dikurangi
                if (in_array($oldStatus, ['received', 'diterima', 'selesai', 'done']) && !in_array($newStatusLower, ['received', 'diterima', 'selesai', 'done']) && $productId) {
                    $product = Product::find($productId);
                    if ($product) {
                        if (Schema::hasColumn('products', 'stok')) $product->decrement('stok', $qty);
                        elseif (Schema::hasColumn('products', 'stock')) $product->decrement('stock', $qty);
                    }
                }
            }

            $po->update(['status' => $exactStatus]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Status Purchase Order berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        // Hapus item terkait jika ada
        if (Schema::hasTable('purchase_order_items')) {
            DB::table('purchase_order_items')->where('purchase_order_id', $po->id)->delete();
        }

        $po->delete();

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }

    private function getExactEnumFor(string $target): string
    {
        $targetLower = strtolower(trim($target));

        try {
            $column = DB::select("SHOW COLUMNS FROM purchase_orders WHERE Field = 'status'");
            if (!empty($column) && isset($column[0]->Type)) {
                $type = $column[0]->Type;
                if (preg_match('/enum\((.*)\)$/i', $type, $matches)) {
                    $enums = array_map(fn($v) => trim($v, "'"), explode(',', $matches[1]));

                    if (str_contains($targetLower, 'cancel') || str_contains($targetLower, 'batal')) {
                        foreach ($enums as $e) {
                            if (str_contains(strtolower($e), 'cancel') || str_contains(strtolower($e), 'batal')) return $e;
                        }
                    }

                    if (str_contains($targetLower, 'receive') || str_contains($targetLower, 'selesai') || str_contains($targetLower, 'terima')) {
                        foreach ($enums as $e) {
                            if (str_contains(strtolower($e), 'receive') || str_contains(strtolower($e), 'selesai') || str_contains(strtolower($e), 'terima')) return $e;
                        }
                    }

                    if (str_contains($targetLower, 'pend') || str_contains($targetLower, 'tunggu') || str_contains($targetLower, 'proses')) {
                        foreach ($enums as $e) {
                            if (str_contains(strtolower($e), 'pend') || str_contains(strtolower($e), 'tunggu') || str_contains(strtolower($e), 'proses')) return $e;
                        }
                    }

                    foreach ($enums as $e) {
                        if (strtolower($e) === $targetLower) return $e;
                    }
                }
            }
        } catch (\Throwable $th) {}

        return $target;
    }
}