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
    <!-- Data table -->
    <link href="{{ asset('plugins/datatables/media/css/dataTables.bootstrap4.css') }}" rel="stylesheet">

    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>


    <!-- Modal route products returned -->
    <div id="modalProducts" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/route/createManualCart') }}" id="formManualCart" autocomplete="off" novalidate>
                @csrf
                <input type="hidden" name="client_id" value="">
                <input type="hidden" name="products_quantity" value="">
                <input type="hidden" name="payment_methods" value="">
                <input type="hidden" name="route_id" value="{{ $route->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Productos a vender</h4>
                        <button id="btnCloseModalProducts" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive" id="table_products_client">
                                    <table class="table" id="modalProductsTable">
                                        <thead>
                                            <tr>
                                                <th class="col-4">Cantidad</th>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="d-flex flex-row justify-content-between">
                                    <p id="totalAmount" class="mr-2 mb-0">Total pedido: $0</p>
                                </div>
                                <hr>
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-row justify-content-between mb-3">
                                        <div class="col-6 d-flex flex-row align-items-center">
                                            <div class="switch">
                                                <label>
                                                    <input id="cash_checkbox" type="checkbox" checked><span class="lever switch-col-teal"></span>
                                                </label>
                                            </div>
                                            <div class="demo-switch-title">{{ $cash->method }}</div>
                                        </div>
                                        <div id="cash_input_container" class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input id="cash_input" type="number" min="0" class="form-control mr-1" disabled data-id="{{ $cash->id }}">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between mb-3">
                                        <div class="col-6 d-flex flex-row align-items-center">
                                            <div class="switch">
                                                <label>
                                                    <input id="method_checkbox" type="checkbox" @checked(false)><span class="lever switch-col-teal"></span>
                                                </label>
                                            </div>
                                            <div class="demo-switch-title">Otro</div>
                                        </div>
                                        <div id="methods_input_container" class="input-group" style="display: none">
                                            <select name="method" id="payment_method" class="form-control mr-1" disabled>
                                                <option value="" disabled selected>Seleccionar</option>
                                                @foreach ($payment_methods as $pm)
                                                    <option value="{{ $pm->id }}">{{ $pm->method }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div id="amount_input_container" class="input-group w-50 mb-1" style="display: none">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input id="amount_input" type="number" min="0" class="form-control mr-1" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="validateForm()" class="btn btn-success waves-effect waves-light">Confirmar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Repartos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/route/index') }}">Repartos</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/route/new') }}">Nuevo</a></li>
                    <li class="breadcrumb-item active">Carrito</li>
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
                <h2 class="text-left">Agregar venta manual al reparto del <b>{{ $diasSemana[$route->day_of_week] }}</b> de <b>{{ $route->user->name }}</b></h2>
                <hr />
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title">Listado de clientes</h4>
                        <div class="table-responsive">
                            <table id="clientsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>DNI</th>
                                        <th>Factura</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients->sortBy('name') as $client)
                                        <tr data-id="{{ $client->id }}" class="client_row">
                                            <td><p class="m-0" style="color: cornflowerblue; cursor: pointer" onclick="openModal({{ $client->id }})" data-toggle="modal" data-target="#modalProducts">{{ $client->name }}</p></td>
                                            <td>{{ $client->adress }}</td>
                                            <td>{{ $client->phone }}</td>
                                            <td>{{ $client->dni }}</td>
                                            <td class="text-center">
                                                @if ( $client->invoice == true)
                                                    <i class="bi bi-check2" style="font-size: 1.5rem"></i>
                                                @else
                                                    <i class="bi bi-x-lg" style="font-size: 1.3rem"></i>
                                                @endif
                                            </td>
                                            <td>{{ $client->observation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Fill modal --}}
        <form id="form_search_products" action="{{ url('/route/getProductsClient') }}" method="GET">
            @csrf
            <input type="hidden" name="client_id" value="">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#clientsTable').DataTable({
                "ordering": false,
                "language": {
                    // "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" // La url reemplaza todo al español
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                    "sInfoFiltered": "(filtrado de _MAX_ clientes en total)",
                    "emptyTable": 'No hay clientes que coincidan con la búsqueda',
                    "sLengthMenu": "Mostrar _MENU_ clientes",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior",
                    },
                },
            });
        });
    </script>

        {{-- Habilitar los medios de pago --}}
    <script>
        $(document).ready(function() {
            // Agregamos evento change a los checkboxes
            $('#cash_checkbox').change(function() {
                // Obtenemos el input number correspondiente
                let inputNumber = $('#cash_input_container');
                // Mostramos u ocultamos el input number según el estado del checkbox
                if($(this).is(":checked")) {
                    inputNumber.show();
                    inputNumber.find('input').prop('disabled', false);
                } else {
                    inputNumber.hide();
                    inputNumber.find('input').prop('disabled', true);
                    inputNumber.find('input').val("");
                }
            });
            $('#method_checkbox').change(function() {
                // Obtenemos el input number correspondiente
                let input = $('#methods_input_container');
                // Mostramos u ocultamos el input number según el estado del checkbox
                if($(this).is(":checked")) {
                    $("#payment_method").val("");
                    $("#payment_method").prop('disabled', false);
                    input.show();
                } else {
                    $("#payment_method").val("");
                    $("#payment_method").prop('disabled', true);
                    input.hide();
                    $("#amount_input_container").css("display", "none");
                    $("#amount_input").prop("disabled", true);
                    $("#amount_input").val("");
                }
            });
            $("#payment_method").on("change", function() {
                $("#amount_input_container").css("display", "flex");
                $("#amount_input").prop("disabled", false);
                $("#amount_input").val("");
            });
        });

        $("input[type='number']").on("input", function() {
            if ($(this).val() <= 0 || !esNumero($(this).val())) {
                $(this).val("");
            }
        });

        function esNumero(valor) {
            return /^\d+$/.test(valor);
        }
    </script>

    <script>
        function openModal(id) {
            $("input[name='client_id']").val(id);

            $("#cash_checkbox").prop("checked", true);
            $("#cash_input_container").css("display", "flex");
            $("#cash_input_container input").prop("disabled", false);
            $("#cash_input_container input").val("");

            $("#method_checkbox").prop("checked", false);
            $("#methods_input_container").css("display", "none");

            $("#amount_input_container").css("display", "none");
            $("#amount_input").prop("disabled", true);

            $("#table_products_client table tbody").html("");
            $.ajax({
                url: $("#form_search_products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form_search_products").attr('method'), // Utiliza el método del formulario
                data: $("#form_search_products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    let content = "";
                    response.products.forEach(product => {
                        content += `
                            <tr data-id="${product.product_id}">
                                <td><input type="number" class="form-control quantity-input" min="0" max="10000"></td>
                                <td>${product.product.name}</td>
                                <td class="precioProducto">$${product.product.price}</td>
                            </tr>
                        `;
                    });
                    $("#table_products_client table tbody").html(content);

                    $(".quantity-input").on("input", function() {
                        let total = 0;
                        $("#table_products_client table tbody tr").each(function() {
                            let precioUnit = $(this).find(".precioProducto").text().replace('$', '');
                            let cantidad = $(this).find(".quantity-input").val();
                            let resultado = precioUnit * cantidad;
                            total += resultado;
                        });

                        $("#totalAmount").html("Total pedido: $" + total);
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
    </script>

    {{-- Send form --}}
    <script>
        function createProductsJSON() {
            let products = [];
            $('#modalProducts table tbody tr').each(function() {
                let productId = $(this).data('id');
                let quantity = $(this).find('input').val();
                if (quantity !== "" && quantity > 0) {
                    products.push({
                        product_id: productId,
                        quantity: quantity
                    });
                }
            });
            $("#formManualCart input[name='products_quantity']").val(JSON.stringify(products));
            return products;
        }

        function createPaymentMethodsJSON() {
            // Métodos de pago
            let payment_methods = [];
            let cash = $("#cash_input").val();
            if (cash !== "" && cash > 0) {
                payment_methods.push({
                    method: $("#cash_input").data('id'),
                    amount: cash
                });
            }
            let other = $("#amount_input").val();
            if (other !== "" && other > 0) {
                payment_methods.push({
                    method: $("#payment_method").val(),
                    amount: other
                });
            }
            $("#formManualCart input[name='payment_methods']").val(JSON.stringify(payment_methods));
            return payment_methods;
        }

        function validateForm() {
            products = createProductsJSON();
            payment_methods = createPaymentMethodsJSON();
            if (products.length > 0 && payment_methods.length >= 0) {
                if (payment_methods.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ALERTA',
                        text: '¿Seguro que quieres cargar todo en la cuenta corriente del cliente?',
                        showCancelButton: true,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-success waves-effect waves-light px-3 py-2',
                            cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                        }
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            sendProductsForm();
                        }
                    })
                } else {
                    sendProductsForm();
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: "ERROR",
                    text: "Debes ingresar al menos un producto",
                    confirmButtonColor: '#1e88e5',
                });
            }
        }

        function sendProductsForm() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#formManualCart").attr('action'), // Utiliza la ruta del formulario
                method: $("#formManualCart").attr('method'), // Utiliza el método del formulario
                data: $("#formManualCart").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnCloseModalProducts").click();
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#1e88e5',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                    })
                    .then((result) => {
                        if (result.isConfirmed && window.userRol != '1') {
                            window.location.href = "{{ route('route.details', ['id' => $route->id] ) }}";
                        }
                    })
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

    <style>
        #clientsTable tbody tr:hover {
            background-color: #dee2e6;
        }
    </style>

@endsection
