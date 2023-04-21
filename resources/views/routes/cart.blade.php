@extends('layouts.app')

@section('content')
    <!-- Data table -->
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>

    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/cart/create') }}" id="form-create" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modalTitle" class="modal-title">Crear pedido</h4>
                        <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="route_id" value="{{ $route->id }}" required>
                        <input type="hidden" name="client_id" id="client_id" value="" required>
                        <input type="hidden" name="products_array" id="products_array" value="" required>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table" id="modalTable">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td class="product_inputs">
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}" required>
                                                        <input type="number" min="0" max="10000" class="form-control cantidadProducto" name="quantity" style="width: 120px">
                                                    </td>
                                                    <td>{{ $product->name }}</td>
                                                    <td class="precioProducto">${{ $product->price }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <p id="totalAmount" class="mr-2">Total pedido: $0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Crear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Repartos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/route/index') }}">Repartos</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/route/new') }}">Nuevo</a></li>
                    <li class="breadcrumb-item active">Carrito</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <div class="row">
            <div class="col-12">
                <h2 class="text-left">Nuevo reparto para {{ $route->user->name }}</h2>
                <hr />
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Listado de clientes</h4>
                        <h6 class="card-subtitle">Seleccione uno</h6>
                        <div class="table-responsive m-t-20">
                            <table id="clientsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>DNI</th>
                                        <th>Factura</th>
                                        <th>Deuda</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr data-toggle="modal" data-target="#modalConfirmation" onclick="openModal({{ $client->id }}, '{{ $client->name }}')">
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->adress }}</td>
                                            <td>{{ $client->phone }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->dni }}</td>
                                            <td class="text-center">
                                                @if ( $client->invoice == true)
                                                    <i class="bi bi-check2" style="font-size: 1.5rem"></i>
                                                @else
                                                    <i class="bi bi-x-lg" style="font-size: 1.3rem"></i>
                                                @endif
                                            </td>
                                            <td>${{ $client->debt }}</td>
                                            <td>{{ $client->observation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable({
                "language": {
                    // "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // La url reemplaza todo al español
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                    "sInfoFiltered": "(filtrado de _MAX_ clientes en total)",
                    "sLengthMenu": "Mostrar _MENU_ clientes",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior",
                    },
                },
            });
        });
    </script>

    {{-- Calcular el total dentro del modal --}}
    <script>
        $(".cantidadProducto").on("input", function() {
            let total = 0;
            $("#modalTable tbody tr").each(function() {
                let precioUnit = $(this).find(".precioProducto").text().replace('$', '');
                let cantidad = $(this).find(".cantidadProducto").val();
                let resultado = precioUnit * cantidad;
                total += resultado;
            });

            $("#totalAmount").html("Total pedido: $" + total);
        });

        function openModal(id, name) {
            $("#client_id").val(id);
            $("#form-create").removeClass("was-validated");
            $("#modalTitle").html("Crear pedido para " + name)
            $('#modalTable input[name="quantity"]').each(function() {
                $(this).val("0");
            });
        }
    </script>

    <script>
        //For validation with custom styles
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                            form.classList.add('was-validated');
                        } else {
                            event.preventDefault();
                            createProductsArray();
                            if ($("#products_array").val() != "[]") {
                                form.classList.add('was-validated');
                                sendForm();
                            } else {
                                $("#form-create").removeClass("was-validated");
                                Swal.fire({
                                    icon: 'warning',
                                    title: "Debes agregar al menos un producto al pedido",
                                });
                            };
                        };
                    }, false);
                });
            }, false);
        })();

        function createProductsArray() {
            var products = []; // arreglo para almacenar los productos
    
            // para cada fila de la tabla
            $('tr .product_inputs').each(function(index) {
                var product = {}; // objeto para almacenar un producto
                product.quantity = parseInt($(this).find('input[name="quantity"]').val()); // obtener la cantidad del producto
                if (product.quantity > 0) {
                    product.product_id = parseInt($(this).find('input[name="product_id"]').val()); // obtener el id del producto
                    products.push(product); // agregar el producto al arreglo de productos
                }
            });
            
            // agregar el arreglo de productos como un campo del formulario
            $("#products_array").val(JSON.stringify(products));
        };

        function sendForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-create").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-create").attr('method'), // Utiliza el método del formulario
                data: $("#form-create").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnCloseModal").click();
                    Swal.fire(
                        'OK',
                        'Acción correcta',
                        'success'
                    );
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.message,
                    });
                }
            });
        };
    </script>

    <style>
        #clientsTable tbody tr {
            cursor: pointer;
        }
        #clientsTable tbody tr:hover {
            background-color: #dee2e6;
        }

        #clientsTable_paginate > ul > li.paginate_button.page-item.active > a,
        #clientsTable_paginate > ul > li.paginate_button.page-item.active > a:hover
        {
            background-color: #fc4b6c;
            border-color: #ff0030;
        }
    </style>

@endsection