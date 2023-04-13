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
                    <li class="breadcrumb-item"><a href="{{ url('/clients/index') }}">Clientes</a></li>
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
        <div class="row">
            <div id="datesContainer" class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Intervalo de facturación</h4>
                        <form class="form-material m-t-30">
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
                            <div id="buttonContainer" class="col-lg-12 d-flex flex-direction-row justify-content-end" style="display: none !important">
                                <button type="button" class="btn btn-danger">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body printableArea">
                    <div class="d-flex flex-row justify-content-between">
                        <h3><b>FACTURA B</b> <span class="pull-right">#5669626</span></h3>
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
                            <hr>
                            <div class="text-left">
                                <address>
                                    <h3><b>Hacia,</b></h3>
                                    <p class="text-muted m-l-30">
                                        <b>Apellido y Nombre / Razón Social: </b>Martín Sola<br/>
                                        <b>CUIT: </b>20425592379<br/>
                                        <b>Condición frente al IVA: </b>IVA Sujeto Exento<br/>
                                        <b>Condición de venta: </b>Cuenta Corriente<br/>
                                        <b>Domicilio: </b>Rivadavia 1097 - San Carlos Centro, Santa Fe</p>
                                    <hr>
                                    <p id="dateFromInvoice" class="m-t-30"><i class="fa fa-calendar"></i><b> Fecha desde : </b></p>
                                    <p id="dateToInvoice"><i class="fa fa-calendar"></i><b> Fecha hasta : </b></p>
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
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
                                        <tr>
                                            <td>Milk Powder</td>
                                            <td class="text-right">2</td>
                                            <td class="text-right">$24</td>
                                            <td class="text-right productTotal">$48</td>
                                        </tr>
                                        <tr>
                                            <td>Air Conditioner</td>
                                            <td class="text-right">3</td>
                                            <td class="text-right">$500</td>
                                            <td class="text-right productTotal">$1500</td>
                                        </tr>
                                        <tr>
                                            <td>RC Cars</td>
                                            <td class="text-right">20</td>
                                            <td class="text-right">$600</td>
                                            <td class="text-right productTotal">$12000</td>
                                        </tr>
                                        <tr>
                                            <td>Down Coat</td>
                                            <td class="text-right">60</td>
                                            <td class="text-right">$5</td>
                                            <td class="text-right productTotal">$300</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <p id="subtotalAmount">Subtotal: $13,848</p>
                                <p id="IVAAmount">IVA (21%) : $138</p>
                                <hr>
                                <h3 id="totalAmount"><b>Total: </b>$13,986</h3>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div class="text-right">
                                <button class="btn btn-danger" type="submit">Registrar factura</button>
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
        let subtotal = 0;
        $('#invoiceProducts .productTotal').each(function() {
            const valor = $(this).text().replace('$', '').trim();
            subtotal += parseFloat(valor);
        });
        $('#subtotalAmount').text("Subtotal: $" + formattedNumber(subtotal));
        $("#IVAAmount").html("IVA (21%) : $" + formattedNumber(subtotal*0.21))
        let iva = subtotal*0.21;
        $("#totalAmount").html("<b>Total: </b>$" + formattedNumber(subtotal+iva))
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
            $("#buttonContainer").css("display", "flex");
            $("#dateToInvoice").html(`<i class="fa fa-calendar"></i><b> Fecha hasta : </b>` + $(this).val())
        });
    </script>

@endsection