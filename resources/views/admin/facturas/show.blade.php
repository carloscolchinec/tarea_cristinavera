@extends('layouts.adminlte_admin')

@section('title', 'Detalles de Factura')

@section('content')
<div class="container-fluid py-3">
    <h1>Detalles de Factura #{{ $factura->id }}</h1>

    <div class="card mb-3 my-0 p-3">
        <div class="card-body py-0">
            <h5 class="card-title">Cliente: {{ $factura->cliente->nombre }} {{ $factura->cliente->apellido }}</h5>
            <p class="card-text mb-0">Fecha: {{ $factura->fecha_factura }}</p>
            <p class="card-text mb-0">Tipo de Factura: 
                @if ($factura->tipo_factura === 'Diferido')
                    @php
                        $pagosEnRango = $factura->pagos->where('fecha_pago', '>=', $factura->fecha_credito)
                                                      ->where('fecha_pago', '<=', $factura->fecha_final_credito);
                       $cuotasPagadas = $factura->pagos->where('pagado', true)
                                                      ->where('factura_id', $factura->id)
                                                      ->count();
                        $todasCuotasPagadas = $cuotasPagadas === $factura->cuotas;

                    @endphp
                    <span class="badge {{ $todasCuotasPagadas ? 'badge-success' : 'badge-warning' }} text-capitalize">
                        {{ $factura->tipo_factura }}
                    </span>
                    @if ($todasCuotasPagadas)
                        <span class="badge badge-success">Pagado</span>
                    @else
                        <span class="badge badge-info">{{ $cuotasPagadas }}/{{ $factura->cuotas }} Cuotas Pagadas</span>
                    @endif
                @else
                    <span class="badge {{ $factura->tipo_factura === 'Contado' ? 'badge-success' : 'badge-warning' }} text-capitalize">
                        {{ $factura->tipo_factura }}
                    </span>
                @endif
            </p>
            <p class="card-text mb-0">Total: ${{ $factura->total_factura }}</p>
            @if ($factura->tipo_factura === 'Diferido')
                <p class="card-text mb-0">Cuotas: {{ $factura->cuotas }}</p>
                <p class="card-text mb-0">Cuota Mensual: ${{ number_format($factura->total_factura / $factura->cuotas, 2) }}</p>
                <p class="card-text mb-0">Fecha de Inicio de Crédito: {{ $factura->fecha_credito }}</p>
                <p class="card-text mb-0">Fecha Final de Crédito: {{ $factura->fecha_final_credito }}</p>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Productos de la Factura</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factura->productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre_producto }}</td>
                        <td>{{ $producto->cantidad }}</td>
                        <td>${{ $producto->precio_producto }}</td>
                        <td>${{ $producto->cantidad * $producto->precio_producto }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($factura->tipo_factura === 'Diferido' && !$todasCuotasPagadas)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Pagos de la Factura</h5><br>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha de Pago</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Obtener los pagos relacionados con la factura --}}
                    @foreach($pagosEnRango as $pago)
                        <tr>
                            <td>{{ $pago->fecha_pago }}</td>
                            <td>${{ number_format($pago->monto_pago, 2) }}</td>
                            <td>
                                @php
                                    $pagoEnRango = $pago->fecha_pago >= $factura->fecha_credito && $pago->fecha_pago <= $factura->fecha_final_credito;
                                    $estadoPago = $pagoEnRango ? 'Pagado' : ($pago->pagado ? 'Cancelado' : 'Sin Cancelar');
                                    $estadoBadge = $pagoEnRango ? 'badge-success' : ($pago->pagado ? 'badge-success' : 'badge-danger');
                                @endphp
                                <span class="badge {{ $estadoBadge }}">
                                    {{ $estadoPago }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Badge para mostrar la cantidad de cuotas pagadas (añadir ID) --}}
            <div id="cuotasPagadasBadge">
                @if ($todasCuotasPagadas)
                    <span class="badge badge-success">Pagado</span>
                @else
                    <span class="badge badge-info">{{ $cuotasPagadas }}/{{ $factura->cuotas }} Cuotas Pagadas</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario para registrar un nuevo pago (añadir ID) --}}
    @if ($factura->tipo_factura === 'Diferido' && !$todasCuotasPagadas)
    <div class="card mt-3" id="formularioPago">
        <div class="card-body">
            <h5 class="card-title">Registrar Pago</h5>
            <form action="{{ route('facturas.registrarPago', $factura->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fecha_pago">Fecha de Pago</label>
                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                </div>
                <div class="form-group">
                    <label for="monto_pago">Monto de Pago</label>
                    <input type="number" step="0.01" class="form-control" id="monto_pago" name="monto_pago" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
            </form>
        </div>
    </div>
    @endif
  
    <a href="{{ route('facturas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
