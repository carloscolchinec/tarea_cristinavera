<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturasProducto extends Model
{
    protected $table = 'tb_facturas_productos';

    protected $fillable = [
        'factura_id',
        'nombre_producto',
        'precio_producto',
        'cantidad',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }
}