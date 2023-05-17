@extends('layouts.app')

@section('content')
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
                <h3 class="text-themecolor m-b-0 m-t-0">Gastos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Gastos</li>
                </ol>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->

        <!-- Modal -->
        <div id="modalConfirmation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/expense/create') }}" id="form-create" autocomplete="off" novalidate>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Agregar gasto</h4>
                            <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-column">
                                        {{-- TOKEN --}}
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        
                                        <div class="col-12 mb-3">
                                            <label for="expDescription" class="mb-0">Descripción</label>
                                            <input type="text" class="form-control" id="expDescription" name="description" required>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese una descripción
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="expSpent" class="mb-0">Gasto</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="expSpent" name="spent" placearia-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Por favor, ingrese un monto
                                                </div>
                                            </div>
                                        </div>
                                        @if (auth()->user()->rol_id == '1')    
                                            <div class="col-12 mb-3">
                                                <label for="expUser" class="mb-0">Repartidor</label>
                                                <select name="user_id" class="form-control" id="expUser">
                                                    <option disabled selected value="">Seleccione un repartidor</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success waves-effect waves-light">Agregar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
        <div class="row">
            <div id="datesContainer" class="col-xlg-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Intervalo de gastos</h4>
                        <form method="GET" action="{{ url('/expense/searchExpenses') }}" id="form-expense" class="form-material m-t-30">
                            @csrf
                            <input type="hidden" name="dateFrom" id="dateFromFormatted" value="">
                            <input type="hidden" name="dateTo" id="dateToFormatted" value="">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="dateFrom">Fecha inicio</label>
                                    <input id="dateFrom" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                </div>
                                <div id="dateToContainer" class="form-group col-lg-6" style="display: none">
                                    <label for="dateTo">Fecha fin</label>
                                    <input id="dateTo" type="text" class="form-control" placeholder="dd/mm/aaaa">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h2 class="card-title">Listado de gastos</h4>
                            <button id="btnAddExpense" type="button" class="btn btn-info btn-rounded m-t-10 float-right" data-toggle="modal" data-target="#modalConfirmation">Agregar gasto</button>
                        </div>
                        <h4 id="totalTable">Total: $</h4>
                        <div class="table-responsive m-t-20">
                            <table id="expensesTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Gasto</th>
                                        @if (auth()->user()->rol_id == '1')
                                            <th>Repartidor</th>
                                        @endif
                                        <th>Fecha</th>
                                        @if (auth()->user()->rol_id == '1')
                                            <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody id="table_body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="/expense/delete" method="post" id="form-delete">
            @csrf
            <input type="hidden" name="id" id="expense_id">
        </form>
    </div>

    <script>
        window.userRol = "{{ auth()->user()->rol_id }}";
    </script>

    <script>
        moment.locale('es');
        $('#dateFrom').bootstrapMaterialDatePicker({
            maxDate: new Date(),
            time: false,
            format: 'DD/MM/YYYY',
            cancelText: "Cancelar",
            weekStart: 1,
        });

        $("#dateFrom").on("change", function() {
            $("#datesContainer").removeClass("col-lg-5");
            $("#datesContainer").addClass("col-lg-6");
            $("#dateToContainer").css("display", "block");
            $('#dateTo').bootstrapMaterialDatePicker({
                minDate: $("#dateFrom").val(),
                maxDate: new Date(),
                time: false,
                format: 'DD/MM/YYYY',
                cancelText: "Cancelar",
                weekStart: 1,
            });
        });
        $("#dateTo").on("change", function() {
            searchExpenses()
        });
        $("#dateFrom").on("change", function() {
            if ($("#dateTo").val() != "") {
                searchExpenses();
            }
        });
    </script>

    {{-- Buscar ventas para la facturación --}}
    <script>
        function formatDate(date) {
            // Convertir a formato yyyy-mm-dd
            let partesFecha = date.split("/");
            let fechaNueva = new Date(partesFecha[2], partesFecha[1] - 1, partesFecha[0]);
            let fechaISO = fechaNueva.toISOString().slice(0,10);
            return fechaISO;
        }

        function searchExpenses() {
            $("#dateFromFormatted").val(formatDate($("#dateFrom").val()));
            $("#dateToFormatted").val(formatDate($("#dateTo").val()));
            $.ajax({
                url: $("#form-expense").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-expense").attr('method'), // Utiliza el método del formulario
                data: $("#form-expense").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    let content = "";
                    response.data.forEach((expense) => {
                        content += "<tr data-id='" + expense.id + "'>";
                        content += "<td>" + expense.description + "</td>";
                        content += "<td class='text-right spent'>$" + expense.spent + "</td>";
                        if (window.userRol == 1)
                            content += "<td>" + expense.user + "</td>";
                        content += "<td>" + expense.date + "</td>";
                        if (window.userRol == 1)
                            content += `<td class='text-center'><button type='button' class='btn btn-danger btn-rounded btn-sm' onclick='deleteExpense(` + expense.id + `)'><i class='fas fa-trash-alt'></i></button></td>`;
                        content += "</tr>";
                    });
                    $("#table_body").html(content);
                    if (!$.fn.DataTable.isDataTable('#expensesTable')) {
                        $('#expensesTable').DataTable({
                            "ordering": false,
                            "language": {
                                // "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // La url reemplaza todo al español
                                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ gastos",
                                "sInfoEmpty": "Mostrando 0 a 0 de 0 gastos",
                                "sInfoFiltered": "(filtrado de _MAX_ gastos en total)",
                                "emptyTable": 'No hay gastos que coincidan con la búsqueda',
                                "sLengthMenu": "Mostrar _MENU_ gastos",
                                "sSearch": "Buscar:",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior",
                                },
                            },
                        });
                    }
                    calculateTotal();
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
        };
    </script>
    {{-- Calcular el total de la tabla --}}
    <script>
        function calculateTotal() {
            let total = 0;
            $(".spent").each(function() {
                total += parseInt($(this).text().replace("$", ""));
            });
            $("#totalTable").text("Total: $" + total);
        }
    </script>

    <script>
        //For validation with custom styles
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            event.preventDefault();
                            sendForm();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        function sendForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-create").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-create").attr('method'), // Utiliza el método del formulario
                data: $("#form-create").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnCloseModal").click();
                    Swal.fire({
                        icon: 'success',
                        title: response.message,
                        confirmButtonColor: '#1e88e5',
                    });
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
        };

        function deleteExpense(id) {
            Swal.fire({
                title: '¿Seguro deseas eliminar este gasto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger waves-effect waves-light px-3 py-2',
                    cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                }
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $("#expense_id").val(id);
                    // Enviar solicitud AJAX
                    $.ajax({
                        url: $("#form-delete").attr('action'), // Utiliza la ruta del formulario
                        method: $("#form-delete").attr('method'), // Utiliza el método del formulario
                        data: $("#form-delete").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            let row = $('#expensesTable').DataTable().row(`tr[data-id='${id}']`);
                            if (row.length) {
                                // Si la fila existe, la eliminamos del DataTable
                                row.remove().draw(false);
                            }
                            calculateTotal();
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                confirmButtonColor: '#1e88e5',
                            });
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
        };
        
        $("#btnAddExpense").on("click", function () {
            $("#form-create").removeClass('was-validated');
            $("#form-create input:not([name='_token'], [name='user_id'])").val("");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
@endsection
