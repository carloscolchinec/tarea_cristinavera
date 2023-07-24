<?php

namespace App\Http\Controllers;

use App\Models\FacturasPago;
use Illuminate\Http\Request;


class PagosController extends Controller
{
    public function index()
    {
        $transacciones = FacturasPago::all();

        return view('admin.pagos.index', compact('transacciones'));
    }
}
