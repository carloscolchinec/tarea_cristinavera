@extends('layouts.adminlte_admin')

@section('title', 'Facturas Diferidas Pendientes')

@section('content')
<div class="container-fluid py-4">
    <h1>Facturas Diferidas Pendientes</h1>

    @if ($facturasPendientes->isEmpty())
        <p>No hay facturas diferidas con pagos pendientes.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped dataTable">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Cliente</th>
                        <th>Fecha de Crédito</th>
                        <th>Cuotas Pendientes</th>
                        <th>Acciones</th> <!-- Agregamos esta columna para el botón -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($facturasPendientes as $factura)
                        @php
                            $totalCuotas = $factura->cuotas;
                            $pagosRegistrados = $factura->pagos->where('pagado', true)->count();
                            $cuotasPendientes = $totalCuotas - $pagosRegistrados;
                        @endphp

                        @if ($cuotasPendientes > 0)
                            <tr>
                                <td>{{ $factura->id }}</td>
                                <td>{{ $factura->cliente->nombre }} {{ $factura->cliente->apellido }}</td>
                                <td>{{ $factura->fecha_credito }}</td>
                                <td class="text-center">{{ $cuotasPendientes }}</td>
                                <td>
                                    <a href="{{ route('facturas.registrarPago', $factura->id) }}" class="btn btn-primary">Pagar cuota</a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a href="{{ route('facturas.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection

@section('styles')
    <!-- Agregar el CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
    <!-- Agregar jQuery antes de DataTables para que funcione correctamente -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Agregar el script de DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- Inicializar el DataTable en la tabla con la clase "dataTable" -->
    <script>
        $(document).ready(function() {
            $('.dataTable').DataTable();
        });
    </script>
@endsection
