@extends('layouts.adminlte_admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h1>Dashboard</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number">{{ $totalClientes }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-file-invoice"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Facturas</span>
                    <span class="info-box-number">{{ $totalFacturas }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-money-bill"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Pagos</span>
                    <span class="info-box-number">{{ $totalPagos }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Monto Total Pagado</span>
                    <span class="info-box-number">{{ $montoTotalPagado }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Clientes con Pagos Pendientes</span>
                    <span class="info-box-number">{{ $clientesConPagosPendientes }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box bg-secondary">
                <span class="info-box-icon"><i class="fas fa-coins"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Factura con Monto Más Alto</span>
                    <span class="info-box-number">{{ $facturaMontoMasAlto->total_factura }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-coins"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Factura con Monto Más Bajo</span>
                    <span class="info-box-number">{{ $facturaMontoMasBajo->total_factura }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
