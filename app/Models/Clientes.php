<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'tb_clientes';

    protected $fillable = [
        'nombre',
        'apellido', // Nuevo campo "apellidos"
        'direccion',
        'telefono',
        'correo',
        'cedula',
    ];

    public function facturas()
    {
        return $this->hasMany(Facturas::class, 'cedula_cliente');
    }
}
