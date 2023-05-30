@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
    
@endphp

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
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-currency-usd"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-light">${{ $data->day_collected }}</h3>
                                <h5 class="text-muted m-b-0">Recaudado en el día</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-danger"><i class="mdi mdi-shopping"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">${{ $data->day_expenses->sum('spent') }}</h3>
                                <h5 class="text-muted m-b-0">Gastos del día</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-success"><i class="mdi mdi-checkbox-marked-circle-outline"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $data->completed_routes }}</h3>
                                <h5 class="text-muted m-b-0">Repartos completados</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-clock-fast"></i></div>
                            <div class="m-l-10 align-self-center">
                                <h3 class="m-b-0 font-lgiht">{{ $data->pending_routes }}</h3>
                                <h5 class="text-muted m-b-0">Repartos en curso</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->

            
            <div class="col-lg-6 col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Productos vendidos</h4>
                        </div>
                        <h6 class="card-subtitle">{{ $today->format('d/m/Y') }}</h6>
                        <div class="table-responsive">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th style="width:90px;">Producto</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->products_sold as $item)    
                                    <tr>
                                        <td><span class="round"><i class="ti-shopping-cart"></i></span></td>
                                        <td>
                                            <h6>{{ $item->Product->name }}</h6><small class="text-muted">Precio: ${{ $item->Product->price }}</small>
                                        </td>
                                        <td>
                                            <h5>{{ $item->total_quantity }}</h5>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Gastos del día</h4>
                        </div>
                        <h6 class="card-subtitle">{{ $today->format('d/m/Y') }}</h6>
                        <div class="table-responsive">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th>Repartidor</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->day_expenses as $item)    
                                    <tr>
                                        <td>
                                            <h6>{{ $item->User->name }}</h6>
                                        </td>
                                        <td>
                                            <h6>{{ $item->description }}</h6>
                                        </td>
                                        <td>
                                            <h5>${{ $item->spent }}</h5>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Repartos de hoy</h4>
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
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($routes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            @php
                                            $i++;
                                            $names = explode(" ", $route->User->name);
                                            $initials = '';
                                            foreach ($names as $name) {
                                                $initials .= strtoupper(substr($name, 0, 1));
                                            }
                                            @endphp
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->User->truck_number !== null)
                                                <h6>{{ $route->User->name }}</h6><small class="text-muted">Camión {{ $route->User->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->User->name }}</h6><small class="text-muted">Sin camión asignado</small>
                                            @endif
                                            </td>
                                            <td>{{ $route->Info()['completed_carts'] }}/{{ $route->Info()['total_carts'] }}</td>
                                            @if ($route->Info()['state'] === "En depósito")
                                                <td><span class="label label-danger">{{ $route->Info()['state'] }}</span></td>
                                            @elseif ($route->Info()['state'] === "En reparto")
                                                <td><span class="label label-warning">{{ $route->Info()['state'] }}</span></td>
                                            @else
                                                <td><span class="label label-success">{{ $route->Info()['state'] }}</span></td>
                                            @endif
                                            <td>${{ $route->Info()['total_collected'] }}</td>
                                        </tr>
                                    @endforeach
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
                let url = $(this).data('url');
                let id = $(this).data('id');
                window.location.href = url + "/" + id;
            });
        });
    </script>
@endsection
