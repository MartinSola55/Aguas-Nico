@extends('layouts.app')

@section('content')
    <!-- Morris CSS -->
    <link href="{{ asset('plugins/morrisjs/morris.css') }}" rel="stylesheet">

    <!--Morris JavaScript -->
    <script src="{{ asset('plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('plugins/morrisjs/morris.js') }}"></script>
    <!-- Datepicker -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Datepicker -->
    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

    <!-- Data table -->
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>


    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Repartidores</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/dealer/index') }}">Repartidores</a></li>
                    <li class="breadcrumb-item active">Detalles</li>
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
            <div class="col-12">
                @if ($dealer->truck_number)
                    <h2 class="text-center">{{ $dealer->name }} - Camión {{ $dealer->truck_number }}</h2>
                @else
                    <h2 class="text-center">{{ $dealer->name }} - Sin camión asignado</h2>
                @endif
                <hr />
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Clientes anuales</h4>
                        <div class="text-right"> <span class="text-muted">Completados</span>
                            <h1 class="font-light"><sup></sup>{{ $repartos['completados'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($repartos['completados'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1)) }}%</span>
                        <div class="progress">
                            <div class="progress-bar bg-success wow animated progress-animated" role="progressbar" style="width: {{ $repartos['completados'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Clientes anuales</h4>
                        <div class="text-right"> <span class="text-muted">Cancelados / pendientes</span>
                            <h1 class="font-light"><sup></sup>{{ $repartos['pendientes'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($repartos['pendientes'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1)) }}%</span>
                        <div class="progress">
                            <div class="progress-bar bg-danger wow animated progress-animated" role="progressbar" style="width: {{ $repartos['pendientes'] * 100 / ($repartos['totales'] !== 0 ? $repartos['totales'] : 1) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Producto más vendido</h4>
                        <div class="text-right"> <span class="text-muted">{{ $stats['product'] }}</span>
                            <h1 class="font-light"><sup></sup>{{ $stats['product_sales'] }}</h1>
                        </div>
                        <span class="text-dark">{{ round($stats['product_sales'] * 100 / ($stats['totalSold'] !== 0 ? $stats['totalSold'] : 1 )) }}% respecto del total</span>
                        <div class="progress">
                            <div class="progress-bar bg-dark wow animated progress-animated" role="progressbar" style="width: {{ round($stats['product_sales'] * 100 / ($stats['totalSold'] !== 0 ? $stats['totalSold'] : 1 )) }}%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Total recaudado en el mes</h4>
                        <div class="text-right"> <span class="text-muted" id="monthName"></span>
                            <h1 class="font-light"><sup></sup>${{ number_format($stats["totalCollected"], 0, ",", ".") }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Ventas anuales</h4>
                        <ul class="list-inline text-right">
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-inverse"></i>{{ $dealer->name }}</h5>
                            </li>
                        </ul>
                        <div id="morris-area-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Ventas mensuales</h4>
                        <ul class="list-inline text-center m-t-40">
                            <li>
                                <h5><i class="fa fa-circle m-r-5 text-dark"></i>{{ $dealer->name }}</h5>
                            </li>
                        </ul>
                        <div id="extra-area-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-start align-items-center">
                            <h4 class="card-title mr-2 mb-2">Clientes del día:</h4>
                            <select id="clientsDay" class="form-control mb-2" style="max-width: fit-content">
                                <option value="" selected disabled>Seleccione un día</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                            </select>
                        </div>
                        <hr>
                        <table id="clientsTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Deuda</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title mr-2 mb-2">Repartos pendientes</h4>
                        <hr>
                        <div class="d-flex flex-column flex-sm-row justify-content-start align-items-center">
                            <div class="form-group">
                                <label for="dateFromPendingCarts">Fecha desde</label>
                                <input id="dateFromPendingCarts" type="text" class="form-control" placeholder="dd/mm/aaaa">
                            </div>
                            <div class="form-group">
                                <label for="dateToPendingCarts">Fecha hasta</label>
                                <input id="dateToPendingCarts" type="text" class="form-control" placeholder="dd/mm/aaaa">
                            </div>
                            <button id="btnSearchPendingCarts" class="btn btn-info">Buscar</button>
                        </div>
                        <div class="d-flex justify-content-start align-items-center">
                            <select class="form-control w-50" id="estadoSelect" style="display: none;">
                                <option value="">Filtrar por estado</option>
                                <option value="No estaba">No estaba</option>
                                <option value="No necesitaba">No necesitaba</option>
                              </select>
                        </div>
                        <table id="pendingCartsTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Día</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row justify-content-start align-items-center">
                            <h4 class="card-title mr-2 mb-2">Clientes a los que no se les bajó máquinas</h4>
                            <select id="clientsMaqMonth" class="form-control mb-2" style="max-width: fit-content">
                                <option value="" selected disabled>Seleccione un mes</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <select id="clientsMaqYear" class="form-control mb-2" style="max-width: fit-content">
                                <option value="" selected disabled>Seleccione un año</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <table id="maquinasTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-sm-row justify-content-start align-items-center">
                            <h4 class="card-title mr-2 mb-2">Productos vendidos</h4>
                            <select id="productsSoldMonth" class="form-control mb-2" style="max-width: fit-content">
                                <option value="" selected disabled>Seleccione un mes</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                            <select id="productsSoldYear" class="form-control mb-2" style="max-width: fit-content">
                                <option value="" selected disabled>Seleccione un año</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <table id="productsSoldTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ url('/dealer/getPendingCarts') }}" id="form-pendingCarts" class="form-material m-t-30">
            @csrf
            <input type="hidden" name="dateFrom" value="">
            <input type="hidden" name="dateTo" value="">
            <input type="hidden" name="id" value="{{ $dealer->id }}">
        </form>

        <form method="GET" action="{{ url('/dealer/searchClients') }}" id="form-searchClients" class="form-material m-t-30">
            @csrf
            <input type="hidden" name="day_of_week" value="">
            <input type="hidden" name="id" value="{{ $dealer->id }}">
        </form>

        <form method="GET" action="{{ url('/dealer/searchClientsMachines') }}" id="form-searchClientsMachines" class="form-material m-t-30">
            @csrf
            <input type="hidden" name="month" value="">
            <input type="hidden" name="year" value="">
            <input type="hidden" name="id" value="{{ $dealer->id }}">
        </form>

        <form method="GET" action="{{ url('/dealer/searchProductsSold') }}" id="form-searchProductsSold" class="form-material m-t-30">
            @csrf
            <input type="hidden" name="month" value="">
            <input type="hidden" name="year" value="">
            <input type="hidden" name="id" value="{{ $dealer->id }}">
        </form>
    </div>

    {{-- Constantes --}}
    <script>
        function createLocalDate(date) {
            return new Date(date).toLocaleString("es-AR", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
            });
        }
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }
        const states = ['Pendiente', 'Completado', 'No estaba', 'No necesitaba', 'De vacaciones'];
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    </script>

    {{-- Repartos pendientes --}}
    <script>
        $("#pendingCartsTable").DataTable({
            "info": false,
            "scrollY": '30vh',
            "scrollCollapse": true,
            "paging": false,
            "ordering": false,
            "language": {
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ repartos",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 repartos",
                "sInfoFiltered": "(filtrado de _MAX_ repartos en total)",
                "emptyTable": 'No hay repartos que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ repartos",
                "sSearch": "Buscar:",
            },
        });
        $('#dateFromPendingCarts').bootstrapMaterialDatePicker({
            maxDate: new Date(),
            time: false,
            format: 'DD/MM/YYYY',
            cancelText: "Cancelar",
            weekStart: 1,
            lang: 'es',
        });
        $("#dateFromPendingCarts").on("change", function() {
            $('#dateToPendingCarts').bootstrapMaterialDatePicker({
                minDate: $("#dateFromPendingCarts").val(),
                maxDate: new Date(),
                time: false,
                format: 'DD/MM/YYYY',
                cancelText: "Cancelar",
                weekStart: 1,
                lang: 'es',
            });
        });
        $("#btnSearchPendingCarts").on("click", function() {
            if ($("#dateFromPendingCarts").val() === "" || $("#dateToPendingCarts").val() === "") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Debe seleccionar un rango de fechas',
                    confirmButtonColor: '#1e88e5',
                });
                return;
            }
            $("#form-pendingCarts input[name='dateFrom']").val(formatDate($("#dateFromPendingCarts").val()));
            $("#form-pendingCarts input[name='dateTo']").val(formatDate($("#dateToPendingCarts").val()));
            $("#pendingCartsTable").DataTable().clear().draw();
            sendForm("pendingCarts");

            $('#estadoSelect').show();
            $('#estadoSelect').on('change', function() {
                let estadoFiltrar = $(this).val();

                $('#pendingCartsTable tbody tr').show(); // Mostrar todos los elementos

                if (estadoFiltrar) {
                $('#pendingCartsTable tbody tr').filter(function() {
                    return $('td:eq(3)', this).text() !== estadoFiltrar;
                }).hide();
                }
            });
        });

        function fillPendingCartsTable(data) {
            let table = $("#pendingCartsTable");
            data.forEach(function (item) {
                let row = table.DataTable().row.add([
                    item.client.name,
                    dias[item.route.day_of_week],
                    createLocalDate(item.created_at),
                    states[item.state],
                ]).draw().node();
            });
        }
    </script>

    {{-- Clientes de un día --}}
    <script>
        $("#clientsTable").DataTable({
            "info": false,
            "scrollY": '30vh',
            "scrollCollapse": true,
            "paging": false,
            "ordering": false,
            "language": {
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                "sInfoFiltered": "(filtrado de _MAX_ clientes en total)",
                "emptyTable": 'No hay clientes que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ clientes",
                "sSearch": "Buscar:",
            },
        });
        $("#clientsDay").on("change", function() {
            $("#form-searchClients input[name='day_of_week']").val($(this).val());
            $("#clientsTable").DataTable().clear().draw();
            sendForm("searchClients");
        });

        function fillClientsTable(data) {
            let table = $("#clientsTable");
            data.carts.forEach(function (item) {
                let client = item.client;
                let debt = "Sin deuda";
                if (client.debt > 0) {
                    debt = `$${client.debt}`;
                } else if (client.debt < 0){
                    debt = `A favor: $${client.debt * -1}`;
                }
                let row = table.DataTable().row.add([
                    client.name,
                    debt,
                ]).draw().node();
            });
        }
    </script>

    {{-- Clientes que no se les bajó máquinas --}}
    <script>
        $("#maquinasTable").DataTable({
            "info": false,
            "scrollY": '30vh',
            "scrollCollapse": true,
            "paging": false,
            "ordering": false,
            "language": {
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                "sInfoFiltered": "(filtrado de _MAX_ clientes en total)",
                "emptyTable": 'No hay clientes que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ clientes",
                "sSearch": "Buscar:",
            },
        });
        $("#clientsMaqMonth").on("change", function() {
            if ($("#clientsMaqYear").val() == "" || $("#clientsMaqYear").val() == null) {
                return;
            }
            $("#form-searchClientsMachines input[name='month']").val($(this).val());
            $("#form-searchClientsMachines input[name='year']").val($("#clientsMaqYear").val());
            $("#maquinasTable").DataTable().clear().draw();
            sendForm("searchClientsMachines");
        });
        $("#clientsMaqYear").on("change", function() {
            if ($("#clientsMaqMonth").val() === "" || $("#clientsMaqMonth").val() === null) {
                return;
            }
            $("#form-searchClientsMachines input[name='month']").val($("#clientsMaqMonth").val());
            $("#form-searchClientsMachines input[name='year']").val($(this).val());
            $("#maquinasTable").DataTable().clear().draw();
            sendForm("searchClientsMachines");
        });

        function fillClientsMachinesTable(data) {
            let table = $("#maquinasTable");
            data.forEach(function (item) {
                let row = table.DataTable().row.add([
                    item.name,
                ]).draw().node();
            });
        }
    </script>

    {{-- Productos vendidos --}}
    <script>
        $("#productsSoldTable").DataTable({
            "info": false,
            "searching": false,
            "paging": false,
            "ordering": false,
            "language": {
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 productos",
                "sInfoFiltered": "(filtrado de _MAX_ productos en total)",
                "emptyTable": 'No hay productos que coincidan con la búsqueda',
                "sLengthMenu": "Mostrar _MENU_ productos",
            },
        });
        $("#productsSoldMonth").on("change", function() {
            if ($("#productsSoldYear").val() == "" || $("#productsSoldYear").val() == null) {
                return;
            }
            $("#form-searchProductsSold input[name='month']").val($(this).val());
            $("#form-searchProductsSold input[name='year']").val($("#productsSoldYear").val());
            $("#productsSoldTable").DataTable().clear().draw();
            sendForm("searchProductsSold");
        });
        $("#productsSoldYear").on("change", function() {
            if ($("#productsSoldMonth").val() === "" || $("#productsSoldMonth").val() === null) {
                return;
            }
            $("#form-searchProductsSold input[name='month']").val($("#productsSoldMonth").val());
            $("#form-searchProductsSold input[name='year']").val($(this).val());
            $("#productsSoldTable").DataTable().clear().draw();
            sendForm("searchProductsSold");
        });

        function fillProductsSoldTable(data) {
            let table = $("#productsSoldTable");
            let keys = Object.keys(data);
            for (let i = 0; i < keys.length; i++) {
                let key = keys[i];
                let item = data[key];
                let row = table.DataTable().row.add([
                    item.product,
                    item.quantity,
                ]).draw().node();
            }
        }
    </script>

    <script>
        function sendForm(action) {
            let form = document.getElementById(`form-${action}`);

            // Enviar solicitud AJAX
            $.ajax({
                url: $(form).attr('action'), // Utiliza la ruta del formulario
                method: $(form).attr('method'), // Utiliza el método del formulario
                data: $(form).serialize(), // Utiliza los datos del formulario
                success: function (response) {
                    if (action === 'pendingCarts') {
                        fillPendingCartsTable(response.data);
                    } else if (action === 'searchClients') {
                        fillClientsTable(response.data);
                    } else if (action === 'searchClientsMachines' && response.data != null) {
                        fillClientsMachinesTable(response.data);
                    } else if (action === 'searchProductsSold' && response.data != null) {
                        fillProductsSoldTable(response.data);
                    }
                },
                error: function (errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.title,
                        text: errorThrown.responseJSON.message,
                        confirmButtonColor: '#1e88e5',
                    });
                }
            });
        };
    </script>

    {{-- Ventas anuales --}}
    <script>
        let anualSales = {!! json_encode($anualSales) !!};
        let anualJson = JSON.parse(anualSales);
        let anualData = anualJson.data;

        Morris.Area({
                element: 'morris-area-chart',
                data: anualData,
                xkey: 'period',
                ykeys: ['sold'],
                labels: ['$'],
                pointSize: 3,
                fillOpacity: 0,
                pointStrokeColors:['#2f3d4a'],
                behaveLikeLine: true,
                gridLineColor: '#009efb',
                lineWidth: 3,
                hideHover: 'auto',
                lineColors: ['#2f3d4a'],
                resize: true,
            });
    </script>

    {{-- Ventas mensuales --}}
    <script>
        let monthlySales = {!! json_encode($monthlySales) !!};
        let monthJson = JSON.parse(monthlySales);
        let monthdata = monthJson.data;

        Morris.Area({
                element: 'extra-area-chart',
                data: monthdata,
                lineColors: ['#2f3d4a'],
                xkey: 'period',
                ykeys: ['sold'],
                labels: ['$'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'
            });
    </script>

    <script src="{{ asset('plugins/moment/moment-with-locales.js') }}"></script>
    <script>
        let month = moment().locale('es').format('MMMM');
        $("#monthName").text(month);
    </script>
@endsection
