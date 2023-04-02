@extends('layouts.app')

@section('content')
<link href="{{ asset('plugins/css-chart/css-chart.css') }}" rel="stylesheet">
<!--This page css - Morris CSS -->
<link href="{{ asset('plugins/c3-master/c3.min.css') }}" rel="stylesheet">
<!-- Vector CSS -->
<link href="{{ asset('plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">

<!--c3 JavaScript -->
<script src="{{ asset('plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('plugins/c3-master/c3.min.js') }}"></script>
<!-- Vector map JavaScript -->
<script src="{{ asset('plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('plugins/vectormap/jquery-jvectormap-us-aea-en.js') }}"></script>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Inicio</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Inicio</li>
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
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-info"><i class="ti-wallet"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-light">$3249</h3>
                            <h5 class="text-muted m-b-0">Ganancias del día</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-cellphone-link"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">2</h3>
                            <h5 class="text-muted m-b-0">Repartos completados</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-cart-outline"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">4</h3>
                            <h5 class="text-muted m-b-0">Repartos en curso</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="round round-lg align-self-center round-danger"><i class="mdi mdi-bullseye"></i></div>
                        <div class="m-l-10 align-self-center">
                            <h3 class="m-b-0 font-lgiht">1</h3>
                            <h5 class="text-muted m-b-0">Algo más</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex no-block">
                        <h4 class="card-title">Repartidores</h4>
                        <div class="ml-auto">
                            <select class="custom-select">
                                <option selected="" value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive m-t-20">
                        <table class="table stylish-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Nombre</th>
                                    <th>Envíos completados</th>
                                    <th>Estado</th>
                                    <th>Recaudado</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr class="clickable" data-url="{{ route('/routes/details', ['id' => $user->id]) }}"> --}}
                                <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                    <td style="width:50px;"><span class="round">JP</span></td>
                                    <td>
                                        <h6>Juan Pérez</h6><small class="text-muted">Camión 1</small>
                                    </td>
                                    <td>4/6</td>
                                    <td><span class="label label-danger">En reparto</span></td>
                                    <td>$3.9K</td>
                                </tr>
                                {{-- <tr class="clickable" data-url="{{ route('/routes/details', ['id' => $user->id]) }}" /*class="active"*/> --}}
                                    <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                        <td><span class="round">MS</span></td>
                                    <td>
                                        <h6>Martín Sola</h6><small class="text-muted">Camión 2</small>
                                    </td>
                                    <td>5/5</td>
                                    <td><span class="label label-primary">Completado</span></td>
                                    <td>$23.9K</td>
                                </tr>
                                <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                    <td><span class="round round-success">PB</span></td>
                                    <td>
                                        <h6>Peter Bettig</h6><small class="text-muted">Camión 3</small>
                                    </td>
                                    <td>6/7</td>
                                    <td><span class="label label-danger">En reparto</span></td>
                                    <td>$12.9K</td>
                                </tr>
                                <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                    <td><span class="round round-primary">SL</span></td>
                                    <td>
                                        <h6>Samuelson Leiva</h6><small class="text-muted">Camión 4</small>
                                    </td>
                                    <td>1/8</td>
                                    <td><span class="label label-danger">En reparto</span></td>
                                    <td>$10.9K</td>
                                </tr>
                                <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                    <td><span class="round round-warning">NB</span></td>
                                    <td>
                                        <h6>Nachito Bettig</h6><small class="text-muted">Camión 5</small>
                                    </td>
                                    <td>8/8</td>
                                    <td><span class="label label-primary">Completado</span></td>
                                    <td>$12.9K</td>
                                </tr>
                                <tr class="clickable" data-url="{{ url('/routes/details') }}">
                                    <td><span class="round round-danger">J</span></td>
                                    <td>
                                        <h6>Johny</h6><small class="text-muted">Camión 6</small>
                                    </td>
                                    <td>0/5</td>
                                    <td><span class="label label-warning">En galpón</span></td>
                                    <td>$0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .clickable {
        cursor: pointer;
    }
</style>

<script>
    $(document).ready(function() {
        $('.clickable').click(function() {
            var url = $(this).data('url');
            window.location.href = url;
        });
    });
</script>
@endsection