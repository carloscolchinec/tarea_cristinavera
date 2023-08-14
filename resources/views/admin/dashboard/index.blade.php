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

</div>
@endsection
