@extends('layouts.adminlte_admin')

@section('title', 'Editar Cliente')

@section('content')
<h1>Editar Cliente</h1>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('clientes.update', $cliente->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $cliente->nombre }}" required>
    </div>
    <div class="form-group">
        <label for="apellidos">Apellidos</label>
        <input type="text" name="apellido" id="apellido" class="form-control" value="{{ $cliente->apellido }}" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control" value="{{ $cliente->direccion }}" required>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $cliente->telefono }}" required>
    </div>
    <div class="form-group">
        <label for="correo">Correo</label>
        <input type="email" name="correo" id="correo" class="form-control" value="{{ $cliente->correo }}" required>
    </div>
    <div class="form-group">
        <label for="cedula">Cédula</label>
        <input type="text" name="cedula" id="cedula" class="form-control" value="{{ $cliente->cedula }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection