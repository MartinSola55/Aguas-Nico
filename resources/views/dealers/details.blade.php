@extends('layouts.app')

@section('content')
    <!-- Morris CSS -->
    <link href="{{ asset('plugins/morrisjs/morris.css') }}" rel="stylesheet">

    <!--Morris JavaScript -->
    <script src="{{ asset('plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('js/morris-data.js') }}"></script>


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
                            <h1 class="font-light"><sup></sup>784</h1>
                        </div>
                        <span class="text-dark">95%</span>
                        <div class="progress">
                            <div class="progress-bar bg-success wow animated progress-animated" role="progressbar" style="width: 95%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Repartos anuales</h4>
                        <div class="text-right"> <span class="text-muted">Cancelados</span>
                            <h1 class="font-light"><sup></sup>38</h1>
                        </div>
                        <span class="text-dark">5%</span>
                        <div class="progress">
                            <div class="progress-bar bg-danger wow animated progress-animated" role="progressbar" style="width: 5%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Producto más vendido</h4>
                        <div class="text-right"> <span class="text-muted">Bidón de agua 15L</span>
                            <h1 class="font-light"><sup></sup>3597</h1>
                        </div>
                        <span class="text-dark">68% respecto del total</span>
                        <div class="progress">
                            <div class="progress-bar bg-dark wow animated progress-animated" role="progressbar" style="width: 68%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
@endsection
