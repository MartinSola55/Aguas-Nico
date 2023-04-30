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
    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/route/confirm') }}" id="form-confirm" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmar pedido</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table" id="modalTable">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <div class="d-flex flex-row justify-content-between">
                                        <p id="totalAmount" class="mr-2 mb-0">Total pedido: $0</p>
                                        <p id="modalClientDebt">Deuda: $</p>
                                    </div>
                                    <hr >
                                    <div class="d-flex flex-column">
                                        @foreach ($payment_methods as $pm)
                                            <div class="d-flex flex-row justify-content-between mb-3">
                                                <div class="col-6 d-flex flex-row">    
                                                    <div class="switch">
                                                        <label>
                                                            <input type="checkbox" class="payment_checkbox"><span class="lever switch-col-red"></span>
                                                        </label>
                                                    </div>
                                                    <div class="demo-switch-title">{{ $pm["method"] }}</div>
                                                </div>
                                                <div class="input-group payment_input_container" style="display: none">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" min="0" class="form-control mr-1" disabled name="method_{{ $pm["id"] }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Pagar</button>
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
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <h3 class="m-0">Repartos de <b>{{ $route->User->name }}</b> para el <b>{{ $diasSemana[$route->day_of_week] }}</b></h1>
                        @if (auth()->user()->rol_id == '1')
                        <button type="button" id="btnDeleteRoute" class="btn btn-sm btn-primary btn-rounded px-3">Eliminar ruta</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            <?php
                                $contador = 0;
                            ?>
                            @foreach ($route->Carts->sortBy('priority') as $cart)
                                <?php $contador++; ?>
                                @if ($contador % 2 != 0)
                                <li>
                                @else
                                <li class="timeline-inverted">
                                @endif
                                    @if ($cart->state === 0)
                                        <div class="timeline-badge danger"><i class="bi bi-truck"></i></div>
                                    @else
                                        <div class="timeline-badge" style="background-color: #30d577"><i class="bi bi-truck"></i></div>
                                    @endif
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            @if ($cart->state !== 0)
                                                <h4 class="timeline-title" style="color: #30d577">{{ $cart->Client->name }} - Deuda: ${{ $cart->Client->debt }}</h4>
                                            @else
                                                <h4 class="timeline-title" style="color: #fc4b6c">{{ $cart->Client->name }} - Deuda: ${{ $cart->Client->debt }}</h4>
                                            @endif
                                            <p><small class="text-muted"><i class="bi bi-house-door"></i> {{ $cart->Client->adress }}</small></p>
                                        </div>
                                        <div class="timeline-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Cantidad</th>
                                                                    <th>Producto</th>
                                                                    <th>Precio</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($cart->ProductsCart as $pc)    
                                                                    <tr>
                                                                        <td>{{ $pc->quantity}}</td>
                                                                        <td>{{ $pc->product->name }}</td>
                                                                        <td>${{ $pc->product->price }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            @if ($cart->Client->observation != "")
                                                <p><b>Observaciones:</b> {{ $cart->Client->observation }}</p>
                                            @endif
                                            @if ($cart->state === 0)
                                                @if ($cart->Client->observation != "")
                                                    <hr>
                                                @endif
                                                <div class="d-flex flex-row justify-content-end">
                                                    {{-- 2 = employee --}}
                                                    @if (auth()->user()->rol_id == '2')
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                        <div class="dropdown-menu">
                                                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal({{ $cart->id }}, {{ $cart->Client->debt }})"><b>Confirmar</b></button>
                                                            <div class="dropdown-divider"></div>
                                                            <button class="dropdown-item" type="button" style="cursor: pointer;" id="btnNoEstaba">No estaba</button>
                                                            <button class="dropdown-item" type="button" style="cursor: pointer;" id="btnNoNecesitaba">No necesitaba</button>
                                                            <button class="dropdown-item" type="button" style="cursor: pointer;" id="btnVacaciones">Vacaciones</button>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{-- 1 = admin --}}
                                                    @if (auth()->user()->rol_id == '1') 
                                                    <div>
                                                        {{-- Delete Cart --}}
                                                        <form id="formDeleteCart_{{ $cart->id }}" action="{{ url('/cart/delete') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $cart->id }}">
                                                            <button name="btnDeleteCart" value="{{ $cart->id }}" type="button" class="btn btn-sm btn-primary btn-rounded px-3">Eliminar</button>
                                                        </form>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex flex-row justify-content-end">
                            <a class="btn btn-danger btn-rounded m-t-30 float-right" href="{{ url('/route/' . $route->id . '/newCart') }}">Agregar nuevo cliente</a>
                        </div>
                    </div>
                </div>
                {{-- Delete Route --}}
                <form id="formDeleteRoute" action="{{ url('/route/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $route->id }}">
                </form>
                {{-- Acción-->No estaba --}}
                <form id="formNoEstaba" action="{{ url('/route/noEstaba') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $route->id }}">
                </form>
                <form id="form_search_products" action="{{ url('/route/getProductCarts') }}" method="GET">
                    @csrf
                    <input type="hidden" id="cart_id" name="id" value="">
                </form>
            </div>
        </div>
    </div>

    {{-- Habilitar los medios de pago --}}
    <script>
        $(document).ready(function() {
            // Agregamos evento change a los checkboxes
            $('.payment_checkbox').change(function() {
                // Obtenemos el input number correspondiente
                let inputNumber = $(this).closest('.mb-3').find('.payment_input_container');
                // Mostramos u ocultamos el input number según el estado del checkbox
                if($(this).is(":checked")) {
                    inputNumber.show();
                    inputNumber.find('input').prop('disabled', false);
                } else {
                    inputNumber.hide();
                    inputNumber.find('input').prop('disabled', true);
                }
            });
        });

        $(".payment_input_container input").on("input", function() {
                if ($(this).val() <= 0 || !esNumero($(this).val())) {
                    $(this).val("");
                }
            });
    </script>

    <script>
        function esNumero(valor) {
            return /^\d+$/.test(valor);
        }
        
        // Pegada AJAX que busca los productos del carrito seleccionado y completa el modal
        function fillModal(data) {
            let content = "";
            data.forEach(pc => {
                content += '<tr>';
                content += '<td><input type="number" min="0" max="1000" class="form-control cantidadProducto"></td>';
                content += '<td>' + pc.product.name + '</td>';
                content += '<td class="precioProducto">$ ' + pc.product.price + '</td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);

            // Calcular el total dentro del modal

            $(".cantidadProducto").on("input", function() {
                if ($(this).val() <= 0 || !esNumero($(this).val())) {
                    $(this).val("");
                }
                let total = 0;
                $("#modalTable tbody tr").each(function() {
                    let precioUnit = $(this).find(".precioProducto").text().replace('$', '');
                    let cantidad = $(this).find(".cantidadProducto").val();
                    let resultado = precioUnit * cantidad;
                    total += resultado;
                });

                $("#totalAmount").html("Total pedido: $" + total);
            });
        };

        function openModal(id, debt) {
            $("#modalClientDebt").text("Deuda: $" + debt);
            $("#tableBody").html("");
            $("#cart_id").val(id);
            $(".payment_checkbox").prop("checked", false);
            $(".payment_input_container").css("display", "none");
            $(".payment_input_container input").prop("disabled", true);
            $(".payment_input_container input").val("");

            // Enviar solicitud AJAX para rellenar el modal
            $.ajax({
                url: $("#form_search_products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form_search_products").attr('method'), // Utiliza el método del formulario
                data: $("#form_search_products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    fillModal(response.cart.products_cart);
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.message,
                    });
                }
            });

            $('#modalTable select').each(function() {
                $(this).val($(this).find('option:first').val());
            });
        }

        $("#btnDeleteRoute").on("click", function() {
            Swal.fire({
                title: 'Seguro deseas eliminar este reparto?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
                })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $("#formDeleteRoute").attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteRoute").attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteRoute").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            window.location.href = window.location.origin + "/route/index";
                        },
                        error: function(errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: errorThrown.responseJSON.message,
                            });
                        }
                    });
                }
            })
        });


        $("button[name='btnDeleteCart']").on("click", function() {
            let id = $(this).val();
            Swal.fire({
                title: 'Seguro deseas eliminar este pedido?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar'
                })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $("#formDeleteCart_" + id).attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteCart_" + id).attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteCart_" + id).serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: errorThrown.responseJSON.message,
                            });
                        }
                    });
                }
            })
        });
    </script>
@endsection
