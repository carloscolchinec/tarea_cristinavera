<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturasPago extends Model
{
    use HasFactory;

    protected $table = 'tb_facturas_pagos';

    protected $fillable = [
        'factura_id',
        'fecha_pago',
        'monto_pago',
    ];

    // Relación con el modelo Factura
    public function factura()
    {
        return $this->belongsTo(Facturas::class);
    }

    
}



