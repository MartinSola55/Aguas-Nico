@extends('layouts.app')

@section('content')
    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

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
                <h3 class="text-themecolor m-b-0 m-t-0">Transferencias</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Transferencias</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <!-- Modal transfer data -->
        <div id="modalTransferData" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modalTitle" class="modal-title"></h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-column">
                                    <div class="col-12 mb-3">
                                        <label for="transferAmount" class="mb-0">Monto</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="transferAmount" placearia-describedby="inputGroupPrepend" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un monto
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="user_id" class="mb-0">Repartidor</label>
                                        <select class="form-control" id="user_id">
                                            <option disabled selected value="">Seleccione un repartidor</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button id="btnOpenModalClients" type="button" class="btn btn-success waves-effect waves-light">Buscar cliente</button>
                        <button id="btnOpenModalClientsHidden" type="button" data-toggle="modal" data-target="#modalTransferClient" style="display: none"></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

        <!-- Modal transfer client -->
        <div id="modalTransferClient" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/transfer/create') }}" id="form-create" autocomplete="off" novalidate>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Seleccionar cliente</h4>
                            <button type="button" class="close" id="btnCloseModalClients" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-column">
                                        {{-- TOKEN --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="transfer_id" value="" />
                                        <input type="hidden" name="user_id" value="" />
                                        <input type="hidden" name="client_id" value="" />
                                        <input type="hidden" name="amount" value="" />
                                        <input type="hidden" name="received_from" value="" />

                                        <label for="inputSearch">Nombre del cliente</label>
                                        <div class="d-flex flex-row">
                                            <input id="inputSearchClients" type="text" class="form-control" >
                                            <button id="btnSearchClients" type="button" class="btn btn-primary waves-effect waves-light pr-3">Buscar</button>
                                        </div>

                                        <div id="ClientsTableContainer" class="table-responsive m-t-20">
                                            <table id="ClientsTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnCreateTransfer" type="button" class="btn btn-success waves-effect waves-light">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->

        <div class="row">
            <div id="datesContainer" class="col-xlg-6 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Intervalo de transferencias</h4>
                        <form method="GET" action="{{ url('/transfer/searchTransfers') }}" id="form-transfers" class="form-material m-t-30">
                            @csrf
                            <input type="hidden" name="dateFrom" id="dateFromFormatted" value="">
                            <input type="hidden" name="dateTo" id="dateToFormatted" value="">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="dateFrom">Fecha inicio</label>
                                    <input id="dateFrom" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                </div>
                                <div id="dateToContainer" class="form-group col-lg-6" style="display: none">
                                    <label for="dateTo">Fecha fin</label>
                                    <input id="dateTo" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h2 class="card-title">Listado de transferencias</h4>
                            <button id="btnAddTransfer" type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#modalTransferData">Nueva transferencia</button>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table id="DataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Repartidor</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">
                                    @foreach ($transfers as $transfer)
                                        <tr data-id='{{ $transfer->id }}'>
                                            <td>{{ $transfer->client->name }}</td>
                                            <td>{{ $transfer->user->name }}</td>
                                            <td>${{ $transfer->amount }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type='button' class='btn btn-outline-info btn-rounded btn-sm mr-2' onclick='editObj({{ json_encode($transfer) }})' data-toggle="modal" data-target="#modalTransferData"><i class="bi bi-pen"></i></button>
                                                    <button type='button' class='btn btn-danger btn-rounded btn-sm ml-2' onclick='deleteObj({{ $transfer->id }})'><i class='bi bi-trash3'></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="/transfer/delete" method="post" id="form-delete">
            @csrf
            <input type="hidden" name="id" id="transfer_id">
        </form>

        <form action="/transfer/searchClients" method="get" id="form-searchClients">
            @csrf
            <input type="hidden" name="id" id="transfer_id">
            <input type="hidden" name="name" id="client_name">
        </form>
    </div>

    <script>
        moment.locale('es');
        $('#dateFrom').bootstrapMaterialDatePicker({
            maxDate: new Date(),
            time: false,
            format: 'DD/MM/YYYY',
            cancelText: "Cancelar",
            weekStart: 1,
            lang: 'es',
        });

        $("#dateFrom").on("change", function() {
            $("#datesContainer").removeClass("col-lg-5");
            $("#datesContainer").addClass("col-lg-6");
            $("#dateToContainer").css("display", "block");
            $('#dateTo').bootstrapMaterialDatePicker({
                minDate: $("#dateFrom").val(),
                maxDate: new Date(),
                time: false,
                format: 'DD/MM/YYYY',
                cancelText: "Cancelar",
                weekStart: 1,
                lang: 'es',
            });
        });
        $("#dateTo").on("change", function() {
            searchTransfers()
        });
        $("#dateFrom").on("change", function() {
            if ($("#dateTo").val() != "") {
                searchTransfers();
            }
        });

        $('#ClientsTable').DataTable({
            "ordering": false,
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

        $('#DataTable').DataTable({
            "ordering": false,
            "language": {
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ transferencias",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 transferencias",
                "sInfoFiltered": "(filtrado de _MAX_ transferencias en total)",
                "emptyTable": 'No hay transferencias que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ transferencias",
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

    {{-- Buscar transferencias --}}
    <script>
        function createLocalDate(date) {
            return new Date(date).toLocaleString("es-AR", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
            });
        }
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }

        function searchTransfers() {
            $("#dateFromFormatted").val(formatDate($("#dateFrom").val()));
            $("#dateToFormatted").val(formatDate($("#dateTo").val()));
            sendForm("transfers");
        };
    </script>

    {{-- Change modals --}}
    <script>
        const fireAlert = (text) => {
            Swal.fire({
                icon: 'warning',
                title: 'ALERTA',
                text: text,
                showCancelButton: false,
                confirmButtonColor: '#1e88e5',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            });
            return false;
        };

        $("#btnAddTransfer").on("click", function () {
            $("#form-create").attr('action', "/transfer/create");
            $("#modalTitle").text("Nueva transferencia");
            $("#form-create input:not([name='_token'])").val("");
            $("#user_id option:first").prop("selected", true);
            $("#transferAmount").val("");
        });

        $("#btnOpenModalClients").on("click", function() {
            if ($("#transferAmount").val() <= 0 || $("#transferAmount").val() == "") return fireAlert("Debes ingresar un monto");
            if ($("#user_id").val() == "" || $("#user_id").val() == null) return fireAlert("Debes ingresar un repartidor");
            $("#btnCloseModal").click();
            $("#btnOpenModalClientsHidden").click();
            $("#ClientsTableContainer").css("display", "none");

            // Asignar valores de inputs del modal antererior al nuevo modal
            $("#form-create input[name='user_id']").val($("#user_id").val());
            $("#form-create input[name='amount']").val($("#transferAmount").val());
        });

        $("#btnSearchClients").on("click", function() {
            let name = $("#inputSearchClients").val();
            $("#client_name").val(name);
            $('#ClientsTable').DataTable().clear().draw();
            sendForm("searchClients");
            $("#ClientsTableContainer").css("display", "flex");
        });
        
        $("#btnCreateTransfer").on("click", function() {
            if ($("#form-create input[name='client_id']").val() == "") return fireAlert("Debes seleccionar un cliente");
            sendForm("create");
        });

        $('#ClientsTable tbody').on('click', 'tr', function() {
            $('#ClientsTable tbody tr').removeClass('selected');
            $(this).addClass('selected');

            let clientId = $(this).data('id');
            $('#form-create input[name="client_id"]').val(clientId);
        });
    </script>

    <style>
        /* Color para hover */
        #ClientsTable tbody tr:hover {
            background-color: #a1bef787;
        }

        /* Color seleccionado */
        #ClientsTable tbody tr.selected {
            background-color: #a1bef787;
        }

    </style>

    {{-- Genéricos --}}
    <script>
        function fillTable(items) {
            items.forEach(item => {
                let content = `
                    <tr data-id='${item.id}'>
                        <td>${item.client.name}</td>
                        <td>${item.user.name}</td>
                        <td>$${item.amount}</td>
                        <td>${createLocalDate(item.created_at)}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type='button' class='btn btn-outline-info btn-rounded btn-sm mr-2' onclick='editObj(${JSON.stringify(item)})' data-toggle="modal" data-target="#modalTransferData"><i class="bi bi-pen"></i></button>
                                <button type='button' class='btn btn-danger btn-rounded btn-sm ml-2' onclick='deleteObj(${item.id})'><i class='bi bi-trash3'></i></button>
                            </div>
                        </td>
                    </tr>`;
                $('#DataTable').DataTable().row.add($(content)).draw();
            });
        }

        function removeFromTable(id) {
            $('#DataTable').DataTable().row(`[data-id="${id}"]`).remove().draw();
        }

        function drawClientsTable(clients) {
            clients.forEach(client => {
                let content = `
                    <tr data-id='${client.id}' style='cursor: pointer'>
                        <td>${client.name}</td>
                    </tr>`;
                $('#ClientsTable').DataTable().row.add($(content)).draw();
            });
        }

        function sendForm(action) {
            let form = document.getElementById(`form-${action}`);

            // Enviar solicitud AJAX
            $.ajax({
                url: $(form).attr('action'), // Utiliza la ruta del formulario
                method: $(form).attr('method'), // Utiliza el método del formulario
                data: $(form).serialize(), // Utiliza los datos del formulario
                success: function (response) {
                    if (action === 'create') {
                        $("#btnCloseModalClients").click();
                        if ($("#form-create input[name='transfer_id']").val() != "") {
                            removeFromTable(response.data.id);
                            fillTable([response.data]);
                        }
                    } else if (action === 'delete') {
                        removeFromTable(response.data);  
                    } else if (action === 'searchClients') {
                        drawClientsTable(response.data);
                        return;
                    } else if (action === 'transfers') {
                        $('#DataTable').DataTable().clear().draw();
                        fillTable(response.data);
                        return;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        confirmButtonColor: '#1e88e5',
                    });
                },
                error: function (errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.title,
                        text: errorThrown.responseJSON.message,
                        confirmButtonColor: '#1e88e5',
                    });
                }
            });
        };

        function deleteObj(id) {
            Swal.fire({
                title: '¿Seguro deseas eliminar esta transferencia?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger waves-effect waves-light px-3 py-2',
                    cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                }
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $("#transfer_id").val(id);
                    sendForm("delete");
                }
            });
        };

        function editObj(item) {
            $("#form-create input[name='transfer_id']").val(item.id);
            $("#form-create").attr('action', "/transfer/edit");
            $("#modalTitle").text("Editar transferencia");
            $("#user_id").val(item.user.id);
            $("#transferAmount").val(item.amount);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
@endsection
