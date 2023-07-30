@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
@endphp
@php
    $diasSemana = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
    ];
@endphp
@extends('layouts.app')

@section('content')
    <link href="{{ asset('plugins/css-chart/css-chart.css') }}" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="{{ asset('plugins/c3-master/c3.min.css') }}" rel="stylesheet">
    <!-- Vector CSS -->
    <link href="{{ asset('plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
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
            <div class="col-xl-3 col-md-6">
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
            <div class="col-xl-3 col-md-6">
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
            <div class="col-xl-3 col-md-6">
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
            <div class="col-xl-3 col-md-6">
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
                                        <th style="width:10%;"></th>
                                        <th>Producto/Envase</th>
                                        <th>Cargados</th>
                                        <th>Vendidos</th>
                                        <th>Devueltos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->items as $item)
                                        <tr>
                                            <td><span class="round"><i class="ti-shopping-cart"></i></span></td>
                                            <td>
                                                <h6>{{ $item['name'] }}</h6>
                                            </td>
                                            <td>
                                                <h6>{{ $item['dispatch'] }}</h6>
                                            </td>
                                            <td>
                                                <h5>{{ $item['sold'] }}</h5>
                                            </td>
                                            <td>
                                                <h5>{{ $item['returned'] }}</h5>
                                            </td>
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
                        <h3 class="card-title">Repartos del día</h3>
                        <div class="d-flex no-block">
                            <div class="mr-auto">
                                <form method="GET" action="{{ url('/home/searchRoutes') }}" id="formSearchRoutes" novalidate>
                                    <label for="DatePicker" class="mb-0">Día</label>
                                    <input type="text" class="form-control" id="DatePicker" name="date" value="{{ $today->format('d/m/Y') }}" />
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table id="routesTable" class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        <th>Envíos completados</th>
                                        <th>Estado</th>
                                        <th>Recaudado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($routes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            @php
                                            $names = explode(" ", $route->User->name);
                                            $initials = '';
                                            foreach ($names as $name) {
                                                $initials .= strtoupper(substr($name, 0, 1));
                                            }
                                            @endphp
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                            <h6>{{ $route->User->name }}</h6><small class="text-muted">Planilla {{ $diasSemana[$route->day_of_week] }}</small>
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
        const diasSemana = ["", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
    </script>

    <script>
        moment.locale('es');
        $(document).ready(function() {
            $(document).on("click", ".clickable", function () {
                let url = $(this).data('url');
                let id = $(this).data('id');
                window.location.href = url + "/" + id;
            });

            $('#DatePicker').bootstrapMaterialDatePicker({
                maxDate: new Date(),
                time: false,
                format: 'DD/MM/YYYY',
                cancelText: "Cancelar",
                weekStart: 1,
                lang: 'es',
            });
        });

        $("#DatePicker").on("change", function() {
            $.ajax({
                url: $("#formSearchRoutes").attr('action'), // Utiliza la ruta del formulario
                method: $("#formSearchRoutes").attr('method'), // Utiliza el método del formulario
                data: $("#formSearchRoutes").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    fillTable(response.data);
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.title,
                        text: errorThrown.responseJSON.message,
                        confirmButtonColor: '#1e88e5',
                    });
                }
            });
        });

        function fillTable(routes) {
            let content = "";
            routes.forEach(route => {
                let initials = "";
                let names = route.user.name.split(" ");
                names.forEach(name => {
                    initials += name.substr(0, 1).toUpperCase();
                });
                let state = "";
                if (route.info.state === "En depósito") state = `<span class="label label-danger">${route.info.state}</span>`;
                else if (route.info.state === "En reparto") state = `<span class="label label-warning">${route.info.state}</span>`;
                else state = `<span class="label label-success">${route.info.state}</span>`;

                content += `
                    <tr class="clickable" data-url="/route/details" data-id="${route.id}">
                        <td style="width:50px;"><span class="round">${initials}</span></td>
                        <td>
                            <h6>${route.user.name}</h6><small class="text-muted">Planilla ${diasSemana[route.day_of_week]}</small>
                        </td>
                        <td>${route.info.completed_carts}/${route.info.total_carts}</td>
                        <td>${state}</td>
                        <td>$${route.info.total_collected}</td>
                    </tr>
                `;
            });
            $("#routesTable tbody").html(content);
        }
    </script>
@endsection
