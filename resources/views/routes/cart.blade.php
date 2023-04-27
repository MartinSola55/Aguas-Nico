@extends('layouts.app')

@section('content')
    <!-- Data table -->
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>

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
                                        <th>Seleccionar</th>
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
                                        <tr data-id="{{ $client->id }}" class="client_row">
                                            <td class="text-center">
                                                <input type="checkbox" id="check_{{ $client->id }}" class="client_checkbox form-control">
                                                <label for="check_{{ $client->id }}"></label>
                                                <input type="hidden" name="client_priority[]" class="client_priority" value="0">
                                            </td>
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

    {{-- Establecer el orden de prioridad --}}
    <script>
        let lastPriority = 0;
        document.querySelectorAll('.client_checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let input = this.parentElement.querySelector('.client_priority');
                let label = this.parentElement.querySelector('label');
                
                if (this.checked) {
                    lastPriority++;
                    input.value = lastPriority;
                    label.textContent = lastPriority;
                } else {
                    input.value = '';
                    label.textContent = '';
                    lastPriority = 0;
                    document.querySelectorAll('.client_checkbox:checked').forEach(function(checkbox) {
                        lastPriority++;
                        checkbox.parentElement.querySelector('.client_priority').value = lastPriority;
                        checkbox.parentElement.querySelector('label').textContent = lastPriority;
                    });
                }
            });
        });

    </script>


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
                            createClientsArray();
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

        function createClientsArray() {
            var clients = []; // arreglo para almacenar los clientes
    
            // para cada fila de la tabla
            $('#clientsTable tbody tr').each(function(index) {
                var client = {}; // objeto para almacenar un cliente
                if ($(this).find('input[name="priority"]').val() != "") {
                    client.priority = parseInt($(this).find('input[name="priority"]').val()); // obtener la prioridad del cliente
                    client.id = parseInt($(this).find('input[name="id"]').val()); // obtener el id del cliente
                    clients.push(client); // agregar el cliente al arreglo de clientes
                }
            });
            
            // agregar el arreglo de productos como un campo del formulario
            $("#clients_array").val(JSON.stringify(clients));
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