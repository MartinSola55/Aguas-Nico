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
                <li class="breadcrumb-item"><a href="{{ url('/products/index') }}">Productos</a></li>
                <li class="breadcrumb-item active">Estadísticas</li>
            </ol>
        </div>
        <div class="col-md-7 col-4 align-self-center">
            <div class="d-flex m-t-10 justify-content-end">
                <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                    <div>
                    <a class="btn btn-primary waves-effect waves-light" href="{{ url('/products/edit') }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                    </div>
                </div>
            </div>
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
                        <h3 class="card-title">Botella de agua 1 litro</h3>
                        <h6 class="card-subtitle">2023</h6>
                    </div>
                    <div>
                        <canvas id="barChart" height="150"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-md-6 m-b-30 m-t-20 text-center">
                            <h1 class="m-b-0 font-light">$54578</h1>
                            <h6 class="text-muted">Ganancias totales</h6>
                        </div>
                        <div class="col-md-6 m-b-30 m-t-20 text-center">
                            <h1 class="m-b-0 font-light">893</h1>
                            <h6 class="text-muted">En stock</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection