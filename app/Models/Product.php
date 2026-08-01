<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom disimpan
    protected $guarded = [];

    /**
     * Relasi ke Model Supplier
     */
    public function supplier()
    {
        $foreignKey = 'supplier_id';

        if (Schema::hasColumn('products', 'id_supplier')) {
            $foreignKey = 'id_supplier';
        }

        return $this->belongsTo(Supplier::class, $foreignKey);
    }
}