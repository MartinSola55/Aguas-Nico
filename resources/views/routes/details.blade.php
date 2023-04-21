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
                                                <th>Descargado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <p id="totalAmount" class="mr-2">Total pedido: $0</p>
                                    <p id="modalClientDebt">Deuda: $</p>
                                    <div>
                                        <div>
                                            <input type="checkbox" id="debtCheckbox" />
                                            <label for="debtCheckbox">Otro monto</label>
                                        </div>
                                        <div class="input-group mb-3 pr-1" id="paymentInput" style="display: none">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" max="1000000" class="form-control" name="payment" placearia-describedby="inputGroupPrepend" required>
                                        </div>
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
                        <h3 class="m-0">Repartos de <b>{{ $route->user->name }}</b> para el <b id="routeDate">{{ \Carbon\Carbon::parse($route->start_daytime)->format('d/m/Y') }}</b></h1>
                        <button type="button" id="btnDeleteRoute" class="btn btn-sm btn-primary btn-rounded px-3">Eliminar ruta</button>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            <?php
                                $contador = 0;
                            ?>
                            @foreach ($route->carts as $cart)
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
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                    <div class="dropdown-menu">
                                                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal({{ $cart->id }}, {{ $cart->Client->debt }})"><b>Confirmar</b></button>
                                                        <div class="dropdown-divider"></div>
                                                        <button class="dropdown-item" type="button" id="btnNoEstaba">No estaba</button>
                                                        <button class="dropdown-item" type="button" id="btnNoNecesitaba">No necesitaba</button>
                                                        <button class="dropdown-item" type="button" id="btnVacaciones">Vacaciones</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex flex-row justify-content-end">
                            <a class="btn btn-danger btn-rounded m-t-30 float-right" href="{{ url('/route/' . $route->id . '/newCart') }}">Agregar nuevo carrito</a>
                        </div>
                    </div>
                </div>
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

    <script>
        const diasDeLaSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        let dateFormatted = $("#routeDate").text();
        let date = new Date($("#routeDate").text().split('/').reverse().join('-'));
        $("#routeDate").html(diasDeLaSemana[date.getDay()] + ", " + dateFormatted);

        // Pegada AJAX que busca los productos del carrito seleccionado y completa el modal

        function fillModal(data) {
            let content = "";
            data.forEach(pc => {
                content += '<tr>';
                content += '<td>' + pc.quantity + '</span></td>';
                content += '<td>' + pc.product.name + '</td>';
                content += '<td class="precioProducto">$ ' + pc.product.price + '</td>';
                content += '<td>';
                content += '<select class="form-control cantidadProducto">';
                content += '<option value="0" selected>0</option>';
                for (let index = 0; index < pc.quantity; index++) {
                    content += '<option value="' + (index + 1) + '">' + (index + 1) + '</option>';
                }
                content += '</select>';
                content += '</td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);

            // Calcular el total dentro del modal

            $(".cantidadProducto").change(function() {
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
            $("#debtCheckbox").prop("checked", false);
            $("#paymentInput").css("display", "none");
            $("#paymentInput input").prop("disabled", true);

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

        $("#debtCheckbox").change(function() {
            if ($(this).prop("checked")) {
                $("#paymentInput").css("display", "flex");
                $("#paymentInput input").prop("disabled", false);
            } else {
                $("#paymentInput").css("display", "none");
                $("#paymentInput input").prop("disabled", true);
            }
        });

        $("#btnDeleteRoute").on("click", function() {
            Swal.fire({
                title: 'Seguro deseas eliminar esta ruta?',
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
    </script>
@endsection
