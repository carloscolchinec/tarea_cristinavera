@extends('layouts.adminlte_admin')

@section('title', 'Facturas')

@section('content')
<div class="container-fluid py-4">
    <h1>Lista de Facturas</h1>
    <a href="{{ route('facturas.create') }}" class="btn btn-primary mb-3">Nueva Factura</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table" id="facturasTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th> <!-- Modificamos "Tipo" por "Estado" -->
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturas as $factura)
            <tr>
                <td>{{ $factura->id }}</td>
                <td>{{ $factura->cliente->cedula }}</td>
                <td>{{ $factura->fecha_factura }}</td>
                <td>
                    <span class="badge {{ $factura->estado === 'Completado' ? 'badge-success' : 'badge-warning' }} text-capitalize">
                        {{ $factura->estado }}
                    </span>
                </td>

                <td>{{ $factura->total_factura }}</td>
                <td>
                    <a href="{{ route('facturas.show', $factura->id) }}" class="btn btn-info">Ver</a>
                    <form action="{{ route('facturas.destroy', $factura->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('styles')
    <!-- Estilos de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
    <!-- Bibliotecas de jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#facturasTable').DataTable();
        });
    </script>
@endsection
