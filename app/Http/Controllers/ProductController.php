<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('supplier')->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('products.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'purchase_price' => 'required|numeric',
            'stock'          => 'required|numeric',
        ]);

        $data = [];

        // Dynamic Column Handling untuk Produk (Store)
        if (Schema::hasColumn('products', 'name')) $data['name'] = $request->name;
        if (Schema::hasColumn('products', 'nama_produk')) $data['nama_produk'] = $request->name;
        if (Schema::hasColumn('products', 'nama')) $data['nama'] = $request->name;

        if (Schema::hasColumn('products', 'purchase_price')) $data['purchase_price'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'harga_beli')) $data['harga_beli'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'price')) $data['price'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'harga')) $data['harga'] = $request->purchase_price;

        if (Schema::hasColumn('products', 'stock')) $data['stock'] = $request->stock;
        if (Schema::hasColumn('products', 'stok')) $data['stok'] = $request->stock;

        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            if (Schema::hasColumn('products', 'supplier_id')) $data['supplier_id'] = $request->supplier_id;
            if (Schema::hasColumn('products', 'id_supplier')) $data['id_supplier'] = $request->supplier_id;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Data produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('supplier')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'           => 'required',
            'purchase_price' => 'required|numeric',
            'stock'          => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $data = [];

        // Dynamic Column Handling untuk Produk (Update)
        if (Schema::hasColumn('products', 'name')) $data['name'] = $request->name;
        if (Schema::hasColumn('products', 'nama_produk')) $data['nama_produk'] = $request->name;
        if (Schema::hasColumn('products', 'nama')) $data['nama'] = $request->name;

        if (Schema::hasColumn('products', 'purchase_price')) $data['purchase_price'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'harga_beli')) $data['harga_beli'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'price')) $data['price'] = $request->purchase_price;
        if (Schema::hasColumn('products', 'harga')) $data['harga'] = $request->purchase_price;

        if (Schema::hasColumn('products', 'stock')) $data['stock'] = $request->stock;
        if (Schema::hasColumn('products', 'stok')) $data['stok'] = $request->stock;

        if ($request->has('supplier_id')) {
            if (Schema::hasColumn('products', 'supplier_id')) $data['supplier_id'] = $request->supplier_id;
            if (Schema::hasColumn('products', 'id_supplier')) $data['id_supplier'] = $request->supplier_id;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Data produk berhasil dihapus.');
    }
}