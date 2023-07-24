<?php

namespace App\Http\Controllers;

use App\Models\Facturas;
use App\Models\Clientes;
use App\Models\FacturasPago;
use App\Models\FacturasProducto;

use Carbon\Carbon;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacturasController extends Controller
{
    public function index()
    {
        $facturas = Facturas::all();
        foreach ($facturas as $factura) {
            $factura->estado = $factura->esCompletado() ? 'Completado' : 'En Curso';
        }
        return view('admin.facturas.index', compact('facturas'));
    }

    public function show(Facturas $factura)
    {
        return view('admin.facturas.show', compact('factura'));
    }


    public function create()
    {
        $clientes = Clientes::all();
        return view('admin.facturas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'cedula_cliente' => 'required',
            'fecha_factura' => 'required|date',
            'tipo_factura' => 'required|in:Contado,Diferido',
            'total_factura' => 'required',
            'productos.nombre' => 'required|array',
            'productos.cantidad' => 'required|array',
            'productos.precio_unitario' => 'required|array',
            'productos.nombre.*' => 'required|string',
            'productos.cantidad.*' => 'required|integer|min:1',
            'productos.precio_unitario.*' => 'required|numeric|min:0',
        ]);

        if ($request->tipo_factura === 'Diferido') {
            $request->validate([
                'cuotas' => 'required|integer|min:1',
                'fecha_credito' => 'required|date',
                'fecha_final_credito' => 'required|date|after_or_equal:fecha_credito',
            ]);
        }

        // Guardar la factura en la base de datos
        $factura = new Facturas();
        $factura->cedula_cliente = $request->cedula_cliente;
        $factura->fecha_factura = $request->fecha_factura;
        $factura->tipo_factura = $request->tipo_factura;
        $factura->cuotas = $request->cuotas ?? null;
        $factura->fecha_credito = $request->fecha_credito ?? null;
        $factura->fecha_final_credito = $request->fecha_final_credito ?? null;
        $factura->total_factura = $request->total_factura; // Aquí debes calcular el total de la factura en función de los productos
        $factura->save();

        // Guardar los productos relacionados con la factura
        $productos = [];
        foreach ($request->productos['nombre'] as $key => $nombre) {
            $productos[] = [
                'nombre_producto' => $nombre,
                'cantidad' => $request->productos['cantidad'][$key],
                'precio_producto' => $request->productos['precio_unitario'][$key],
                'factura_id' => $factura->id, // Asignamos el ID de la factura recién creada
            ];
        }

        // Guardar los productos en la base de datos
        $factura->productos()->createMany($productos);

        // Redireccionar a la lista de facturas u otra página
        return redirect()->route('facturas.index')->with('success', 'La factura se ha guardado correctamente.');
    }


    public function edit(Facturas $factura)
    {
        $clientes = Clientes::all();
        return view('admin.facturas.edit', compact('factura', 'clientes'));
    }

    public function update(Request $request, Facturas $factura)
    {
        $rules = [
            'cedula_cliente' => 'required',
            'fecha_factura' => 'required|date',
            'tipo_factura' => 'required',
            'total_factura' => 'required|numeric',
            'productos' => 'required|array',
            'productos.*.nombre' => 'required|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ];

        if ($request->tipo_factura === 'Diferido') {
            $rules['cuotas'] = 'required|integer|min:1';
            $rules['fecha_credito'] = 'required|date';
            $rules['fecha_final_credito'] = 'required|date|after:fecha_credito';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $factura->update([
            'cedula_cliente' => $request->cedula_cliente,
            'fecha_factura' => $request->fecha_factura,
            'tipo_factura' => $request->tipo_factura,
            'total_factura' => $request->total_factura,
        ]);

        // Eliminar los productos previos asociados a la factura
        FacturasProducto::where('factura_id', $factura->id)->delete();

        foreach ($request->productos as $producto) {
            $facturaProducto = new FacturasProducto([
                'factura_id' => $factura->id,
                'producto' => $producto['nombre'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
            ]);
            $facturaProducto->save();
        }

        // Aquí puedes agregar la lógica para actualizar los pagos relacionados con la factura si es necesario.

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada exitosamente.');
    }

    public function destroy(Facturas $factura)
    {
        // Aquí puedes agregar la lógica para eliminar los productos y pagos relacionados con la factura si es necesario.
        $factura->delete();

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada exitosamente.');
    }


    public function showRegistrarPagos($id)
    {
        $factura = Facturas::findOrFail($id);
        $pagos = $factura->pagos;
        $pagosPendientes = false;

        if ($factura->tipo_factura === 'Diferido') {
            // Verificar si hay pagos pendientes
            $fechaInicioCredito = Carbon::parse($factura->fecha_credito);
            $cuotas = $factura->cuotas;
            $montoCuota = $factura->total_factura / $cuotas;

            for ($i = 0; $i < $cuotas; $i++) {
                $fechaCuota = $fechaInicioCredito->addMonth($i);
                $pago = $pagos->where('fecha_pago', $fechaCuota)->first();

                if (!$pago || !$pago->pagado) {
                    $pagosPendientes = true;
                    break;
                }
            }
        }

        $fechaInicioFactura = $factura->fecha_factura;
        $fechaFinFactura = $factura->tipo_factura === 'Diferido' ? $factura->fecha_final_credito : $factura->fecha_factura;

        return view('admin.facturas.show', compact('factura', 'pagos', 'pagosPendientes', 'montoCuota', 'fechaInicioFactura', 'fechaFinFactura'));
    }


    public function registrarPago(Request $request, $id)
    {
        $factura = Facturas::findOrFail($id);
        $ultimoPago = $factura->pagos->sortByDesc('fecha_pago')->first();

        // Validar si se quiere saltar un mes
        if ($ultimoPago) {
            $fechaUltimoPago = Carbon::parse($ultimoPago->fecha_pago);
            $fechaPago = Carbon::parse($request->input('fecha_pago'));

            if (!$fechaPago->isAfter($fechaUltimoPago)) {
                return redirect()->back()->with('error', 'La fecha del pago debe ser posterior al último pago registrado.');
            }
        }

        // Guardar el nuevo pago
        $pago = new FacturasPago();
        $pago->factura_id = $factura->id;
        $pago->fecha_pago = $request->input('fecha_pago');
        $pago->monto_pago = $request->input('monto_pago');
        $pago->pagado = true; // Asignar el valor true directamente
        $pago->save();

        return redirect()->back()->with('success', 'Pago registrado exitosamente.');
    }


    public function facturasDiferidasPendientes()
    {
        // Obtener todas las facturas diferidas
        $facturasDiferidas = Facturas::where('tipo_factura', 'Diferido')->get();

        // Filtrar las facturas diferidas que tienen pagos pendientes
        $facturasPendientes = $facturasDiferidas->filter(function ($factura) {
            $pagos = $factura->pagos;
            $fechaInicioCredito = Carbon::parse($factura->fecha_credito);
            $cuotas = $factura->cuotas;
            $montoCuota = $factura->total_factura / $cuotas;

            for ($i = 0; $i < $cuotas; $i++) {
                $fechaCuota = $fechaInicioCredito->addMonth($i);
                $pago = $pagos->where('fecha_pago', $fechaCuota)->first();

                if (!$pago || !$pago->pagado) {
                    return true; // Tiene pagos pendientes
                }
            }

            return false; // Todos los pagos están completados
        });

        return view('admin.facturas.showPagosPendientes', compact('facturasPendientes'));
    }
}
