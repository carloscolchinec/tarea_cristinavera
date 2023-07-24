<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    use HasFactory;

    protected $table = "tb_administradores";

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'cedula',
        'fecha_cumpleanos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
