<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault();
    }

    // Relasi ke Product (Opsi 1)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    // Relasi ke Product (Opsi 2)
    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id')->withDefault();
    }

    // Relasi ke Product (Opsi 3: id_produk)
    public function productById()
    {
        return $this->belongsTo(Product::class, 'id_produk')->withDefault();
    }
}