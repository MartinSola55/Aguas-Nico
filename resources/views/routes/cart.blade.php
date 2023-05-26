@php
    $diasSemana = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
    ];
@endphp
@extends('layouts.app')

@section('content')
    <!-- Data table -->
    <link href="{{ secure_asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- This is data table -->
    <script src="{{ secure_asset('plugins/datatables/datatables.min.js') }}"></script>

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
                <h2 class="text-left">Agregar cliente al reparto del <b>{{ $diasSemana[$route->day_of_week] }}</b> de <b>{{ $route->user->name }}</b></h2>
                <hr />
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Listado de clientes</h4>
                        <form role="form" method="POST" action="{{ auth()->user()->rol_id == '1' ? url('/route/updateClients') : url('/route/addClients') }}" id="form-confirm">
                            @csrf
                            <input type="hidden" name="route_id" value="{{ $route->id }}">
                            <input type="hidden" name="clients_array" id="clients_array" value="">

                            <div class="table-responsive">
                                <table id="clientsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Seleccionar</th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            @if (auth()->user()->rol_id == '1')
                                            <th>DNI</th>
                                            @endif
                                            <th>Factura</th>
                                            <th>Deuda</th>
                                            <th>Observación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients->sortBy('priority')->sortBy(function($client) {
                                            return is_null($client->priority) ? 1 : 0;
                                        }) as $client)
                                            <tr data-id="{{ $client->id }}" class="client_row">
                                                <td class="text-center">
                                                    <input type="checkbox" id="check_{{ $client->id }}" {{ $client->priority ? "checked" : ""}} class="client_checkbox form-control" {{ auth()->user()->rol_id != '1' && $client->priority ? "disabled" : ""}}>
                                                    <label for="check_{{ $client->id }}">{{ $client->priority ?? ""}}</label>
                                                    <input type="hidden" name="client_priority" class="client_priority" value="{{ $client->priority ?? 0 }}" {{ auth()->user()->rol_id != '1' && $client->priority ? "disabled" : ""}}>
                                                </td>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->adress }}</td>
                                                <td>{{ $client->phone }}</td>
                                                @if (auth()->user()->rol_id == '1')
                                                <td>{{ $client->dni }}</td>
                                                @endif
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
                                <div class="d-flex flex-row justify-content-end mt-4">
                                    <button onclick="createClientsArray()" type="button" class="btn btn-success">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Establecer el orden de prioridad --}}
    <script>
        let lastPriority = $('.client_checkbox:checked').length; // Cantidad de clientes seleccionados
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
                "ordering": false,
                "language": {
                    // "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // La url reemplaza todo al español
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                    "sInfoFiltered": "(filtrado de _MAX_ clientes en total)",
                    "emptyTable": 'No hay clientes que coincidan con la búsqueda',
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
        window.userRol = "{{ auth()->user()->rol_id }}";
    </script>

    {{-- Send form --}}
    <script>
        function createClientsArray() {
            var clients = []; // arreglo para almacenar los clientes
    
            // para cada fila de la tabla
            $('#clientsTable tbody tr').each(function(index) {
                var client = {}; // objeto para almacenar un cliente
                if ($(this).find('input[name="client_priority"]').val() != "" && !$(this).find('input[name="client_priority"]').is(':disabled')) {
                    client.priority = parseInt($(this).find('input[name="client_priority"]').val()); // obtener la prioridad del cliente)
                    client.id = parseInt($(this).attr('data-id')); // obtener el id del cliente
                    clients.push(client); // agregar el cliente al arreglo de clientes
                }
            });
            if (clients.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se ha seleccionado ningún cliente',
                    confirmButtonColor: '#1e88e5',
                });
                return;
            }
            // agregar el arreglo de productos como un campo del formulario
            $("#clients_array").val(JSON.stringify(clients));
            sendForm();
        };

        function sendForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-confirm").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-confirm").attr('method'), // Utiliza el método del formulario
                data: $("#form-confirm").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#1e88e5',
                        confirmButtonText: 'OK',
                        allowOutsideClick: window.userRol == '1' ? true : false,
                    })
                    .then((result) => {
                        if (result.isConfirmed && window.userRol != '1') {
                            window.location.href = "{{ route('route.details', ['id' => $route->id] ) }}";
                        }
                    })
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.title,
                        text: errorThrown.responseJSON.message,
                        confirmButtonColor: '#1e88e5',
                    });
                }
            });
        };
    </script>

    <style>
        #clientsTable tbody tr:hover {
            background-color: #dee2e6;
        }
    </style>

@endsection