@php
    use Carbon\Carbon;
    $today = Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));

    $diasSemana = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
    ];
    $states = [
        0 => 'Pendiente',
        1 => 'Completado',
        2 => 'No estaba',
        3 => 'No necesitaba',
        4 => 'De vacaciones',
    ];
@endphp

@extends('layouts.app')

@section('content')
    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/cart/confirm') }}" id="form-confirm" autocomplete="off" novalidate>
                @csrf
                <input type="hidden" name="cart_id" value="">
                <input type="hidden" name="products_quantity" value="">
                <input type="hidden" name="payment_methods" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmar pedido</h4>
                        <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                                        <p id="modalClientDebt"></p>
                                    </div>
                                    <hr >
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnPayCart" class="btn btn-success waves-effect waves-light">Pagar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    @if (auth()->user()->rol_id == '1')
        <!-- Modal route products -->
        <div id="modalProducts" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/route/updateDispatched') }}" id="formRouteProducts" autocomplete="off" novalidate>
                    @csrf
                    <input type="hidden" name="products_quantity" value="">
                    <input type="hidden" name="route_id" value="{{ $route->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Productos cargados en el camión</h4>
                            <button id="btnCloseModalProducts" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table" id="modalProductsTable">
                                            <thead>
                                                <tr>
                                                    <th class="col-4">Cantidad</th>
                                                    <th>Producto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productsDispatched as $product)
                                                    <tr data-id="{{ $product->product_id }}">
                                                        <td><input type="number" class="form-control" min="0" max="10000" value="{{ $product->quantity }}"></td>
                                                        <td>{{ $product->Product->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="btnUpdateProducts" class="btn btn-success waves-effect waves-light">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
    @endif

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
            @if (auth()->user()->rol_id == '1' && $route->is_static === false)
                <div class="col-md-7 col-4 align-self-center">
                    <div class="d-flex m-t-10 justify-content-end">
                        <div class="d-flex m-r-20 m-l-10">
                            <button id="btnAddProducts" class="btn btn-info" data-toggle="modal" data-target="#modalProducts">Productos cargados</button>
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
        @if ($route->is_static === false && auth()->user()->rol_id == '1')
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex no-block">
                                <h4 class="card-title">Productos vendidos</h4>
                            </div>
                            <h6 class="card-subtitle">{{ $today->format('d/m/Y') }}</h6>
                            <div class="table-responsive">
                                <table class="table stylish-table">
                                    <thead>
                                        <tr>
                                            <th style="width:90px;">Producto</th>
                                            <th>Descriptción</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products_sold as $item)    
                                        <tr>
                                            <td><span class="round"><i class="ti-shopping-cart"></i></span></td>
                                            <td>
                                                <h6>{{ $item->Product->name }}</h6><small class="text-muted">Precio: ${{ $item->Product->price }}</small>
                                            </td>
                                            <td>
                                                <h5>{{ $item->total_quantity }}</h5>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-lg-6 col-md-12">
                    <div class="row">
                        <!-- Column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-currency-usd"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0 font-light">${{ $data->day_collected }}</h3>
                                            <h5 class="text-muted m-b-0">
                                                Recaudado en el día
                                                <a class="mytooltip" href="javascript:void(0)">
                                                    <i class="bi bi-info-circle"></i>
                                                    <span class="tooltip-content5">
                                                        <span class="tooltip-text3">
                                                            <span class="tooltip-inner2">
                                                                <div class="d-flex">
                                                                    <table>
                                                                        <tbody>
                                                                            @foreach ($data->payment_used as $item)
                                                                                <tr>
                                                                                    <td><h6 class="text-white text-left">{{ $item["name"] }}: ${{ $item["total"] }}</h6></td>
                                                                                </tr>
                                                                            @endforeach
                                                                    </table>
                                                                </div>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round round-lg align-self-center round-danger"><i class="mdi mdi-shopping"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0 font-lgiht">${{ $data->day_expenses }}</h3>
                                            <h5 class="text-muted m-b-0">Gastos del día</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                    <div class="row">
                        <!-- Column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round round-lg align-self-center round-success"><i class="mdi mdi-checkbox-marked-circle-outline"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0 font-lgiht">{{ $data->completed_carts }}</h3>
                                            <h5 class="text-muted m-b-0">Clientes visitados</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                        <!-- Column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-clock-fast"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0 font-lgiht">{{ $data->pending_carts }}</h3>
                                            <h5 class="text-muted m-b-0">Clientes por visitar</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <h3 class="m-0">Repartos de <b>{{ $route->User->name }}</b> para el <b>{{ $diasSemana[$route->day_of_week] }}</b></h1>
                        @if (auth()->user()->rol_id == '1')
                        <button type="button" id="btnDeleteRoute" class="btn btn-sm btn-danger btn-rounded px-3">Eliminar reparto</button>
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
                                    @if ($cart->state === 1)
                                        <div class="timeline-badge" style="background-color: #30d577"><i class="bi bi-truck"></i></div>
                                    @elseif ($cart->state === 2 || $cart->state === 3 || $cart->state === 4)
                                        <div class="timeline-badge" style="background-color: #ffc107"><i class="bi bi-truck"></i></div>
                                    @else
                                        <div class="timeline-badge" style="background-color: #6c757d"><i class="bi bi-truck"></i></div>
                                    @endif
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            @if ($cart->state === 1)
                                                <h4 class="timeline-title" style="color: #30d577">{{ $cart->Client->name }} - {{ $states[$cart->state] }}</h4>
                                            @elseif ($cart->state === 2 || $cart->state === 3 || $cart->state === 4)
                                                <h4 class="timeline-title" style="color: #ffc107">{{ $cart->Client->name }} - {{ $states[$cart->state] }}</h4>
                                            @elseif ($cart->state === 0)
                                                <h4 class="timeline-title" style="color: #6c757d">{{ $cart->Client->name }} - {{ $states[$cart->state] }}</h4>
                                            @else
                                                <h4 class="timeline-title" style="color: #6c757d">{{ $cart->Client->name }}</h4>
                                            @endif

                                            {{-- Deuda / saldo a favor --}}
                                            @if ($cart->Client->debt > 0)
                                                <p class="m-0"><small class="text-danger">Deuda: ${{ $cart->Client->debt }}</small></p>
                                            @elseif ($cart->Client->debt < 0)
                                                <p class="m-0"><small style="color: #30d577">Saldo a favor: ${{ $cart->Client->debt * -1 }}</small></p>
                                            @else
                                                <p class="m-0"><small class="text-muted">Sin deuda</small></p>
                                            @endif
                                            <p class="mb-0"><small class="text-muted"><i class="bi bi-house-door"></i> {{ $cart->Client->adress }}&nbsp;&nbsp;-&nbsp;&nbsp;<i class="bi bi-telephone"></i> {{ $cart->Client->phone }}</small></p>
                                        </div>
                                        <div class="timeline-body">
                                            @if ($cart->state === 1)
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
                                                <div class="d-flex flex-row justify-content-end">
                                                    <p class="m-0">Total del pedido: ${{ $cart->ProductsCart->sum(function($product_cart) {
                                                        return $product_cart->setted_price * $product_cart->quantity;
                                                    }) }}</p>
                                                </div>
                                                <hr>
                                            @endif

                                            @if ($cart->Client->observation != "")
                                                <hr>
                                                <p><b>Observaciones:</b> {{ $cart->Client->observation }}</p>
                                            @endif

                                            @if ($cart->state === 0)
                                                <div class="d-flex flex-row justify-content-end">

                                                    {{-- 2 = employee --}}
                                                    @if (auth()->user()->rol_id == '2')
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                            <div class="dropdown-menu">
                                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal({{ $cart->id }}, {{ $cart->Client->id }}, {{ $cart->Client->debt }})"><b>Confirmar</b></button>
                                                                <div class="dropdown-divider"></div>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(2, {{ $cart->id }}, 'no estaba')">No estaba</button>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(3, {{ $cart->id }}, 'no necesitaba')">No necesitaba</button>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(4, {{ $cart->id }}, 'estaba de vacaciones')">Vacaciones</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif ($cart->state === 1)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Método de pago</th>
                                                                        <th>Cantidad</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($cart->CartPaymentMethod as $pm)    
                                                                        <tr>
                                                                            <td>{{ $pm->PaymentMethod->method}}</td>
                                                                            <td>${{ $pm->amount }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="d-flex flex-row justify-content-end">
                                                            <p class="m-0">Total abonado: ${{ $cart->CartPaymentMethod->sum(function($pm) {
                                                                return $pm->amount;
                                                            }) }}</p>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- 1 = admin --}}
                                            @if (auth()->user()->rol_id == '1' && $cart->is_static === false) 
                                            <hr>
                                                <div class="d-flex flex-row justify-content-end">
                                                    {{-- Delete Cart --}}
                                                    <form id="formDeleteCart_{{ $cart->id }}" action="{{ url('/cart/delete') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $cart->id }}">
                                                        <button name="btnDeleteCart" value="{{ $cart->id }}" type="button" class="btn btn-sm btn-danger btn-rounded px-3">Eliminar</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if (($route->is_static === false && auth()->user()->rol_id == '2') || $route->is_static === true)
                            <div class="d-flex flex-row justify-content-end">
                                <a class="btn btn-info btn-rounded m-t-30 float-right" href="{{ url('/route/' . $route->id . '/newCart') }}">Agregar nuevo cliente</a>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- Delete Route --}}
                <form id="formDeleteRoute" action="{{ url('/route/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $route->id }}">
                </form>
                {{-- Fill modal --}}
                <form id="form_search_products" action="{{ url('/route/getProductsClient') }}" method="GET">
                    @csrf
                    <input type="hidden" id="client_id" name="client_id" value="">
                </form>

                {{-- Actions --}}
                <form id="form_no_confirmation" action="{{ url('/cart/changeState') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="state" value="">
                </form>
            </div>
        </div>
    </div>

    {{-- Productos en el camión de un repartidor --}}
    <script>
        $("#modalProducts input[type='number']").on("input", function() {
            if ($(this).val() < 0) {
                $(this).val(0);
            } else if ($(this).val() > 10000){
                $(this).val(10000);
            }
        });

        $("#btnUpdateProducts").on("click", function() {
            // Productos
            let products = [];
            $('#modalProducts table tbody tr').each(function() {
                let productId = $(this).data('id');
                let quantity = $(this).find('input').val();
                if (quantity !== "") {
                    products.push({
                        product_id: productId,
                        quantity: quantity
                    });
                }
            });
            $("#formRouteProducts input[name='products_quantity']").val(JSON.stringify(products));
            
            function updateProducts() {
                $.ajax({
                    url: $("#formRouteProducts").attr('action'), // Utiliza la ruta del formulario
                    method: $("#formRouteProducts").attr('method'), // Utiliza el método del formulario
                    data: $("#formRouteProducts").serialize(), // Utiliza los datos del formulario
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
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
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

            if (products.length <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ALERTA',
                    text: 'Debes ingresar al menos un producto',
                    showCancelButton: false,
                    confirmButtonColor: '#1e88e5',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                })
            } else {
                updateProducts();
            }
        });
    </script>
    
    {{-- Pagar un carrito --}}
    <script>
        $("#btnPayCart").on("click", function() {
            // Productos
            let products = [];
            $('.quantity-input').each(function() {
                let productId = $(this).data('id');
                let quantity = $(this).val();
                if (quantity !== "" && quantity > 0) {
                    products.push({
                        product_id: productId,
                        quantity: quantity
                    });
                }
            });
            $("#form-confirm input[name='products_quantity']").val(JSON.stringify(products));
            
            // Métodos de pago
            let payment_methods = [];
            let cash = $("#cash_input").val();
            if (cash !== "") {
                payment_methods.push({
                    method: $("#cash_input").data('id'),
                    amount: cash
                });
            }
            let other = $("#amount_input").val();
            if (other !== "") {
                payment_methods.push({
                    method: $("#payment_method").val(),
                    amount: other
                });
            }
            $("#form-confirm input[name='payment_methods']").val(JSON.stringify(payment_methods));

            function payCart() {
                $.ajax({
                    url: $("#form-confirm").attr('action'), // Utiliza la ruta del formulario
                    method: $("#form-confirm").attr('method'), // Utiliza el método del formulario
                    data: $("#form-confirm").serialize(), // Utiliza los datos del formulario
                    success: function(response) {
                        $("#btnCloseModal").click();
                        Swal.fire({
                                title: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#1e88e5',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
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
                            payCart();
                        }
                    })
                } else {
                    payCart();
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: "ERROR",
                    text: "Debes ingresar al menos un producto",
                    confirmButtonColor: '#1e88e5',
                });
            }
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
            })
        });

        $("input[type='number']").on("input", function() {
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
            data.forEach(p => {
                content += '<tr>';
                content += '<td><input type="number" min="0" max="1000" class="form-control quantity-input" data-id="' + p.product.id + '" ></td>';
                content += '<td>' + p.product.name + '</td>';
                content += '<td class="precioProducto">$ ' + p.product.price + '</td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);

            // Calcular el total dentro del modal

            $(".quantity-input").on("input", function() {
                let total = 0;
                $("#modalTable tbody tr").each(function() {
                    let precioUnit = $(this).find(".precioProducto").text().replace('$', '');
                    let cantidad = $(this).find(".quantity-input").val();
                    let resultado = precioUnit * cantidad;
                    total += resultado;
                });

                $("#totalAmount").html("Total pedido: $" + total);
            });
        };

        function openModal(cart_id, client_id, debt) {
            // Para el modal
            $("#form-confirm input[name='cart_id']").val(cart_id);
            console.log(debt)
            if (debt > 0) {
                $("#modalClientDebt").text("Deuda: $" + debt);
            } else if (debt < 0) {
                $("#modalClientDebt").text("Saldo a favor: $" + debt * -1);
            } else {
                $("#modalClientDebt").text("Sin deuda");
            }
            $("#tableBody").html("");
            $("#client_id").val(client_id);
            $("#cash_checkbox").prop("checked", true);
            $("#cash_input_container").css("display", "flex");
            $("#cash_input_container input").prop("disabled", false);
            $("#cash_input_container input").val("");

            $("#method_checkbox").prop("checked", false);
            $("#methods_input_container").css("display", "none");

            $("#amount_input_container").css("display", "none");
            $("#amount_input").prop("disabled", true);

            // Enviar solicitud AJAX para rellenar el modal
            $.ajax({
                url: $("#form_search_products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form_search_products").attr('method'), // Utiliza el método del formulario
                data: $("#form_search_products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    fillModal(response.products);
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
        }

        $("#btnDeleteRoute").on("click", function() {
            Swal.fire({
                title: "Esta acción no se puede revertir",
                text: '¿Seguro deseas eliminar este reparto?',
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
                    $.ajax({
                        url: $("#formDeleteRoute").attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteRoute").attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteRoute").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                confirmButtonColor: '#1e88e5',
                                allowOutsideClick: false,
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = window.location.origin + "/home";
                                }
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
        });


        $("button[name='btnDeleteCart']").on("click", function() {
            let id = $(this).val();
            Swal.fire({
                title: "Esta acción no se puede revertir",
                text: '¿Seguro deseas eliminar este cliente?',
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
                    $.ajax({
                        url: $("#formDeleteCart_" + id).attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteCart_" + id).attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteCart_" + id).serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                confirmButtonColor: '#1e88e5',
                                allowOutsideClick: false,
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
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
        });
    </script>

    {{-- Acciones carrito --}}
    <script>
        function sendStateChange(state, cart_id, action) {
            $("#form_no_confirmation input[name='id']").val(cart_id);
            $("#form_no_confirmation input[name='state']").val(state);

            Swal.fire({
                title: "¿Está seguro que el cliente " + action + "?",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: 'Confirmar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success waves-effect waves-light px-3 py-2',
                    cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                }
            })
            // Si confirma la acción, envía el formulario
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $("#form_no_confirmation").attr('action'),
                        method: $("#form_no_confirmation").attr('method'),
                        data: $("#form_no_confirmation").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
                            Swal.fire({
                                title: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#1e88e5',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                            })
                            .then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
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
        }
    </script>
@endsection