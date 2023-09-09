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
                <h3 class="text-themecolor m-b-0 m-t-0">Planillas</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Planillas</li>
                </ol>
            </div>
            @if (auth()->user()->rol_id == '1')
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div>
                            <a class="btn btn-info waves-effect waves-light" href="{{ url('/route/new') }}">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <form action="/route/createNew" method="POST" id="form_confirm">
                @csrf
                <input type="hidden" value="" name="id" id="route_id">
            </form>
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Planilla Lunes</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos a realizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($mondayRoutes as $route)
                                        <tr class="clickable" data-url="/route/createNew" data-id="{{ $route->id }}">
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
                                            <td>{{ $route->Info()['total_carts'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Planilla Martes</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos a realizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($tuesdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/createNew" data-id="{{ $route->id }}">
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
                                            <td>{{ $route->Info()['total_carts'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Planilla Miércoles</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos a realizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($wednesdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/createNew" data-id="{{ $route->id }}">
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
                                            <td>{{ $route->Info()['total_carts'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Planilla Jueves</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos a realizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($thursdayRoutes as $route)
                                        <tr class="clickable" data-url="/route/createNew" data-id="{{ $route->id }}">
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
                                            <td>{{ $route->Info()['total_carts'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Planilla Viernes</h4>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos a realizar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($fridayRoutes as $route)
                                        <tr class="clickable" data-url="/route/createNew" data-id="{{ $route->id }}">
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
                                            <td>{{ $route->Info()['total_carts'] }}</td>
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
            $('.clickable').on("click", function() {
                Swal.fire({
                    title: '¿Seguro deseas comenzar el reparto?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Comenzar',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-success waves-effect waves-light px-3 py-2',
                        cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                    }
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $("#route_id").val($(this).data('id'));
                        $.ajax({
                            url: $("#form_confirm").attr("action"),
                            method: $("#form_confirm").attr("method"),
                            data: $("#form_confirm").serialize(),
                            success: function(response) {
                                window.location.href = response.data;
                            },
                            error: function(errorThrown) {
                                Swal.fire({
                                    icon: 'error',
                                    title: errorThrown.responseJSON.message,
                                    confirmButtonColor: '#1e88e5',
                                });
                            }
                        });
                    }
                })
            });
        });
    </script>

@endsection
