@extends('layouts.adminlte_admin')

@section('title', 'Nueva Factura')

@section('content')
<div class="container-fluid py-3">
    <h1>Nueva Factura</h1>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('facturas.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="cedula_cliente">Cliente</label>
            <div class="input-group">
                <input type="text" class="form-control" name="cedula" placeholder="Seleccione un cliente" id="cliente_search">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modalBuscarCliente">
                        Buscar Cliente
                    </button>
                </div>
            </div>
            <input type="hidden" name="cedula_cliente" id="cedula_cliente" value="" required>
        </div>
        <div class="form-group">
            <label for="fecha_factura">Fecha de Factura</label>
            <input type="date" name="fecha_factura" id="fecha_factura" class="form-control" value="{{ old('fecha_factura') }}" required>
        </div>
        <div class="form-group">
            <label for="tipo_factura">Tipo de Factura</label>
            <select name="tipo_factura" id="tipo_factura" class="form-control" required>
                <option value="Contado">Contado</option>
                <option value="Diferido">Diferido</option>
            </select>
        </div>

        <div id="cuotas_container" style="display: none;">
            <div class="form-group">
                <label for="cuotas">Cuotas</label>
                <input type="number" name="cuotas" id="cuotas" class="form-control">
            </div>
            <div class="form-group">
                <label for="fecha_credito">Fecha de Inicio del Crédito</label>
                <input type="date" name="fecha_credito" id="fecha_credito" class="form-control">
            </div>
            <div class="form-group">
                <label for="fecha_final_credito">Fecha Final del Crédito</label>
                <input type="date" name="fecha_final_credito" id="fecha_final_credito" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="total_factura">Total de Factura</label>
            <input type="text" name="total_factura" id="total_factura" class="form-control" value="0" readonly required>
        </div>
        <div id="productos" class="mb-4">
            <!-- Aquí se agregarán los campos de los productos con JavaScript -->
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-success" onclick="agregarProducto()">Agregar Producto</button>
        <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <!-- Modal para buscar clientes -->
    <div class="modal fade" id="modalBuscarCliente" tabindex="-1" role="dialog" aria-labelledby="modalBuscarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarClienteLabel">Buscar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Buscar cliente por nombre o cédula" id="cliente_search_modal">
                </div>
                <div id="lista_clientes">
                    <!-- Aquí se mostrarán los clientes encontrados por el buscador -->
                    @if(count($clientes) > 0)
                    @foreach($clientes as $cliente)
                    <div class="cliente-item" data-cedula="{{ $cliente->cedula }}">{{ $cliente->nombre }} {{ $cliente->apellido }} ({{ $cliente->cedula }})</div>
                    @endforeach
                    @else
                    <div class="alert alert-warning" role="alert">
                        No hay clientes con la palabra para mostrar.
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    <script>
        var contadorProductos = 0;

        function agregarProducto() {
            contadorProductos++;
            var productosDiv = document.getElementById('productos');
            var nuevoProductoHtml =
                `<div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Producto ${contadorProductos}</h5><br>
                <div class="form-group">
                    <label for="productos[nombre][]">Nombre del Producto</label>
                    <input type="text" name="productos[nombre][]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="productos[cantidad][]">Cantidad</label>
                    <input type="number" name="productos[cantidad][]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="productos[precio_unitario][]">Precio Unitario</label>
                    <input type="number" name="productos[precio_unitario][]" class="form-control" required oninput="calcularTotalFactura()">
                </div>
                <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Eliminar Producto</button>
            </div>
        </div>`;
            productosDiv.insertAdjacentHTML('beforeend', nuevoProductoHtml);
            calcularTotalFactura();
        }

        function calcularTotalFactura() {
            var totalFactura = 0;
            var precioUnitarioInputs = document.getElementsByName('productos[precio_unitario][]');
            var cantidadInputs = document.getElementsByName('productos[cantidad][]');

            for (var i = 0; i < precioUnitarioInputs.length; i++) {
                var precioUnitario = parseFloat(precioUnitarioInputs[i].value) || 0;
                var cantidad = parseFloat(cantidadInputs[i].value) || 0;
                totalFactura += precioUnitario * cantidad;
            }

            document.getElementById('total_factura').value = totalFactura.toFixed(2);
        }

        function eliminarProducto(boton) {
            var productoDiv = boton.closest('.card');
            productoDiv.remove();
            calcularTotalFactura();
        }

        document.getElementById('tipo_factura').addEventListener('change', function(event) {
            var tipoFactura = event.target.value;
            var cuotasContainer = document.getElementById('cuotas_container');
            cuotasContainer.style.display = tipoFactura === 'Diferido' ? 'block' : 'none';
            if (tipoFactura === 'Contado') {
                document.getElementById('cuotas').value = '';
                document.getElementById('fecha_credito').value = '';
                document.getElementById('fecha_final_credito').value = '';
            }
        });

        document.getElementById('cliente_search_modal').addEventListener('input', function(event) {
            var input, filter, lista, items, item, i, txtValue;
            input = event.target;
            filter = input.value.toUpperCase();
            lista = document.getElementById('lista_clientes');
            items = lista.getElementsByClassName('cliente-item');
            for (i = 0; i < items.length; i++) {
                item = items[i];
                txtValue = item.textContent || item.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            }
        });

        document.getElementById('lista_clientes').addEventListener('click', function(event) {
        var target = event.target;
        if (target.classList.contains('cliente-item')) {
            var cedulaCliente = target.dataset.cedula;
            document.getElementById('cedula_cliente').value = cedulaCliente;
            document.getElementById('cliente_search').value = cedulaCliente;
            $('#modalBuscarCliente').modal('hide'); // Cerrar el modal
        }
    });
        
    </script>
</div>
@endsection
