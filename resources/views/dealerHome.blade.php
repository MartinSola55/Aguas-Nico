@extends('layouts.app')

@section('content')
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
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
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Planillas</h4>
                            <div class="ml-auto">
                                <form method="GET" action="{{ url('/route/showRoutes') }}" id="formSearchRoutes" novalidate>
                                    <label for="day_of_week" class="mb-0">Día</label>
                                    <select name="day_of_week" class="form-control" id="day_of_week">
                                        <option disabled>Seleccione un día</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive m-t-20">
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Nombre</th>
                                        @if (auth()->user()->rol_id == '1')
                                            <th>Envíos a realizar</th>
                                        @else
                                            <th>Envíos completados</th>
                                            <th>Estado</th>
                                            <th>Recaudado</th>
                                            <th>Fecha</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
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
                                        <td>{{ date('d/m/Y', strtotime($route->start_date)) }}</td>
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
        let dayOfWeek = (new Date()).getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6)
            $("#day_of_week").val($('#day_of_week option:first').val());
        else
            $("#day_of_week").val(dayOfWeek);
    </script>

    <script>
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
                content += '<td>' + route.info.completed_carts + "/" + route.info.total_carts + '</td>';
                if (route.info.state === "En depósito")
                    content += '<td><span class="label label-danger">' + route.info.state + '</span></td>';
                else if (route.info.state === "En reparto")
                    content += '<td><span class="label label-warning">' + route.info.state + '</span></td>';
                else
                    content += '<td><span class="label label-success">' + route.info.state + '</span></td>';
                content += '<td>$' + route.info.total_collected + '</td>';
                content += '<td>' + moment(route.start_date).format('DD/MM/YYYY') + '</td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);
            makeHREF();
        };

        $("#day_of_week").on("change", function() {
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
                        title: errorThrown.responseJSON.title,
                        text: errorThrown.responseJSON.message,
                        confirmButtonColor: '#1e88e5',
                    });
                }
            });
        });
    </script>

@endsection
