<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Tampilkan daftar supplier
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Tampilkan form tambah supplier
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Simpan supplier baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:100',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
        ], [
            'kode_supplier.required' => 'Kode supplier wajib diisi!',
            'kode_supplier.unique'   => 'Kode supplier sudah terdaftar!',
            'nama_supplier.required' => 'Nama supplier wajib diisi!',
        ]);

        Supplier::create($validated);

        return redirect()->route('supplier.index')->with('success', 'Data Supplier berhasil ditambahkan!');
    }

    /**
     * Hapus data supplier
     */
    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Data Supplier berhasil dihapus!');
    }
}