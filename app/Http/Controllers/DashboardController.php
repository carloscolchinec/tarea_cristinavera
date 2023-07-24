<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes;
use App\Models\Facturas;
use App\Models\FacturasPago;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener la cantidad total de registros en cada tabla
        $totalClientes = Clientes::count();
        $totalFacturas = Facturas::count();
        $totalPagos = FacturasPago::count();

        // Obtener el monto total pagado en todas las facturas
        $montoTotalPagado = FacturasPago::sum('monto_pago');

        // Obtener la cantidad total de clientes con pagos pendientes
        $clientesConPagosPendientes = Clientes::whereHas('facturas.pagos', function ($query) {
            $query->where('pagado', false);
        })->count();

        // Obtener la factura con el monto más alto
        $facturaMontoMasAlto = Facturas::orderBy('total_factura', 'desc')->first();

        // Obtener la factura con el monto más bajo
        $facturaMontoMasBajo = Facturas::orderBy('total_factura')->first();

        // Pasar los datos a la vista
        return view('admin.dashboard.index', compact(
            'totalClientes',
            'totalFacturas',
            'totalPagos',
            'montoTotalPagado',
            'clientesConPagosPendientes',
            'facturaMontoMasAlto',
            'facturaMontoMasBajo'
        ));
    }
}
