@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
@endphp

@extends('layouts.app')

@section('content')
<!-- Chart JS -->
<script src="{{ asset('plugins/Chart.js/chartjs.init.js') }}"></script>
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
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column no-block align-items-start">
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <h6 class="card-subtitle">{{ $today->format('Y') }}</h6>
                    </div>
                    <div>
                        <canvas id="barChart" height="150"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-md-6 m-b-30 m-t-20 text-center">
                            <h1 class="m-b-0 font-light">$54578</h1>
                            <h6 class="text-muted">Ganancias totales</h6>
                            <h1 class="m-b-0 font-light">ARRAY CANT {{ $cant[3]}}</h1>
                        </div>
                        <div class="col-md-6 m-b-30 m-t-20 text-center">
                            <h1 class="m-b-0 font-light">{{ $product->stock }}</h1>
                            <h6 class="text-muted">En stock</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
