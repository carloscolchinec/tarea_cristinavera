<?php

// app/Models/Factura.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    protected $table = 'tb_facturas';

    protected $fillable = [
        'cedula_cliente',
        'fecha_factura',
        'tipo_factura',
        'total_factura',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cedula_cliente', 'cedula');
    }

    public function productos()
    {
        return $this->hasMany(FacturasProducto::class, 'factura_id');
    }

    public function pagos()
    {
        return $this->hasMany(FacturasPago::class, 'factura_id');
    }

    // Facturas.php (modelo)

    public function esCompletado()
    {
        $totalCuotas = $this->cuotas;
        $cuotasPagadas = $this->pagos->where('pagado', true)->count();
        return $cuotasPagadas >= $totalCuotas;
    }
}
