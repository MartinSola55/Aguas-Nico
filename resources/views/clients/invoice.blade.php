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
                    <h3><b>FACTURA</b> <span class="pull-right">#5669626</span></h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <address>
                                    <h3> &nbsp;<b class="text-danger">Aguas Nico</b></h3>
                                    <p class="text-muted m-l-5">Lorenza Aguilera 415
                                        <br/>Neuquén, Neuquén
                                        <br/>CP: 8300
                                        <br/>(0299) 4467078 / 4450365</p>
                                </address>
                            </div>
                            <div class="pull-right text-right">
                                <address>
                                    <h3>Hacia,</h3>
                                    <h4 class="font-bold">Martín Sola,</h4>
                                    <p class="text-muted m-l-30">Rivadavia 1097
                                        <br/>San Carlos Centro, Santa Fe
                                        <br/>CP: 3013</p>
                                    <p class="m-t-30"><i class="fa fa-calendar"></i><b> Fecha desde :</b> 01 de Mayo de 2023</p>
                                    <p><i class="fa fa-calendar"></i><b> Fecha hasta :</b> 03 de Abril de 2023</p>
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Description</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">Unit Cost</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>Milk Powder</td>
                                            <td class="text-right">2 </td>
                                            <td class="text-right"> $24 </td>
                                            <td class="text-right"> $48 </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td>Air Conditioner</td>
                                            <td class="text-right"> 3 </td>
                                            <td class="text-right"> $500 </td>
                                            <td class="text-right"> $1500 </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td>RC Cars</td>
                                            <td class="text-right"> 20 </td>
                                            <td class="text-right"> %600 </td>
                                            <td class="text-right"> $12000 </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td>Down Coat</td>
                                            <td class="text-right"> 60 </td>
                                            <td class="text-right">$5 </td>
                                            <td class="text-right"> $300 </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <p>Subtotal: $13,848</p>
                                <p>IVA (10%) : $138 </p>
                                <hr>
                                <h3><b>Total :</b> $13,986</h3>
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
        });
        $("#dateTo").on("change", function() {
            $("#buttonContainer").css("display", "flex");
        });
    </script>

@endsection