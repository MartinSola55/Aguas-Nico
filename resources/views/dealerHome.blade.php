@php
    $mondayRoutes = [];
    $tuesdayRoutes = [];
    $wednesdayRoutes = [];
    $thursdayRoutes = [];
    $fridayRoutes = [];

    foreach($routes as $route) {
        switch($route->day_of_week) {
            case 1:
                array_push($mondayRoutes, $route);
                break;
            case 2:
                array_push($tuesdayRoutes, $route);
                break;
            case 3:
                array_push($wednesdayRoutes, $route);
                break;
            case 4:
                array_push($thursdayRoutes, $route);
                break;
            case 5:
                array_push($fridayRoutes, $route);
                break;
        }
    }
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reparto Lunes</h4>
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
                                    @foreach ($mondayRoutes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                            $i++;
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->user->truck_number !== null)
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Sin camión asignado</small>
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reparto Martes</h4>
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
                                    @foreach ($tuesdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                            $i++;
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->user->truck_number !== null)
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Sin camión asignado</small>
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reparto Miércoles</h4>
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
                                    @foreach ($wednesdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                            $i++;
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->user->truck_number !== null)
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Sin camión asignado</small>
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reparto Jueves</h4>
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
                                    @foreach ($thursdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                            $i++;
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->user->truck_number !== null)
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Sin camión asignado</small>
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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reparto Viernes</h4>
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
                                    @foreach ($fridayRoutes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                            $i++;
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            @if ($route->user->truck_number !== null)
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
                                            @else
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Sin camión asignado</small>
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