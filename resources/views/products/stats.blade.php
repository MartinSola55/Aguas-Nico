@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
@endphp

@extends('layouts.app')

@section('content')
    <!-- Chart JS -->
    <script src="{{ asset('plugins/Chart.js/Chart.min.js') }}"></script>

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Productos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/product/index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Estadísticas</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-column no-block align-items-start">
                            <h3 class="card-title">{{ $product->name }}</h3>
                            <h6 class="card-subtitle">{{ $today->format('Y') }}</h6>
                        </div>
                        <div>
                            <canvas id="barChart" height="160"></canvas>
                        </div>
                        <div class="row">
                            <div class="col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">${{ $total_earnings }}</h1>
                                <h6 class="text-muted">Ventas totales</h6>
                            </div>
                            <div class="col-md-6 m-b-30 m-t-20 text-center">
                                <h1 class="m-b-0 font-light">{{ $total_in_street }} u.</h1>
                                <h6 class="text-muted">En la calle</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
        /*<!-- ============================================================== -->*/
        /*<!-- Bar Chart -->*/
        /*<!-- ============================================================== -->*/
        new Chart(document.getElementById("barChart"),
            {
                "type":"bar",
                "data":{"labels":["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                "datasets":[{
                                "label":"Ventas del mes",
                                "data":{{ $graph }},
                                "fill":false,
                                "backgroundColor":["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                                "borderColor":["rgb(252, 75, 108)","rgb(255, 159, 64)","rgb(255, 178, 43)","rgb(38, 198, 218)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)","rgb(255, 159, 64)","rgb(255, 178, 43)","rgb(38, 198, 218)","rgb(54, 162, 235)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                                "borderWidth":1}
                            ]},
                "options":{
                    "scales":{"yAxes":[{"ticks":{"beginAtZero":true}}]}
                }
            });
    });
    </script>

@endsection
