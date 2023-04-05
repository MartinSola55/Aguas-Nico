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
                                <label for="datePicker" class="mb-0">Día</label>
                                <input type="text" class="form-control" placeholder="dd/mm/aaaa" id="datePicker" name="date">
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

@endsection