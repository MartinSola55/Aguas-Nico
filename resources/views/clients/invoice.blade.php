@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
@endphp
@extends('layouts.app')

@section('content')

    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- Print invoice -->
    <script src="{{ asset('js/jquery.PrintArea.js') }}" type="text/JavaScript"></script>

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
                    <li class="breadcrumb-item"><a href="{{ route('client.details', ['id' => $client->id]) }}">Detalles</a></li>
                    <li class="breadcrumb-item active">Facturación</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="text-center">
            <h1>{{ $client->name }}</h1>
            <hr>
        </div>
        <div class="row">
            <div id="datesContainer" class="col-xlg-6 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Intervalo de facturación</h4>
                        <form method="GET" action="{{ url('/client/searchSales') }}" id="form-sales" class="form-material m-t-30">
                            @csrf
                            <input type="hidden" name="client_id" value="{{ $client->id }}">
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
                            <div id="buttonDatesContainer" class="col-lg-12 d-flex flex-direction-row justify-content-end" style="display: none !important">
                                <button id="btnSearchSale" type="button" class="btn btn-info">Buscar ventas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="InvoiceDataContainer" class="col-xlg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Datos de facturación</h4>
                        <form class="form-material m-t-30">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <label for="invoiceType">Tipo factura</label>
                                    <select id="invoiceType" class="form-control">
                                        <option value="A" {{ $client->invoice_type === 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $client->invoice_type === 'B' ? 'selected' : '' }}>B</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="invoiceNumber">Número</label>
                                    <input id="invoiceNumber" type="number" class="form-control">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="invoiceName">Nombre y Apellido / Razón Social</label>
                                    <input id="invoiceName" type="text" class="form-control" value="{{ $client->business_name }}">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="invoiceCUIT">CUIT</label>
                                    <input id="invoiceCUIT" type="number" class="form-control" value="{{ $client->cuit }}">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="invoiceCondition">Condición frente al IVA</label>
                                    <select id="invoiceCondition" class="form-control">
                                        <option value="Responsable inscripto" {{ $client->tax_condition == 1 ? 'selected' : '' }}>Responsable inscripto</option>
                                        <option value="Monotributista" {{ $client->tax_condition == 2 ? 'selected' : '' }}>Monotributista</option>
                                        <option value="Excento" {{ $client->tax_condition == 3 ? 'selected' : '' }}>Excento</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="invoiceAdress">Domicilio</label>
                                    <input id="invoiceAdress" type="text" class="form-control" value="{{ $client->tax_address }}">
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex flex-direction-row justify-content-end">
                                <button id="buttonInvoiceData" type="button" class="btn btn-info">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body printableArea shadow">
                    <div class="d-flex flex-row justify-content-between">
                        <h3><b name="invoiceType">FACTURA -</b> <span id="invoiceNumberText" class="pull-right">#</span></h3>
                        <h3 class="pull-right m-0"><b>DOCUMENTO NO VÁLIDO LEGALMENTE</b></h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <address>
                                    <h3> &nbsp;<b class="text-danger">Sodería La Nueva S.A.</b></h3>
                                    <div class="d-flex flex-row justify-content-between">
                                        <p class="text-muted m-l-5">
                                            <b>Razón Social:</b> Sodería La Nueva S.A.<br/>
                                            <b>Domicilio Comercial:</b> Lorenza Aguilera 415 - Neuquén, Neuquén<br/>
                                            <b>Condición frente al IVA:</b> IVA Responsable Inscripto<br/>
                                        </p>
                                        <p class="text-muted m-l-5 text-right">
                                            <b>Fecha de Emisión:</b> {{ $today->format('d/m/Y') }}<br/>
                                            <b>CUIT: </b>30707808698<br/>
                                            <b>Ingresos Brutos: </b>915-720884-0<br/>
                                            <b>Fecha de Inicio de Actividades: </b>01/11/2001<br/>
                                        </p>
                                    </div>
                                </address>
                            </div>
                            <hr>
                            <div class="text-left">
                                <address>
                                    <h3><b>Hacia,</b></h3>
                                    <p class="text-muted m-l-30 mb-0" name="invoiceName"><b>Apellido y Nombre / Razón Social: </b></p>
                                    <p class="text-muted m-l-30 mb-0" name="invoiceCUIT"><b>CUIT: </b></p>
                                    <p class="text-muted m-l-30 mb-0" name="invoiceCondition"><b>Condición frente al IVA: </b></p>
                                    {{-- <p class="text-muted m-l-30 mb-0" name="invoice"><b>Condición de venta: </b>HAY QUE VER (Cuenta Corriente)</p> --}}
                                    <p class="text-muted m-l-30 mb-0" name="invoiceAdress"><b>Domicilio: </b></p>
                                    <hr>
                                    <p id="dateFromInvoice" class="m-t-30"><i class="fa fa-calendar"></i><b> Fecha desde : </b></p>
                                    <p id="dateToInvoice"><i class="fa fa-calendar"></i><b> Fecha hasta : </b></p>
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table id="invoiceProducts" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Descripción</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">Precio Unitario</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <p id="IVAAmount">IVA (21%) : $</p>
                                <hr>
                                <h3 id="totalAmount"><b>Total: </b>$</h3>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="text-right">
                                <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Imprimir</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para calcular el subtotal, iva y total automáticamente --}}
    <script>
        const formattedNumber = (number) => {
            return number.toLocaleString('es-AR', { minimumFractionDigits: 2 });
        }
        function calculateTotal () {
            let subtotal = 0;
            $('#invoiceProducts .productTotal').each(function() {
                const valor = $(this).text().replace('$', '').replace('.', '').replace(',', '.').trim();
                subtotal += parseFloat(valor);
            });
            $("#IVAAmount").html("IVA (21%) : $" + formattedNumber(subtotal*0.21))
            $("#totalAmount").html("<b>Total: </b>$" + formattedNumber(subtotal))
        }
    </script>
    
    <script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
    </script>

    <script>
        moment.locale('es');
        $('#dateFrom').bootstrapMaterialDatePicker({
            maxDate: new Date(),
            time: false,
            format: 'DD/MM/YYYY',
            cancelText: "Cancelar",
            weekStart: 1,
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
            });
            $("#dateFromInvoice").html(`<i class="fa fa-calendar"></i><b> Fecha desde : </b>` + $(this).val())
        });
        $("#dateTo").on("change", function() {
            $("#buttonDatesContainer").css("display", "flex");
            $("#dateToInvoice").html(`<i class="fa fa-calendar"></i><b> Fecha hasta : </b>` + $(this).val())
        });
    </script>

    <script>
        $("#buttonInvoiceData").on("click", function() {
            $('b[name="invoiceType"]').html("FACTURA " + $("#invoiceType").val())
            $('#invoiceNumberText').html("#" + $("#invoiceNumber").val())
            $('p[name="invoiceName"]').html("<b>Apellido y Nombre / Razón Social: </b>" + $("#invoiceName").val())
            $('p[name="invoiceCUIT"]').html("<b>CUIT: </b>" + $("#invoiceCUIT").val())
            $('p[name="invoiceCondition"]').html("<b>Condición frente al IVA: </b>" + $("#invoiceCondition").val())
            // $('p[name="invoice"]').html($("#invoice").val())
            $('p[name="invoiceAdress"]').html("<b>Domicilio: </b>" + $("#invoiceAdress").val())
        })
    </script>

    {{-- Buscar ventas para la facturación --}}
    <script>
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }

        $("#btnSearchSale").on("click", function() {
            $("#dateFromFormatted").val(formatDate($("#dateFrom").val()));
            $("#dateToFormatted").val(formatDate($("#dateTo").val()));
            $.ajax({
                url: $("#form-sales").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-sales").attr('method'), // Utiliza el método del formulario
                data: $("#form-sales").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    let content = "";
                    Object.values(response.data).forEach((item) => {
                        content += "<tr>";
                            content += "<td>" + item.name + "</td>";
                            content += "<td class='text-right'>" + item.quantity + "</td>";
                            content += "<td class='text-right'>$" + formattedNumber(parseInt(item.price)) + "</td>";
                            content += "<td class='text-right productTotal'>$" + formattedNumber(item.quantity * item.price) + "</td>";
                            content += "</tr>";
                    });
                    $("#invoiceProducts tbody").html(content);
                    calculateTotal();
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

@endsection