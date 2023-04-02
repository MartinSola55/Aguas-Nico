@extends('layouts.app')

@section('content')
    <script src="{{ asset('plugins/flot/excanvas.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('plugins/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('js/flot-data.js') }}"></script>

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Clientes</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/clients/index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active">Detalles</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div>
                            <a class="btn btn-primary waves-effect waves-light" href="{{ url('/product/create') }}">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Productos pedidos</h4>
                        <div class="flot-chart">
                            <div class="flot-chart-content" id="flot-pie-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-xlg-4 col-xs-12">
                        <div class="ribbon-wrapper card">
                            <div class="ribbon ribbon-default">Facturación</div>
                            <button type="button" class="btn btn-danger btn-rounded m-t-10 float-right">Generar PDF</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-xlg-4 col-xs-12">
                        <div class="ribbon-wrapper card">
                            <div class="ribbon ribbon-default">Deuda</div>
                            <p class="ribbon-content">$12500</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow" style="background-color: #ebebeb73">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Cliente</h3>
                            </div>
                            <div class="col-4 text-right">
                                <button id="btnEditInputs" class="btn btn-sm btn-danger btn-rounded px-3">Editar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="needs-validation" method="POST" action="{{ url('/client/edit') }}" id="form-edit" autocomplete="off" novalidate>
                            <h6 class="heading-small text-muted mb-4">Datos personales</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientName">Nombre</label>
                                            <input disabled required type="text" id="clientName" name="name" class="form-control form-control-alternative">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un nombre
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientDNI">DNI</label>
                                            <input disabled required type="number" id="clientDNI" name="dni" class="form-control form-control-alternative">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un DNI
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="clientAdress">Dirección</label>
                                            <input disabled required type="text" id="clientAdress" name="adress" class="form-control form-control-alternative">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese una dirección
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- Address -->
                            <h6 class="heading-small text-muted mb-4">Información de contacto</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientPhone">Teléfono</label>
                                            <input disabled required type="tel" id="clientPhone" name="phone" class="form-control form-control-alternative">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un teléfono
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientEmail">Email</label>
                                            <input disabled required type="email" id="clientEmail" name="email" class="form-control form-control-alternative">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un email
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <!-- Description -->
                            <h6 class="heading-small text-muted mb-4">Otros</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientDebt">Deuda</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input disabled required type="number" step="0.01" min="0" max="1000000" class="form-control form-control-alternative" id="clientDebt" name="debt">
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese un monto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group focused">
                                            <input disabled type="checkbox" id="clientInvoice" name="invoice" checked />
                                            <label for="clientInvoice">¿Quiere factura?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="divSaveClient" style="display: none">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-danger btn-rounded px-3">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                url: $("#form-edit").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-edit").attr('method'), // Utiliza el método del formulario
                data: $("#form-edit").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnEditInputs").click();
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
        $("#btnEditInputs").on("click", function() {
            $("#form-edit :input:not(:button)").prop('disabled', function(i, val) {
                return !val;    
            });
            $("#divSaveClient").toggle();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
