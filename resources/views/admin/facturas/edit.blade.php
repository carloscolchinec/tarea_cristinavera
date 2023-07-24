@extends('layouts.adminlte_admin')

@section('title', 'Editar Factura')

@section('content')
<div class="container-fluid py-3">
    <h1>Editar Factura #{{ $factura->id }}</h1>

    <form action="{{ route('facturas.update', $factura->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Información de la Factura</h5>
                <div class="form-group">
                    <label for="cliente">Cliente:</label>
                    <input type="text" class="form-control" id="cliente" name="cliente" value="{{ $factura->cliente->nombre }} {{ $factura->cliente->apellido }}" readonly>
                </div>
                <div class="form-group">
                    <label for="fecha_factura">Fecha de Factura:</label>
                    <input type="date" class="form-control" id="fecha_factura" name="fecha_factura" value="{{ $factura->fecha_factura }}">
                </div>
                <div class="form-group">
                    <label for="tipo_factura">Tipo de Factura:</label>
                    <select class="form-control" id="tipo_factura" name="tipo_factura" readonly>
                        <option value="Contado" {{ $factura->tipo_factura === 'Contado' ? 'selected' : '' }}>Contado</option>
                        <option value="Diferido" {{ $factura->tipo_factura === 'Diferido' ? 'selected' : '' }}>Diferido</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_factura">Total Factura:</label>
                    <input type="text" class="form-control" id="total_factura" name="total_factura" value="{{ $factura->total_factura }}">
                </div>
                @if ($factura->tipo_factura === 'Diferido')
                <div class="form-group">
                    <label for="cuotas">Cuotas:</label>
                    <input type="text" class="form-control" id="cuotas" name="cuotas" value="{{ $factura->cuotas }}">
                </div>
                <div class="form-group">
                    <label for="fecha_credito">Fecha de Inicio de Crédito:</label>
                    <input type="date" class="form-control" id="fecha_credito" name="fecha_credito" value="{{ $factura->fecha_credito }}">
                </div>
                <div class="form-group">
                    <label for="fecha_final_credito">Fecha Final de Crédito:</label>
                    <input type="date" class="form-control" id="fecha_final_credito" name="fecha_final_credito" value="{{ $factura->fecha_final_credito }}">
                </div>
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

        @if ($factura->tipo_factura === 'Diferido')
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
                        @php
                            $fechaPago = $factura->fecha_credito;
                            $fechaFinalCredito = $factura->fecha_final_credito;
                            $pagosPendientes = false;
                        @endphp
                        @while (strtotime($fechaPago) <= strtotime($fechaFinalCredito))
                        <tr>
                            <td>{{ $fechaPago }}</td>
                            <td>
                                @php
                                    $pago = $factura->pagos->where('fecha_pago', $fechaPago)->first();
                                @endphp
                                @if ($pago)
                                    ${{ $pago->monto }}
                                @else
                                    Sin Cancelar
                                @endif
                            </td>
                            <td>
                                @if ($pago && $pago->pagado)
                                    <span class="badge badge-success">Cancelado</span>
                                @else
                                    @php
                                        $pagosPendientes = true;
                                    @endphp
                                    <span class="badge badge-danger">Sin Cancelar</span>
                                @endif
                            </td>
                        </tr>
                        @php
                            // Añadir un mes a la fecha de pago
                            $fechaPago = date('Y-m-d', strtotime($fechaPago . '+1 month'));
                        @endphp
                        @endwhile
                    </tbody>
                </table>

                @if ($pagosPendientes)
                    <a href="{{ route('facturas.showRegistrarPagos', $factura->id) }}" class="btn btn-primary mt-3">Registrar Pago</a>
                @endif
            </div>
        </div>
        @endif

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
