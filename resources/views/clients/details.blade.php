@extends('layouts.app')

@section('content')
    <script src="{{ asset('plugins/flot/excanvas.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('plugins/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    {{-- <script src="{{ asset('js/flot-data.js') }}"></script> --}}

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Clientes</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/client/index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active">Detalles</li>
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
            <div class="col">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 id="proucts_ordered" class="card-title">Productos pedidos</h4>
                                <div class="flot-chart" id="pie_chart">
                                    <div class="flot-chart-content" id="flot-pie-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-md-4 col-12 col-sm-6">
                                <div class="ribbon-wrapper card">
                                    <div class="ribbon ribbon-default">Facturación</div>
                                    <a href="{{ route('client.invoice', ['id' => $client->id]) }}" class="btn btn-danger btn-rounded m-t-10 float-right">Ir</a>
                                </div>
                            </div>
                            <div class="col-md-4 col-12 col-sm-6">
                                <div class="ribbon-wrapper card">
                                    <div class="ribbon ribbon-default">Deuda</div>
                                    <p class="ribbon-content" id="clientDebtText">${{ $client->debt }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="card-title">Productos asociados</h3>
                                    </div>
                                    <div class="col-4 text-right mb-3">
                                        <button id="btnEditProducts" class="btn btn-sm btn-danger btn-rounded px-3">Editar</button>
                                    </div>
                                </div>
                                <div>
                                    <form role="form" method="POST" action="{{ url('/client/updateProducts') }}" id="form-products">
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        @csrf
                                        <table class="table table-hover table-bordered table-grey" id="products_table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="col-1">Asociar</th>
                                                    <th scope="col" class="col-11">Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($client_products as $product)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input id="product_{{ $product["id"] }}" name="product_{{ $product["id"] }}" type="checkbox" class="form-control" {{ $product["active"] === true ? "checked" : ""}} disabled>
                                                            <label for="product_{{ $product["id"] }}" class="pl-3 mb-0"></label>
                                                        </td>
                                                        <td>{{ $product["name"] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row" id="divSaveProducts" style="display: none">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="button" id="btnSaveProducts" class="btn btn-sm btn-danger btn-rounded px-3">Guardar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                                <input type="hidden" required value="{{ $client->id }}" name="id">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientName">Nombre</label>
                                            <input disabled required type="text" id="clientName" name="name" class="form-control form-control-alternative" value="{{ $client->name }}">
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un nombre
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientDNI">DNI</label>
                                            <input disabled required type="number" id="clientDNI" name="dni" class="form-control form-control-alternative" value="{{ $client->dni }}">
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
                                            <input disabled required type="text" id="clientAdress" name="adress" class="form-control form-control-alternative" value="{{ $client->adress }}">
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
                                            <input disabled required type="tel" id="clientPhone" name="phone" class="form-control form-control-alternative" value="{{ $client->phone }}">
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
                                            <input disabled required type="email" id="clientEmail" name="email" class="form-control form-control-alternative" value="{{ $client->email }}">
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
                                                <input disabled required type="number" step="0.01" min="0" max="1000000" class="form-control form-control-alternative" id="clientDebt" name="debt" value="{{ $client->debt }}">
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese un monto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                        <div>
                                            <input disabled type="checkbox" id="clientInvoice" name="invoice" value="1" {{ $client->invoice ? 'checked' : '' }} />
                                            <label class="m-0" for="clientInvoice">¿Quiere factura?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group focused">
                                            <label class="form-control-label" for="clientDebt">Observación</label>
                                            <div class="input-group">
                                                <textarea disabled type="text" class="form-control form-control-alternative" id="clientObservation" name="observation" value="{{ $client->observation }}"></textarea>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese una descripción
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="divSaveClient" style="display: none">
                                    <div class="col-md-12 d-flex justify-content-between">
                                        <button type="button" id="btnDeleteClient" class="btn btn-sm btn-primary btn-rounded px-3">Eliminar</button>
                                        <button type="submit" class="btn btn-sm btn-danger btn-rounded px-3">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form id="formDeleteClient" action="{{ url('/client/delete') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $client->id }}">
        </form>
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
                            sendClientDataForm();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        function sendClientDataForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-edit").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-edit").attr('method'), // Utiliza el método del formulario
                data: $("#form-edit").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnEditInputs").click();
                    $("#clientDebtText").text("$" + $("#clientDebt").val());
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

    {{-- Datos del cliente --}}
    <script>
        $("#btnEditInputs").on("click", function() {
            $("#form-edit :input:not(:button)").prop('disabled', function(i, val) {
                return $(this).attr('name') === 'id' || $(this).attr('name') === '_token' ? false : !val;
            });
            $("#divSaveClient").toggle();
        });


        $("#btnDeleteClient").on("click", function() {
            Swal.fire({
                title: 'Seguro deseas eliminar este cliente?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
                })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $("#formDeleteClient").attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteClient").attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteClient").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cliente eliminado correctamente',
                            });
                        },
                        error: function(errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: errorThrown.responseJSON.message,
                            });
                        }
                    });
                }
            })
        });
    </script>


    {{-- Productos del cliente --}}
    <style>
        .table-grey tbody td:first-child {
            background-color: #f6f6f6;
        }
    </style>
    <script>
        $("#btnEditProducts").on("click", function() {
            $("#form-products :input[type='checkbox']").prop('disabled', function(i, val) {
                return !val;
            });
            $("#divSaveProducts").toggle();

            if ($("#products_table").hasClass("table-grey"))
                $("#products_table").removeClass("table-grey");
            else
                $("#products_table").addClass("table-grey");
        });

        $("#btnSaveProducts").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-products").attr('method'), // Utiliza el método del formulario
                data: $("#form-products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnEditProducts").click();
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
        });
    </script>

    <script>
        // DATA GRAFICO
        $(function () {
            let data = [];
            data = @json($graph);
            if (data.length > 0) {
                var plotObj = $.plot($("#flot-pie-chart"), data, {
                    series: {
                        pie: {
                            innerRadius: 0.5
                            , show: true
                        }
                    }
                    , grid: {
                        hoverable: true
                    }
                    , color: null
                    , tooltip: true
                    , tooltipOpts: {
                        content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20
                            , y: 0
                        }
                        , defaultTheme: false
                    }
                });
            } else {
                $("#proucts_ordered").text("El cliente no ha realizado ningún pedido");
                $("#pie_chart").css("display", "none");
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
