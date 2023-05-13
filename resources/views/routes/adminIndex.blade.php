@extends('layouts.app')

@section('content')
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex no-block">
                            <h4 class="card-title">Repartos</h4>
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
                                            <th>Envíos a realizar</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    $i = -1;
                                    ?>
                                    @foreach ($routes as $route)
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
                content += '<td>' + route.info.total_carts + '</td>';
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