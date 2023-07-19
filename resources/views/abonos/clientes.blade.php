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
                <h3 class="text-themecolor m-b-0 m-t-0">Abonos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{-- {{ url('/abono/index') }}--}}">Abonos</a></li> 
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

        <!-- Modal transfer data -->
        <div id="modalAbonoData" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/abono/edit') }}" id="form-edit" autocomplete="off" novalidate>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="modalTitle" class="modal-title">Editar abono</h4>
                            <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-column">
                                        {{-- TOKEN --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="abono_id" value="" />

                                        <div class="col-12 mb-3">
                                            <label for="abonoAvailable" class="mb-0">Disponible</label>
                                            <div class="input-group">
                                                <input type="number" name="available" step="1" min="0" max="100" class="form-control" id="abonoAvailable" placearia-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese una cantidad
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button id="btnEditAbono" type="button" class="btn btn-success waves-effect waves-light">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Listado de abonos</h2>
                        <div class="table-responsive m-t-20">
                            <table id="DataTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Abono</th>
                                        <th>Disponible</th>
                                        <th>Precio</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body">
                                    @foreach ($abonos as $abono)
                                        <tr data-id='{{ $abono->id }}'>
                                            <td>{{ $abono->Client->name }}</td>
                                            <td>{{ $abono->Abono->name }}</td>
                                            <td>{{ $abono->available }}</td>
                                            <td>${{ $abono->setted_price }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type='button' class='btn btn-outline-info btn-rounded btn-sm mr-2' onclick='editObj({{ json_encode($abono) }})' data-toggle="modal" data-target="#modalAbonoData"><i class="bi bi-pen"></i></button>
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

        <form action="/abono/searchClients" method="get" id="form-searchClients">
            @csrf
            <input type="hidden" name="id" id="abono_id">
            <input type="hidden" name="name" id="client_name">
        </form>
    </div>

    <script>
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
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ abonos",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 abonos",
                "sInfoFiltered": "(filtrado de _MAX_ abonos en total)",
                "emptyTable": 'No hay abonos que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ abonos",
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

    {{-- Buscar abonos --}}
    <script>
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }
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
        
        $("#btnEditAbono").on("click", function() {
            let value = $("#form-edit input[name='available']").val();
            if (value == "" || value < 0) return fireAlert("Debes ingresar una cantidad");
            sendForm("edit");
        });
    </script>

    {{-- Genéricos --}}
    <script>
        function fillTable(items) {
            items.forEach(item => {
                let content = `
                    <tr data-id='${item.id}'>
                        <td>${item.client.name}</td>
                        <td>${item.abono.name}</td>
                        <td>${item.available}</td>
                        <td>$${item.setted_price}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button type='button' class='btn btn-outline-info btn-rounded btn-sm mr-2' onclick='editObj(${JSON.stringify(item)})' data-toggle="modal" data-target="#modalAbonoData"><i class="bi bi-pen"></i></button>
                            </div>
                        </td>
                    </tr>`;
                $('#DataTable').DataTable().row.add($(content)).draw();
            });
        }

        function removeFromTable(id) {
            $('#DataTable').DataTable().row(`[data-id="${id}"]`).remove().draw();
        }

        function sendForm(action) {
            let form = document.getElementById(`form-${action}`);

            // Enviar solicitud AJAX
            $.ajax({
                url: $(form).attr('action'), // Utiliza la ruta del formulario
                method: $(form).attr('method'), // Utiliza el método del formulario
                data: $(form).serialize(), // Utiliza los datos del formulario
                success: function (response) {

                    $("#btnCloseModal").click();
                    removeFromTable(response.data.id);
                    fillTable([response.data]);
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

        function editObj(item) {
            $("#form-edit input[name='abono_id']").val(item.id);
            $("#abonoAvailable").val(item.available);
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
@endsection
