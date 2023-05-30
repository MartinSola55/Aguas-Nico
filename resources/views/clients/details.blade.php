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
                        <div class="card shadow">
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
                            <div class="col-12 col-sm-6">
                                <div class="ribbon-wrapper card shadow">
                                    <div class="ribbon ribbon-default">Facturación</div>
                                    <a href="{{ route('client.invoice', ['id' => $client->id]) }}"
                                        class="btn btn-info btn-rounded m-t-10 float-right {{ $client->invoice === false ? 'disabled' : '' }}">{{ $client->invoice === false ? 'No habilitada' : 'Ir' }}</a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="ribbon-wrapper card shadow">
                                    @if ($client->debt === 0)
                                        <div class="ribbon ribbon-default">Deuda</div>
                                        <p class="ribbon-content" id="clientDebtText">Sin deuda</p>
                                    @elseif ($client->debt > 0)
                                        <div class="ribbon ribbon-default">Deuda</div>
                                        <p class="ribbon-content" id="clientDebtText">${{ $client->debt }}</p>
                                    @else
                                        <div class="ribbon ribbon-default">Saldo a favor</div>
                                        <p class="ribbon-content" id="clientDebtText">${{ $client->debt * -1 }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Invoice data --}}
                        @if ($client->invoice == 'checked')
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card shadow" style="background-color: #ebebeb73">
                                        <div class="card-header bg-white border-0">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h3 class="mb-0">Datos de facturación</h3>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <button id="btnEditInvoice" class="btn btn-sm btn-outline-info btn-rounded px-3">Editar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <form role="form" class="needs-validation" method="POST" action="{{ url('/client/updateInvoiceData') }}" id="form-invoice" autocomplete="off" novalidate>
                                                <div class="pl-lg-4">
                                                    <input type="hidden" required value="{{ $client->id }}" name="id">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <div class="form-group focused">
                                                                <label class="form-control-label" for="invoiceType">Tipo de factura</label>
                                                                <select disabled required id="invoiceType" name="invoice_type" class="form-control form-select">
                                                                    <option disabled value="" {{ $client->invoice_type === null ? 'selected' : '' }}>Seleccione un tipo</option>
                                                                    <option value="A" {{ $client->invoice_type === 'A' ? 'selected' : '' }}>A</option>
                                                                    <option value="B" {{ $client->invoice_type === 'B' ? 'selected' : '' }}>B</option>
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Por favor, ingrese un tipo de factura
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            <div class="form-group focused">
                                                                <label class="form-control-label" for="taxCondition">Condición frente al IVA</label>
                                                                <select disabled required id="taxCondition" name="tax_condition" class="form-control form-select">
                                                                    <option disabled {{ $client->tax_condition === null ? 'selected' : '' }} value="">Seleccione una condición</option>
                                                                    <option value="1" {{ $client->tax_condition == 1 ? 'selected' : '' }}>Responsable Inscripto</option>
                                                                    <option value="2" {{ $client->tax_condition == 2 ? 'selected' : '' }}>Monotributista</option>
                                                                    <option value="3" {{ $client->tax_condition == 3 ? 'selected' : '' }}>Excento</option>
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Por favor, ingrese una condición frente al IVA
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="clientBusinessName">Razón Social</label>
                                                                <input disabled required type="text" id="clientBusinessName" name="business_name" class="form-control form-control-alternative" value="{{ $client->business_name }}">
                                                                <div class="invalid-feedback">
                                                                    Por favor, ingrese una razón social
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="clientCUIT">CUIT</label>
                                                                <input disabled required type="number" id="clientCUIT" name="cuit" class="form-control form-control-alternative" value="{{ $client->cuit }}">
                                                                <div class="invalid-feedback">
                                                                    Por favor, ingrese un CUIT
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label class="form-control-label" for="clientTaxAdress">Dirección de facturación</label>
                                                                <input disabled required type="text" id="clientTaxAdress" name="tax_address" class="form-control form-control-alternative" value="{{ $client->tax_address }}">
                                                                <div class="invalid-feedback">
                                                                    Por favor, ingrese una dirección de facturación
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="divSaveInvoiceData" style="display: none">
                                                        <hr class="my-4">
                                                        <div class="d-flex flex-end">
                                                            <button type="submit" class="btn btn-sm btn-success btn-rounded px-3 mr-3">Guardar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="card-title">Productos asociados</h3>
                                    </div>
                                    <div class="col-4 text-right mb-3">
                                        <button id="btnEditProducts"
                                            class="btn btn-sm btn-outline-info btn-rounded px-3">Editar</button>
                                    </div>
                                </div>
                                <div>
                                    <form role="form" method="POST" action="{{ url('/client/updateProducts') }}" id="form-products">
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <input type="hidden" name="products_quantity" value="">
                                        @csrf
                                        <table class="table table-hover table-bordered table-grey" id="products_table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="col-1">Asociar</th>
                                                    <th scope="col" class="col-1">Cantidad</th>
                                                    <th scope="col" class="col-10">Nombre</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($client_products as $product)
                                                    <tr data-id="{{ $product['id'] }}">
                                                        <td class="text-center">
                                                            <input id="product_{{ $product['id'] }}"
                                                                name="product_{{ $product['id'] }}" type="checkbox" class="form-control" {{ $product['active'] === true ? 'checked' : '' }} disabled>
                                                            <label for="product_{{ $product['id'] }}" class="pl-3 mb-0"></label>
                                                        </td>
                                                        <td class="text-center"><input type="number" class="form-control" disabled value="{{ $product['stock'] }}" min="0" max="10000"></td>
                                                        <td>{{ $product['name'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row" id="divSaveProducts" style="display: none">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="button" id="btnSaveProducts" class="btn btn-sm btn-success btn-rounded px-3">Guardar</button>
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
                                <button id="btnEditInputs"
                                    class="btn btn-sm btn-outline-info btn-rounded px-3">Editar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="needs-validation" method="POST" action="{{ url('/client/edit') }}"
                            id="form-edit" autocomplete="off" novalidate>
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
                                            <input disabled type="number" id="clientDNI" name="dni" class="form-control form-control-alternative" value="{{ $client->dni }}">
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
                                                <input disabled required type="number" step="0.01" max="1000000" class="form-control form-control-alternative" id="clientDebt" name="debt" value="{{ $client->debt }}">
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
                                                <textarea disabled type="text" class="form-control form-control-alternative" id="clientObservation"
                                                    name="observation" value="{{ $client->observation }}"></textarea>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese una descripción
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="divSaveClient" style="display: none">
                                    <div class="col-md-12 d-flex justify-content-between">
                                        @if ($client->is_active)
                                            <button type="button" id="btnDeleteClient"
                                                class="btn btn-sm btn-outline-danger btn-rounded px-3">Dar de baja</button>
                                        @else
                                            <button type="button" id="btnDeleteClient"
                                                class="btn btn-sm btn-outline-warning btn-rounded px-3">Dar de
                                                alta</button>
                                        @endif
                                        <button type="submit"
                                            class="btn btn-sm btn-success btn-rounded px-3">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <form id="formDeleteClient" action="{{ url('/client/setIsActive') }}" method="POST">
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
                var forms = $('.needs-validation');
                forms.on('submit', function(event) {
                    event.preventDefault();
                    
                    var form = $(this);
                    if (form[0].checkValidity() === false) {
                        event.stopPropagation();
                    } else {
                        if (form.attr('id') == "form-edit") {
                            sendClientDataForm();
                        } else {
                            sendInvoiceDataForm();
                        }
                    }
                    form.addClass('was-validated');
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
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#1e88e5',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
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
        }

        function sendInvoiceDataForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-invoice").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-invoice").attr('method'), // Utiliza el método del formulario
                data: $("#form-invoice").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnEditInvoice").click();
                    $("#form-invoice").removeClass('was-validated')
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        confirmButtonColor: '#1e88e5',
                    });
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
                    title: '¿Seguro deseas cambiar el estado de este cliente?',
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
                        $.ajax({
                            url: $("#formDeleteClient").attr(
                            'action'), // Utiliza la ruta del formulario
                            method: $("#formDeleteClient").attr(
                            'method'), // Utiliza el método del formulario
                            data: $("#formDeleteClient")
                        .serialize(), // Utiliza los datos del formulario
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    confirmButtonColor: '#1e88e5',
                                });
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
                })
        });
    </script>

    {{-- Datos de facturación --}}
    <script>
        $("#btnEditInvoice").on("click", function() {
            $("#form-invoice :input:not(:button)").prop('disabled', function(i, val) {
                return $(this).attr('name') === 'id' || $(this).attr('name') === '_token' ? false : !val;
            });
            $("#divSaveInvoiceData").toggle();
        });
    </script>


    {{-- Productos del cliente --}}
    <style>
        .table-grey tbody td:not(:last-child) {
            background-color: #f6f6f6;
        }
    </style>

    <script>
        $("#btnEditProducts").on("click", function() {
            $("#form-products :input[type='checkbox']").prop('disabled', function(i, val) {
                return !val;
            });
            $("#form-products :input[type='checkbox']").each(function() {
                let checkbox = $(this);
                let numberInput = checkbox.closest("tr").find("input[type='number']");
                if (checkbox.prop('disabled')) {
                    numberInput.prop('disabled', true);
                } else if (checkbox.prop('checked')) {
                    numberInput.prop('disabled', false);
                } else {
                    numberInput.prop('disabled', true);
                }
            });
            $("#divSaveProducts").toggle();

            if ($("#products_table").hasClass("table-grey"))
                $("#products_table").removeClass("table-grey");
            else
                $("#products_table").addClass("table-grey");
        });

        $("#form-products").on("change", ":checkbox", function() {
            let checkbox = $(this);
            let numberInput = checkbox.closest("tr").find("input[type='number']");

            numberInput.prop('disabled', !checkbox.prop('checked'));
        });

        function createProductsJSON() {
            // Productos
            let products = [];
            $('#form-products table tbody tr').each(function() {
                let productId = $(this).data('id');
                let quantity = $(this).find('input[type="number"]').val();
                let checked = $(this).find('input[type="checkbox"]').prop('checked');
                if (quantity !== "" && checked) {
                    products.push({
                        product_id: productId,
                        quantity: quantity
                    });
                }
            });
            $("#form-products input[name='products_quantity']").val(JSON.stringify(products));
        };

        $("#btnSaveProducts").on("click", function(e) {
            createProductsJSON();
            $.ajax({
                url: $("#form-products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-products").attr('method'), // Utiliza el método del formulario
                data: $("#form-products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnEditProducts").click();
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#1e88e5',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
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
        });
    </script>

    <script>
        // DATA GRAFICO
        $(function() {
            let data = [];
            data = @json($graph);
            if (data.length > 0) {
                var plotObj = $.plot($("#flot-pie-chart"), data, {
                    series: {
                        pie: {
                            innerRadius: 0.5,
                            show: true
                        }
                    },
                    grid: {
                        hoverable: true
                    },
                    color: null,
                    tooltip: true,
                    tooltipOpts: {
                        content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                        shifts: {
                            x: 20,
                            y: 0
                        },
                        defaultTheme: false
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
