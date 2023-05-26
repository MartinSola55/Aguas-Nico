@extends('layouts.app')

@section('content')
    <!-- Data table -->
    <link href="{{ secure_asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <link href="{{ secure_asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    
    
    <!-- This is data table -->
    <script src="{{ secure_asset('plugins/datatables/datatables.min.js') }}"></script>
    
    <!-- Datepicker -->
    <script src="{{ secure_asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ secure_asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/route/create') }}" id="form-create" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                <input type="hidden" name="user_id" id="dealerID">
                                <div class="form-column">
                                    <div class="col-12 mb-3">
                                        <label for="day_of_week" class="mb-0">Día del reparto</label>
                                        <select name="day_of_week" class="form-control" id="day_of_week" required>
                                            <option disabled selected value="">Seleccione un día</option>
                                            <option value="1">Lunes</option>
                                            <option value="2">Martes</option>
                                            <option value="3">Miércoles</option>
                                            <option value="4">Jueves</option>
                                            <option value="5">Viernes</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, seleccione un día
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button id="btnCreateRoute" type="submit" class="btn btn-info waves-effect waves-light">Confirmar</button>
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
                    <li class="breadcrumb-item active">Nuevo</li>
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
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Seleccione un repartidor</h4>
                        <div class="table-responsive m-t-10">
                            <table id="dealersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Camión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <p class="m-0" style="cursor: pointer; color: #009efb" data-toggle="modal" data-target="#modalConfirmation" onclick="openModal({{ $user->id }}, '{{ $user->name }}')">{{ $user->name }}</p>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->truck_number }}</td>
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
                        window.location.href = window.location.origin + "/route/" + response.data + "/newCart";
                    },
                    error: function(errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: errorThrown.responseJSON.message,
                            confirmButtonColor: '#1e88e5',
                        });
                    }
                });
            }
    </script>

    <script>
        $('#dealersTable').DataTable({
            "language": {
                // "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // La url reemplaza todo al español
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ repartidores",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 repartidores",
                "sInfoFiltered": "(filtrado de _MAX_ repartidores en total)",
                "emptyTable": 'No hay repartidores que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ repartidores",
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

    <script>
        function openModal(id, name) {
            $("#dealerID").val(id);
            $(".modal-title").html("Crear reparto para " + name);
            $("#day_of_week").val("");
        }
    </script>
@endsection