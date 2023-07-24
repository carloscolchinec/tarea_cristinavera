<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturasController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\AdministratorController;

// Rutas protegidas por el middleware "auth"
Route::group(['middleware' => 'auth'], function () {
    // Dasboard
    Route::get('/panel', [DashboardController::class, 'index'])->name('dashboard.index');

    // Clientes
    Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/crear', [ClientesController::class, 'create'])->name('clientes.create');
    Route::post('/clientes/crear', [ClientesController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/editar/{cliente}', [ClientesController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/editar/{cliente}', [ClientesController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/eliminar/{cliente}', [ClientesController::class, 'destroy'])->name('clientes.destroy');

    // Facturas
    Route::get('/facturas', [FacturasController::class, 'index'])->name('facturas.index');
    Route::get('/facturas/pendientes', [FacturasController::class, 'facturasDiferidasPendientes'])->name('facturas.pendientes');
    Route::get('/facturas/crear', [FacturasController::class, 'create'])->name('facturas.create');
    Route::post('/facturas/crear', [FacturasController::class, 'store'])->name('facturas.store');
    Route::delete('/facturas/eliminar/{factura}', [FacturasController::class, 'destroy'])->name('facturas.destroy');
    Route::get('/facturas/{id}/registrar-pagos', [FacturasController::class, 'showRegistrarPagos'])->name('facturas.showRegistrarPagos');
    Route::post('/facturas/{id}/registrar-pagos', [FacturasController::class, 'registrarPago'])->name('facturas.registrarPago');
    Route::get('/facturas/{factura}', [FacturasController::class, 'show'])->name('facturas.show');

    // Pagos
    Route::get('/pagos', [PagosController::class, 'index'])->name('pagos.index');


    Route::post('/logout', [AdministratorController::class, 'logout'])->name('logout');

});

// Rutas protegidas por el middleware "guest"
Route::middleware('guest')->group(function () {
    // Welcome (Página de inicio sin autenticación)
    Route::get('/', function () {
        return view('welcome');
    });

    // Login
    Route::get('/login', [AdministratorController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdministratorController::class, 'login']);
});
