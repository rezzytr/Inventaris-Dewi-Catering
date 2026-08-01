<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk dikirim secara massal
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'kontak_person',
        'no_telepon',
        'alamat',
    ];

    /**
     * Relasi One-to-Many ke PurchaseOrder
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}