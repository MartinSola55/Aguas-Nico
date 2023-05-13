@extends('layouts.app')

@section('content')
    <!-- Morris CSS -->
    <link href="{{ asset('plugins/morrisjs/morris.css') }}" rel="stylesheet">

    <!--Morris JavaScript -->
    <script src="{{ asset('plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/morrisjs/morris.js') }}"></script>
    {{-- <script src="{{ asset('js/morris-data.js') }}"></script> --}}


    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Repartidores</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/dealer/index') }}">Repartidores</a></li>
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
            <div class="col-12">
                @if ($dealer->truck_number)
                    <h2 class="text-center">{{ $dealer->name }} - Camión {{ $dealer->truck_number }}</h2>
                @else
                    <h2 class="text-center">{{ $dealer->name }} - Sin camión asignado</h2>
                @endif
                <hr />
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Repartos anuales</h4>
                        <div class="text-right"> <span class="text-muted">Completados</span>
                            <h1 class="font-light"><sup></sup>{{ $repartos['completados'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($repartos['completados'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1)) }}%</span>
                        <div class="progress">
                            <div class="progress-bar bg-success wow animated progress-animated" role="progressbar" style="width: {{ $repartos['completados'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Repartos anuales</h4>
                        <div class="text-right"> <span class="text-muted">Cancelados / pendientes</span>
                            <h1 class="font-light"><sup></sup>{{ $repartos['pendientes'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($repartos['pendientes'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1)) }}%</span>
                        <div class="progress">
                            <div class="progress-bar bg-danger wow animated progress-animated" role="progressbar" style="width: {{ $repartos['pendientes'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Producto más vendido</h4>
                        <div class="text-right"> <span class="text-muted">{{ $stats['product'] }}</span>
                            <h1 class="font-light"><sup></sup>{{ $stats['product_sales'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($stats['product_sales'] * 100 / ($stats['totalSold'] !== 0 ? $stats['totalSold'] : 1 )) }}% respecto del total</span>
                        <div class="progress">
                            <div class="progress-bar bg-dark wow animated progress-animated" role="progressbar" style="width: {{ round($stats['product_sales'] * 100 / ($stats['totalSold'] !== 0 ? $stats['totalSold'] : 1 )) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ganancias anuales</h4>
                        <ul class="list-inline text-right">
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-inverse"></i>{{ $dealer->name }}</h5>
                            </li>
                        </ul>
                        <div id="morris-area-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ganancias mensuales</h4>
                        <ul class="list-inline text-center m-t-40">
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-dark"></i>{{ $dealer->name }}</h5>
                            </li>
                        </ul>
                        <div id="extra-area-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ganancias anuales --}}
    <script>
        let anualSales = {!! json_encode($anualSales) !!};
        let anualJson = JSON.parse(anualSales);
        let anualData = anualJson.data;

        Morris.Area({
                element: 'morris-area-chart',
                data: anualData,
                xkey: 'period',
                ykeys: ['sold'],
                labels: ['$'],
                pointSize: 3,
                fillOpacity: 0,
                pointStrokeColors:['#2f3d4a'],
                behaveLikeLine: true,
                gridLineColor: '#009efb',
                lineWidth: 3,
                hideHover: 'auto',
                lineColors: ['#2f3d4a'],
                resize: true,
            });
    </script>

    {{-- Ganancias mensuales --}}
    <script>
        let monthlySales = {!! json_encode($monthlySales) !!};
        let monthJson = JSON.parse(monthlySales);
        let monthdata = monthJson.data;

        Morris.Area({
                element: 'extra-area-chart',
                data: monthdata,
                lineColors: ['#2f3d4a'],
                xkey: 'period',
                ykeys: ['sold'],
                labels: ['$'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
            });   
    </script>
@endsection
