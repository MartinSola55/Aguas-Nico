@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
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
            <h1>Facturación de <b>{{ $route->User->name }}</b> para el día <b>{{ $diasSemana[$route->day_of_week] }}</b></h1>
            <hr>
        </div>
        <div class="row">
            <div id="datesContainer" class="col-xlg-6 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Intervalo de facturación</h4>
                        <form method="GET" action="{{ url('/invoice/searchAllSales') }}" id="form-sales" class="form-material m-t-30">
                            @csrf
                            <input type="hidden" name="dateFrom" id="dateFromFormatted" value="">
                            <input type="hidden" name="dateTo" id="dateToFormatted" value="">
                            <input type="hidden" name="route_id" value="{{ $route->id }}">
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
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body printableArea shadow">
                    <div class="d-flex flex-row justify-content-between">
                        <h3><b name="invoiceType">FACTURA</b></h3>
                        <h3 class="pull-right m-0"><b>ORIGINAL</b></h3>
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
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <div id="tables_container" class="table-responsive m-t-40" style="clear: both;">
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
            $('#tables_container .productTotal').each(function() {
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
                popClose: close,
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
            $("#dateFromInvoice").html(`<i class="fa fa-calendar"></i><b> Fecha desde : </b>` + $(this).val())
        });
        $("#dateTo").on("change", function() {
            $("#buttonDatesContainer").css("display", "flex");
            $("#dateToInvoice").html(`<i class="fa fa-calendar"></i><b> Fecha hasta : </b>` + $(this).val())
        });
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
                    response.data.clients.forEach((client) => {
                        content += 
                        `<h1 class='text-start mt-3 mb-0'>${client.name}</h1>
                        <h3 class='text-start mt-1 mb-0'>Tipo de factura: ${client.invoice_type ?? "Sin cargar"} - CUIT: ${client.cuit ?? "Sin cargar"}</h3>
                        <table class="table table-hover mb-3">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unitario</th>
                                    <th class="text-right">Fecha</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>`;
                        let sum = 0;
                        client.abonos.forEach((item) => {
                            sum += 1 * item.price;
                            content += 
                            `<tr>
                                <td>${item.name}</td>
                                <td class='text-right'>1</td>
                                <td class='text-right'>$${formattedNumber(parseInt(item.price))}</td>
                                <td class='text-right'>${item.date}</td>
                                <td class='text-right productTotal'>$${formattedNumber(parseInt(item.price))}</td>
                            </tr>`;
                        });
                        client.products.forEach((item) => {
                            sum += item.quantity * item.price;
                            content += 
                            `<tr>
                                <td>${item.name}</td>
                                <td class='text-right'>${item.quantity}</td>
                                <td class='text-right'>$${formattedNumber(parseInt(item.price))}</td>
                                <td class='text-right'>${item.date}</td>
                                <td class='text-right productTotal'>$${formattedNumber(item.quantity * item.price)}</td>
                            </tr>`;
                        });
                        content += 
                        `<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class='text-right'><b style='font-weight: bold'>Total: $${formattedNumber(sum)}</b></td>
                        </tr>
                        </tbody>
                        </table>
                        <hr class='mb-5'>`;
                    });
                    $("#tables_container").html(content);
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