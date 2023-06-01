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
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <!--nestable CSS -->
    <link href="{{ asset('plugins/nestable/nestable.css') }}" rel="stylesheet" type="text/css" />

    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.rowReorder.min.js') }}"></script>
 

    <!--Nestable js -->
    <script src="{{ asset('plugins/nestable/jquery.nestable.js') }}"></script>

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
            </div>
            <div class="col-12 col-xl-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Clientes seleccionados</h4>
                        <form role="form" method="POST" action="{{ auth()->user()->rol_id == '1' ? url('/route/updateClients') : url('/route/addClients') }}" id="form-confirm">
                            @csrf
                            <input type="hidden" name="route_id" value="{{ $route->id }}">
                            <input type="hidden" name="clients_array" id="clients_array" value="">

                            <div class="table-responsive">
                                <table id="clientsTable" class="table DataTable table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Quitar</th>
                                            <th>Nombre</th>
                                            <th>Dirección</th>
                                            @if (auth()->user()->rol_id == '1')
                                            <th>DNI</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach($clientsSelected as $client)
                                            @php
                                                $i++;
                                            @endphp
                                            <tr data-id="{{ $client->id }}">
                                                <td style="cursor: pointer">{{ $i }}</td>
                                                <td class="text-center">
                                                    <button type="button" name="remove_client" {{ auth()->user()->rol_id != '1' ? "disabled" : "" }} class="btn btn-danger btn-sm" onclick="removeClient({{ json_encode($client) }})"><i class="bi bi-x-lg"></i></button>
                                                </td>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->adress }}</td>
                                                @if (auth()->user()->rol_id == '1')
                                                <td>{{ $client->dni }}</td>
                                                @endif
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
            <div class="col-12 col-xl-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Listado de clientes</h4>
                        <div class="table-responsive">
                            <table id="listTable" class="table DataTable table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Seleccionar</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        @if (auth()->user()->rol_id == '1')
                                        <th>DNI</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr data-id="{{ $client->id }}">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info" onclick="addClient({{ json_encode($client) }})"><i class="bi bi-arrow-left"></i></button>
                                            </td>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->adress }}</td>
                                            @if (auth()->user()->rol_id == '1')
                                            <td>{{ $client->dni }}</td>
                                            @endif
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
            $('#listTable').DataTable({
                "language": {
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
            if (userRol == 1) {
                $('#clientsTable').DataTable({
                    rowReorder: {
                        selector: 'td:first-child',
                        update: true
                    },
                    columnDefs: [
                        { orderable: false, targets: [0] } // Deshabilita la ordenación en la columna del control de reordenamiento
                    ],
                    scrollY: 600,
                    paging: false,
                    "language": {
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
            } else {
                $('#clientsTable').DataTable({
                    columnDefs: [
                        { orderable: false, targets: [0] } // Deshabilita la ordenación en la columna del control de reordenamiento
                    ],
                    scrollY: 600,
                    paging: false,
                    "language": {
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
            }
        });
    </script>

    <script>
        window.userRol = "{{ auth()->user()->rol_id }}";
    </script>

    <script>
        function removeFromTable(client_id, table_id) {
            $(`#${table_id}`).DataTable().row(`[data-id="${client_id}"]`).remove().draw();
        }

        function fillTable(client, table_id, action, btn_color, btn_icon, btn_size = "") {
            let totalClients = $(`#${table_id}`).DataTable().rows().count() + 1;
            let dni = "<td></td>";
            let index = "";
            if (userRol == '1' && client.dni !== null) {
                dni = `<td>${client.dni}</td>`;
            }
            if (table_id == 'clientsTable') {
                index = `<td style='cursor: pointer'>${totalClients}</td>`;
            }
            let content = `
                <tr data-id='${client.id}'>
                    ${index}
                    <td class="text-center">
                        <button type="button" class="btn btn-${btn_color} ${btn_size}" onclick='${action}(${JSON.stringify(client)})'><i class="bi bi-${btn_icon}"></i></button>
                    </td>
                    <td>${client.name}</td>
                    <td>${client.adress}</td>
                    ${dni}
                </tr>`;
            $(`#${table_id}`).DataTable().row.add($(content)).draw();
            if (table_id == 'listTable') {
                $(`#${table_id}`).DataTable().order([1, 'asc']).draw();
            }
        }

        function addClient(client) {
            removeFromTable(client.id, 'listTable');
            fillTable(client, 'clientsTable', 'removeClient', 'danger', 'x-lg', 'btn-sm');
        }

        function removeClient(client) {
            removeFromTable(client.id, 'clientsTable');
            fillTable(client, 'listTable', 'addClient', 'info', 'arrow-left');
        }
    </script>

    {{-- Send form --}}
    <script>
        function createClientsArray() {
            var clients = []; // arreglo para almacenar los clientes

            // Validar que hay filas en la tabla
            if ($("#clientsTable").DataTable().rows().count() == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No se ha seleccionado ningún cliente',
                    confirmButtonColor: '#1e88e5',
                });
                return;
            }
            
            // para cada fila de la tabla
            $('#clientsTable tbody tr').each(function(index) {
                var client = {}; // objeto para almacenar un cliente
                if (!$(this).find('button[name="remove_client"]').is(':disabled')) {
                    client.id = parseInt($(this).attr('data-id')); // obtener el id del cliente
                    clients.push(client); // agregar el cliente al arreglo de clientes
                }
            });

            // Agregar el arreglo de productos como un campo del formulario
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
                        allowOutsideClick: false,
                    })
                    .then((result) => {
                        if (result.isConfirmed && window.userRol != '1') {
                            window.location.href = "{{ route('route.details', ['id' => $route->id] ) }}";
                        } else if (result.isConfirmed && window.userRol == '1') {
                            window.location.reload();
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



    {{-- Nestable --}}
    <script type="text/javascript">
        $(document).ready(function() {
            var updateOutput = function(e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
                } else {
                    output.val('JSON browser support required');
                }
            };

            $('#nestable-menu').on('click', function(e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('#nestable-menu').nestable();
        });
    </script>

@endsection