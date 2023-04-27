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
                <h3 class="text-themecolor m-b-0 m-t-0">Clientes</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <!-- Modal -->
        <div id="modalConfirmation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/client/create') }}" id="form-create" autocomplete="off" novalidate>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Agregar cliente</h4>
                            <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-column">
                                        {{-- TOKEN --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        
                                        <div class="col-12 mb-3">
                                            <label for="clientName" class="mb-0">Nombre</label>
                                            <input type="text" class="form-control" id="clientName" name="name" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un nombre
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientAdress" class="mb-0">Dirección</label>
                                            <input type="text" class="form-control" id="clientAdress" name="adress" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese una dirección
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientPhone" class="mb-0">Teléfono</label>
                                            <input type="tel" class="form-control" id="clientPhone" name="phone" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un teléfono
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientEmail" class="mb-0">Email</label>
                                            <input type="email" class="form-control" id="clientEmail" name="email" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un email
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientDNI" class="mb-0">DNI</label>
                                            <input type="number" min="0" class="form-control" id="clientDNI" name="dni" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un DNI
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientDebt" class="mb-0">Deuda</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="clientDebt" name="debt" placearia-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese un monto
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="clientObservation" class="mb-0">Observación</label>
                                            <textarea type="text" class="form-control" id="clientObservation" name="observation"></textarea>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div>
                                                <input type="checkbox" id="clientInvoice" name="invoice" value="1"/>
                                                <label for="clientInvoice">¿Quiere factura?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light">Agregar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h2 class="card-title">Listado de clientes</h4>
                            <button id="btnAddClient" type="button" class="btn btn-danger btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#modalConfirmation">Agregar nuevo cliente</button>
                        </div>
                        <div class="table-responsive m-t-40">
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
                                        <tr>
                                            <td>
                                                <a href="{{ route('client.details', ['id' => $client->id]) }}">{{ $client->name }}</a>
                                            </td>
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
                        } else {
                            event.preventDefault();
                            sendForm();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

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
        }
    </script>

    <script>
        $("#btnAddClient").on("click", function () {
            $("#form-create").removeClass('was-validated');
            $("#form-create input:not([name='_token']),textarea").val("");
            $("#form-create input[type='checkbox']").prop("checked", false);
        });

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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #clientsTable_paginate > ul > li.paginate_button.page-item.active > a,
        #clientsTable_paginate > ul > li.paginate_button.page-item.active > a:hover
        {
            background-color: #fc4b6c;
            border-color: #ff0030;
        }
    </style>
    
@endsection
