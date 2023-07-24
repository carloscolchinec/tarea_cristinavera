@extends('layouts.adminlte_admin')

@section('title', 'Transacciones de Facturas')

@section('content')
<div class="container-fluid py-4">
    <h1>Transacciones de Facturas</h1>

    @if ($transacciones->isEmpty())
        <p>No hay transacciones registradas.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Factura ID</th>
                        <th>Cliente</th>
                        <th>Fecha de Pago</th>
                        <th>Monto de Pago</th>
                        <th>Pagado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transacciones as $transaccion)
                        @php
                            $cliente = $transaccion->factura->cliente;
                        @endphp
                        <tr>
                            <td>{{ $transaccion->id }}</td>
                            <td>{{ $transaccion->factura_id }}</td>
                            <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                            <td>{{ $transaccion->fecha_pago }}</td>
                            <td>{{ $transaccion->monto_pago }}</td>
                            <td>{{ $transaccion->pagado ? 'Si' : 'No' }}</td>
                            <td>{{ $transaccion->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a href="{{ route('facturas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
