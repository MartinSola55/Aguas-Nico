@extends('layouts.app')

@section('content')
    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Repartos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Repartos</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div>
                            <a class="btn btn-danger waves-effect waves-light" href="{{ url('/route/new') }}">
                                <i class="bi bi-plus-lg"></i>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Repartos</h4>
                            <div class="ml-auto">
                                <form method="GET" action="{{ url('/route/showRoutes') }}" id="formSearchRoutes" novalidate>
                                    <label for="datePicker" class="mb-0">Día</label>
                                    <input type="text" class="form-control" placeholder="dd/mm/aaaa" id="datePicker">
                                    <input type="hidden" name="start_daytime" id="start_daytime">
                                </form>
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
                                <tbody id="tableBody">
                                    @foreach ($routes as $route)
                                        <tr class="clickable" data-url="/route/details" data-id="{{ $route->id }}">
                                            <?php
                                                $names = explode(" ", $route->user->name);
                                                $initials = '';
                                                foreach ($names as $name) {
                                                    $initials .= strtoupper(substr($name, 0, 1));
                                                }
                                            ?>
                                            <td style="width:50px;"><span class="round">{{ $initials }}</span></td>
                                            <td>
                                                <h6>{{ $route->user->name }}</h6><small class="text-muted">Camión {{ $route->user->truck_number }}</small>
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
            makeHREF();
        });

        function makeHREF() {
            $('.clickable').click(function() {
                let url = $(this).data('url');
                let id = $(this).data('id');
                window.location.href = window.location.origin + url + "/" + id;
            });
        }
    </script>

    <script>
        moment.locale('es');
        $('#datePicker').bootstrapMaterialDatePicker({
            currentDate: new Date(),
            time: false,
            format: 'DD/MM/YYYY',
            cancelText: "Cancelar",
            weekStart: 1,
        });
    </script>

    <script>
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }

        function fillTable(routes) {
            let content = "";
            routes.forEach(route => {
                content += `<tr class="clickable" data-url="/route/details" data-id="` + route.id + `">`;
                content += '<td style="width:50px;"><span class="round">' + route.user.name.split(' ').map(word => word.charAt(0).toUpperCase()).join('') + '</span></td>';
                content += '<td>';
                if (route.user.truck_number !== null) {
                    content += '<h6>' + route.user.name + '</h6><small class="text-muted">Camión ' + route.user.truck_number + '</small>';
                } else {
                    content += '<h6>' + route.user.name + '</h6><small class="text-muted">Sin camión asignado</small>';
                }
                content += '</td>';
                content += '<td>' + route.info.completed_carts + '/' + route.info.total_carts + '</td>';
                if (route.info.state === "En depósito") {
                    content += '<td><span class="label label-danger">' + route.info.state + '</span></td>';
                } else if (route.info.state === "En reparto") {
                    content += '<td><span class="label label-warning">' + route.info.state + '</span></td>';
                } else {
                    content += '<td><span class="label label-success">' + route.info.state + '</span></td>';
                }
                content += '<td>$' + route.info.total_collected + '</td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);
            makeHREF();
        }

        $("#datePicker").on("change", function() {
            $("#start_daytime").val(formatDate($("#datePicker").val()));

            // Enviar solicitud AJAX
            $.ajax({
                url: $("#formSearchRoutes").attr('action'), // Utiliza la ruta del formulario
                method: $("#formSearchRoutes").attr('method'), // Utiliza el método del formulario
                data: $("#formSearchRoutes").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    fillTable(response.routes);
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.message,
                    });
                }
            });
        });
    </script>

@endsection