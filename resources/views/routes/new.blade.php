@extends('layouts.app')

@section('content')
    <!-- Footable CSS -->
    <link href="{{ asset('plugins/footable/css/footable.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Footable -->
    <script src="{{ asset('plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('plugins/footable/js/footable.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <!--FooTable init-->
    <script src="{{ asset('js/footable-init.js') }}"></script>
    
    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/route/create') }}" id="form-create" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Crear reparto</h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                <input type="hidden" name="id" id="dealerID">
                                <div class="form-column">
                                    <div class="col-12 mb-3">
                                        <label for="routeDay" class="mb-0">Día</label>
                                        <select class="form-control" id="routeDay" name="day_of_week" required>
                                            <option value="" selected disabled>Seleccione un día</option>
                                            <option value="0">Lunes</option>
                                            <option value="1">Martes</option>
                                            <option value="2">Miércoles</option>
                                            <option value="3">Jueves</option>
                                            <option value="4">Viernes</option>
                                            <option value="5">Sábado</option>
                                            <option value="6">Domingo</option>
                                        </select>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, seleccione un día
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="datePicker" class="mb-0">Día y hora de inicio</label>
                                        <input type="text" class="form-control" placeholder="dd/mm/aaaa - HH:MM" id="datePicker" name="date" required>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese un día y horario
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Confirmar</button>
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
                    <li class="breadcrumb-item"><a href="{{ url('/routes/index') }}">Repartos</a></li>
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
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Seleccionar un repartidor</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Camión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="cursor: pointer" data-toggle="modal" data-target="#modalConfirmation">
                                        <td>Martín Sola</td>
                                        <td>martinrsola55@gmail.com</td>
                                        <td>3</td>
                                    </tr>
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
                            'Todo piola gato',
                            'Se agregó correctamente',
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
        moment.locale('es');
        $('#datePicker').bootstrapMaterialDatePicker({
            format: 'DD/MM/YYYY - HH:mm',
            minDate: new Date(),
            cancelText: "Cancelar",
            weekStart: 1,
        });
    </script>

@endsection