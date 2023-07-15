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

    @if (auth()->user()->rol_id == '2')

        <!-- Modal confirm cart -->
        <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" id="form-confirm" autocomplete="off" novalidate>
                    <input type="hidden" name="cart_id" value="">
                    <input type="hidden" name="products_quantity" value="">
                    <input type="hidden" name="cash" value="">
                    <input type="hidden" name="renew_abono" value="0">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmar pedido</h4>
                            <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12" id="colAbono">
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table" id="modalTable">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="d-flex row justify-content-between">
                                            <p id="totalAmount" class="col-12 align-items-center justify-content-end mb-0">Total pedido: $0</p>
                                        </div>
                                        <hr>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex flex-row justify-content-between mb-3">
                                                <div class="col-3 d-flex flex-row align-items-center">
                                                    <div class="demo-switch-title">Entrega</div>
                                                </div>
                                                <div id="cash_input_container" class="col-9 input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input id="cash_input" type="number" min="0" class="form-control mr-1" disabled data-id="{{ $cash->id }}">
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
                                    <hr />
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
    @endif

        <!-- Modal edit cart -->
        <div id="modalEditCart" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" id="form-edit-bajada" autocomplete="off" novalidate>
                    <input type="hidden" name="cart_id" value="">
                    <input type="hidden" name="products_quantity" value="">
                    <input type="hidden" name="cash" value="">
                    <input type="hidden" name="renew_abono" value="0">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Editar Bajada</h4>
                            <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12" id="colAbonoEdit">
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table" id="modalTableEditCart">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableEditCart" class="cart-items">
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="d-flex row justify-content-between">
                                            <p id="totalAmountEditCart" class="col-12 align-items-center justify-content-end mb-0"></p>
                                        </div>
                                        <hr>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex flex-row justify-content-between mb-3">
                                                <div class="col-3 d-flex flex-row align-items-center">
                                                    <div class="demo-switch-title">Entrega</div>
                                                </div>
                                                <div id="cash_input_container" class="col-9 input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input id="cash_input_edit_cart" type="number" min="0" class="form-control mr-1">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div id="amount_input_container" class="input-group w-50 mb-1" style="display: none">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" min="0" class="form-control mr-1" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                            <button type="button" id="btnEditCart" class="btn btn-success waves-effect waves-light">Editar</button>
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
                                                    <th>Producto</th>
                                                    <th class="col-4">Cantidad</th>
                                                    <th>Agregar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productsDispatched as $product)
                                                    <tr data-id="{{ $product->product_id }}">
                                                        <td>{{ $product->Product->name }}</td>
                                                        <td><input type="number" name="quantity_dispatched" class="form-control" min="0" max="10000" value="{{ $product->quantity }}"></td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control additional-quantity" min="0" max="10000" value="0">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-primary btn-add-quantity"><i class="bi bi-plus-lg"></i></button>
                                                                </div>
                                                            </div>
                                                        </td>
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

    @if (auth()->user()->rol_id == '2')
        <!-- Modal route products returned general -->
        <div id="modalProductsReturned" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog">
                <form role="form" class="needs-validation" method="POST" action="{{ url('/route/updateReturned') }}" id="formProductsReturned" autocomplete="off" novalidate>
                    @csrf
                    <input type="hidden" name="client_id" value="" id="client_id_prod_returned">
                    <input type="hidden" name="products_quantity" value="">
                    <input type="hidden" name="route_id" value="{{ $route->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Productos devueltos</h4>
                            <button id="btnCloseModalProductsReturned" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group col-12">
                                        <label for="selectClientToReturn">Cliente</label>
                                        <div>
                                            <select id="selectClientToReturn" name="client_id" class="form-control">
                                                <option disabled selected>Seleccione un cliente</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <form action=""></form>
                                    </div>
                                    <div class="table-responsive" id="table_products_client" style="display: none">
                                        <table class="table" id="modalProductsTable">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="col-4">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (auth()->user()->rol_id == '2')
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                                <button type="button" id="btnUpdateProductsReturned" class="btn btn-success waves-effect waves-light">Actualizar</button>
                            </div>
                        @endif

                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->

        <!-- Modal route products returned by client -->
        <div id="modalProductsReturnedByClient" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cargar devolución </h4>
                        <button id="btnCloseModal" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12" id="colAbono">
                            </div>
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Devuelve</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBodyReturnedByClient">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
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
            @elseif ($route->is_static === false)
                <div class="col-md-7 col-4 align-self-center">
                    <div class="d-flex m-t-10 justify-content-end">
                        <div class="d-flex m-r-20 m-l-10">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalProductsReturned">Productos devueltos</button>
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
                <div class="col-xlg-6 col-lg-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex no-block">
                                <h4 class="card-title">Productos vendidos</h4>
                            </div>
                            <h6 class="card-subtitle">{{ $today->format('d/m/Y') }}</h6>
                            <div class="table-responsive">
                                <table class="table stylish-table">
                                    <thead>
                                        <tr>
                                            <th style="width:10%;"></th>
                                            <th>Producto/Envase</th>
                                            <th>Vendidos</th>
                                            <th>Devueltos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->items as $item)
                                            <tr>
                                                <td><span class="round"><i class="ti-shopping-cart"></i></span></td>
                                                <td>
                                                    <h6>{{ $item['name'] }}</h6>
                                                </td>
                                                <td>
                                                    <h5>{{ $item['sold'] }}</h5>
                                                </td>
                                                <td>
                                                    <h5>{{ $item['returned'] }}</h5>
                                                </td>
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
                <div class="col-xlg-6 col-lg-12">
                    <div class="row">
                        <!-- Column -->
                        <div class="col-md-6 col-sm-12">
                            <div class="card shadow">
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
                            <div class="card shadow">
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
                            <div class="card shadow">
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
                            <div class="card shadow">
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
                <div class="card shadow">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <h3 class="m-0">Repartos de <b>{{ $route->User->name }}</b> para el <b>{{ $diasSemana[$route->day_of_week] }}</b></h3>
                        @if (auth()->user()->rol_id == '1')
                        <button type="button" id="btnDeleteRoute" class="btn btn-sm btn-danger btn-rounded px-3">Eliminar reparto</button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if (($route->is_static === false && auth()->user()->rol_id == '2') || $route->is_static === true)
                            <div class="d-flex flex-row justify-content-end">
                                <a class="btn btn-info btn-rounded float-right" href="{{ url('/route/' . $route->id . '/newCart') }}">Fuera de reparto</a>
                            </div>
                        @endif
                        @if ($route->is_static === false && auth()->user()->rol_id == '1')
                            <div class="d-flex flex-row justify-content-end">
                                <a class="btn btn-info btn-rounded float-right" href="{{ url('/route/' . $route->id . '/newManualCart') }}">Agregar venta manual</a>
                            </div>
                        @endif
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

                                            {{-- Mostrar las duedas de cada cliente --}}
                                            @if ($cart->Client->debt == 0)
                                                    <p class="m-0"><small class="text-muted">Sin deuda</small></p>
                                            @elseif ($cart->Client->debt > 0)
                                                    <p class="m-0"><small class="text-danger">Deuda: ${{ $cart->Client->debt }}</small></p>
                                                @if ($cart->Client->debtMonth == 0)
                                                    <p class="m-0"><small class="text-muted">Sin deuda contraída este mes</small></p>
                                                @elseif($cart->Client->debtMonth > 0)
                                                    <p class="m-0"><small class="text-danger">Deuda contraída este mes: ${{ $cart->Client->debtMonth }}</small></p>
                                                @else
                                                    <p class="m-0"><small class="text-success">Entregó este mes: ${{ $cart->Client->debtMonth * -1 }}</small></p>
                                                @endif
                                            @else
                                                    <p class="m-0"><small class="text-success">Saldo a favor: ${{ $cart->Client->debt * -1 }}</small></p>
                                                @if ($cart->Client->debtMonth == 0)
                                                    <p class="m-0"><small class="text-muted">Sin deuda contraída este mes</small></p>
                                                @elseif($cart->Client->debtMonth > 0)
                                                    <p class="m-0"><small class="text-danger">Deuda contraída este mes: ${{ $cart->Client->debtMonth }}</small></p>
                                                @else
                                                    <p class="m-0"><small class="text-success">Entregó este mes: ${{ $cart->Client->debtMonth * -1 }}</small></p>
                                                @endif
                                            @endif

                                            <p class="mb-0"><small class="text-muted"><i class="bi bi-house-door"></i> {{ $cart->Client->adress }}&nbsp;&nbsp;-&nbsp;&nbsp;<i class="bi bi-telephone"></i> {{ $cart->Client->phone }}</small></p>
                                            @if ($cart->state && auth()->user()->rol_id == '1')
                                            <p class="mb-0"><small class="text-muted"><i class="bi bi-calendar-check"></i> {{ $cart->updated_at->format('d-m-Y H:i') }}&nbsp;hs. </small></p>
                                            @endif
                                        </div>
                                        @if ($cart->state === 1)
                                        <hr>
                                        @endif
                                        <div class="timeline-body">
                                            @if ($cart->state === 1)
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                    @if ($cart->ProductsCart->count() > 0)
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
                                                    @endif
                                                    </div>
                                                    @if (isset($cart->AbonoClient))
                                                    <div class="col-lg-12">
                                                        <strong class="m-0"> Renovación {{$cart->AbonoClient->Abono->name }}</strong>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-md-row flex-column justify-content-end">
                                                    @if (auth()->user()->rol_id == 2)
                                                        <button type="button" onclick="getReturnStock('{{ $cart->Client->id }}', '{{ $cart->Client->name }}', '{{ $cart->id }}')" class="btn btn-sm btn-info btn-rounded px-3 mb-2 mb-md-0 ml-auto ml-md-0">Devuelve</button>
                                                    @endif
                                                    <button type="button" onclick="editCart({{ $cart }})" class="btn btn-sm btn-info btn-rounded px-3 ml-auto ml-md-2">Editar Bajada</button>
                                                </div>
                                                <hr>
                                                <div class="d-flex flex-row justify-content-start">
                                                    <p class="m-0">Total del pedido: $
                                                        {{ $cart->ProductsCart->sum(function($product_cart) {
                                                        return $product_cart->setted_price * $product_cart->quantity;
                                                        }) + ($cart->AbonoClient ? $cart->AbonoClient->setted_price : 0) }}
                                                    </p>
                                                </div>
                                            @endif

                                            @if ($cart->state === 0)
                                                <div class="d-flex flex-row justify-content-end">

                                                    {{-- 2 = employee --}}
                                                    @if (auth()->user()->rol_id == '2')
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                            <div class="dropdown-menu">
                                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal({{ $cart->id }}, {{ $cart->Client->id }}, {{ $cart->Client->debt }})"><b>Bajar</b></button>
                                                                <div class="dropdown-divider"></div>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(2, {{ $cart->id }}, 'no estaba')">No estaba</button>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(3, {{ $cart->id }}, 'no necesitaba')">No necesitaba</button>
                                                                <button class="dropdown-item" type="button" style="cursor: pointer;" onclick="sendStateChange(4, {{ $cart->id }}, 'estaba de vacaciones')">Vacaciones</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Resetear estado del carrito (no confirmado) --}}
                                            @if ($cart->is_static === false && $cart->state != 1 && $cart->state != 0)
                                                <hr>
                                                <div class="d-flex flex-row justify-content-end">
                                                    {{-- Reset cart state --}}
                                                    <form id="formResetCartState_{{ $cart->id }}" action="{{ url('/cart/changeState') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $cart->id }}">
                                                        <input type="hidden" name="state" value="0">
                                                        <button name="btnResetCartState" value="{{ $cart->id }}" type="button" class="btn btn-sm btn-danger btn-rounded px-3">Cancelar estado</button>
                                                    </form>
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

    <script>
        const SwalAlert = (text) => {
            Swal.fire({
                icon: 'warning',
                title: 'ALERTA',
                text: text,
                showCancelButton: false,
                confirmButtonColor: '#1e88e5',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            });
            return false;
        };
        const SwalError = (text) => {
            Swal.fire({
                icon: 'error',
                title: text,
                confirmButtonColor: '#1e88e5',
            });
        };
    </script>

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
                    SwalError(errorThrown.responseJSON.message);
                }
            });
        });
    </script>

    {{-- Productos que devuelve un cliente --}}
    <script>
        function searchProductsClient() {
            $("#client_id").val($("#selectClientToReturn").val());
            $("#table_products_client table tbody").html("");
            $.ajax({
                url: $("#form_search_products").attr('action'), // Utiliza la ruta del formulario
                method: $("#form_search_products").attr('method'), // Utiliza el método del formulario
                data: $("#form_search_products").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#table_products_client").css('display', 'block');
                    let content = "";
                    response.products.forEach(product => {
                        content += `
                            <tr data-id="${product.product_id}">
                                <td>${product.product.name}</td>
                                <td><input type="number" class="form-control" min="0" max="10000"></td>
                            </tr>
                        `;
                    });
                    $("#table_products_client table tbody").html(content);
                },
                error: function(errorThrown) {
                    SwalError(errorThrown.responseJSON.message);
                }
            });
        }

        $("#selectClientToReturn").on("change", function() {
            searchProductsClient();
        });

        $("#btnUpdateProductsReturned").on("click", function() {
            // Productos
            let products = [];
            $('#modalProductsReturned table tbody tr').each(function() {
                let productId = $(this).data('id');
                let quantity = $(this).find('input').val();
                if (quantity !== "") {
                    products.push({
                        product_id: productId,
                        quantity: quantity
                    });
                }
            });
            $("#formProductsReturned input[name='products_quantity']").val(JSON.stringify(products));

            $.ajax({
                url: $("#formProductsReturned").attr('action'), // Utiliza la ruta del formulario
                method: $("#formProductsReturned").attr('method'), // Utiliza el método del formulario
                data: $("#formProductsReturned").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnCloseModalProductsReturned").click();

                },
                error: function(errorThrown) {
                    SwalError(errorThrown.responseJSON.message);
                }
            });
        });

        function getReturnStock(client_id, client_name, cart_id) {
            $("#tableBodyReturnedByClient").html("");
            $.ajax({
                url: "{{ url('/client/products/') }}" +"/"+ client_id,
                type: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cart_id: cart_id,
                },
                success: function(response) {
                    $("#modalProductsReturnedByClient").modal("show");
                    $("#modalProductsReturnedByClient .modal-title").text("Cargar devolución " + client_name);
                    let cont = "";
                    if (response.data.bottle) {
                    response.data.bottle.forEach(function(bottle) {
                        cont += '<tr>';
                        cont += '<td><input type="hidden" class="form-control" id="bottle_id_' + bottle.id + '" value="' + bottle.log_id + '">' + bottle.name + '</td>';
                        cont += '<td><input type="number" class="form-control" id="bottle-' + bottle.id + '" value="' + bottle.stock + '" min="0" max="10000"></td>';
                        cont += '<td><button type="button" onclick="returnProduct($(\'#bottle_id_' + bottle.id + '\').val(), false ,'+ bottle.id +','+ response.data.cart_id + ', $(\'#bottle-' + bottle.id + '\').val())" class="btn btn-success waves-effect waves-light"><i class="bi bi-arrow-repeat"></i></button></td>'
                        cont += '</tr>';
                    });
                    }
                    if (response.data.products){
                    response.data.products.forEach(function(product) {
                        cont += '<tr>';
                        cont += '<td><input type="hidden" class="form-control" id="product_id_' + product.id + '" value="' + product.log_id + '">' + product.name + '</td>';
                        cont += '<td><input type="number" class="form-control" id="product-' + product.id + '" value="' + product.stock + '" min="0" max="10000"></td>';
                        cont += '<td><button type="button" onclick="returnProduct($(\'#product_id_' + product.id + '\').val(), true,'+ product.id +','+ response.data.cart_id + ', $(\'#product-' + product.id + '\').val())" class="btn btn-success waves-effect waves-light"><i class="bi bi-arrow-repeat"></i></button></td>'
                        cont += '</tr>';
                    });
                    }
                    $("#tableBodyReturnedByClient").html(cont);
                },
                error: function(errorThrown) {
                    SwalError(errorThrown.responseJSON.message);
                }
            });
        }
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
                        quantity: quantity,
                    });
                }
            });
            $("#form-confirm input[name='products_quantity']").val(JSON.stringify(products));

            // Métodos de pago
            $("#form-confirm input[name='cash']").val($("#cash_input").val());

            discountAbono()
            .then(() => {
                $.ajax({
                    url: "{{ url('/cart/confirm') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $("#form-confirm").serialize(),
                    success: function(response) {
                        $("#btnCloseModal").click();
                        Swal.fire({
                            title: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#1e88e5',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    },
                    error: function(errorThrown) {
                        console.log(errorThrown);
                        console.log(data);
                       // SwalError(errorThrown.responseJSON.message);
                    }
                });
            })
            .catch((error) => {
                SwalError(errorThrown.responseJSON.message);
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
            })
        });

        $("input[type='number']").on("input", function() {
            if ($(this).val() < 0 || !esNumero($(this).val())) {
                $(this).val("");
            }
        });
    </script>

    <script>
        function esNumero(valor) {
            return /^\d+$/.test(valor);
        }
        let totalRenewAbono = 0;
        // Pegada AJAX que busca los productos del carrito seleccionado y completa el modal
        function fillModal(data) {
            $("#colAbono").empty();
            let content = "";
            data.products.forEach(p => {
                content += '<tr>';
                content += '<td>' + p.product.name + '</td>';
                content += '<td class="precioProducto">$ ' + p.product.price + '</td>';
                content += '<td><input type="number" min="0" max="1000" class="form-control quantity-input" data-id="' + p.product.id + '" ></td>';
                content += "</tr>";
            });
            $("#tableBody").html(content);
            if (data.abonoClient !== null) {
                //Abono corriente disponible para descontar
                let cont = "";
                cont += '<input type="hidden" name="abono_id" value="'+ data.abonoClient.id +'">';
                cont += '<div class="table-responsive"><table class="table"><thead><tr>';
                cont += '<th>Abono</th>';
                cont += '<th>Disponible</th>';
                cont += '<th>Baja</th></tr>';
                cont += '</thead><tr>';
                cont += '<td>' + data.abonoType.name + ' $' + data.abonoType.price + '</td>';
                if (data.abonoClient.available === 0) {
                cont += '<td>no disponible</td>';
                } else {
                cont += '<td>' + data.abonoClient.available + '</td>';
                cont += '<td><input type="number" class="form-control" min="0" max="' + data.abonoClient.available + '" id="dump_truck" value="0"></td>';
                }
                cont += '</tr>';
                cont += '</tbody></table><hr></div>';
                $("#colAbono").html(cont);
            }else if (data.client_abono_id !== null){
                //Renovacion de Abono
                let cont = "";
                cont += '<div class="table-responsive"><table class="table"><thead><tr>';
                cont += '<th>Abono</th>';
                cont += '<th>Disponible</th>';
                cont += '<th></th></tr>';
                cont += '</thead><tr>';
                cont += '<td>' + data.abonoType.name + ' $' + data.abonoType.price + '</td>';
                cont += '<td></td>';
                cont += '<td><button type="button" class="btn btn-success waves-effect waves-light" onclick="renewAbono('+ data.abonoType.id +','+ data.abonoType.price +','+ data.abonoType.client_id +')">Renovar Abono</button></td>';
                cont += "</tr>";
                cont += '</tbody></table><hr></div>';
                $("#colAbono").html(cont);
            }

            $(".quantity-input").on("input", function() {
                total = 0 + totalRenewAbono; // Reiniciar el valor total a cero en cada iteración

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
                    fillModal(response);
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
                            SwalError(errorThrown.responseJSON.message);
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
                            SwalError(errorThrown.responseJSON.message);
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
                            SwalError(errorThrown.responseJSON.message);
                        }
                    });
                }
            })
        }

        $("button[name='btnResetCartState']").on("click", function() {
            let id = $(this).val();
            Swal.fire({
                title: "Esta acción no se puede revertir",
                text: '¿Seguro deseas resetear el estado del reparto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger waves-effect waves-light px-3 py-2',
                    cancelButton: 'btn btn-default waves-effect waves-light px-3 py-2'
                }
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $("#formResetCartState_" + id).attr('action'), // Utiliza la ruta del formulario
                        method: $("#formResetCartState_" + id).attr('method'), // Utiliza el método del formulario
                        data: $("#formResetCartState_" + id).serialize(), // Utiliza los datos del formulario
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
                            SwalError(errorThrown.responseJSON.message);
                        }
                    });
                }
            })
        });
    </script>

    {{-- Añadir cantidad de productos cargados --}}
    <script>
        $(document).ready(function() {
            // Evento click para el botón "Añadir"
            $(document).on('click', '.btn-add-quantity', function() {
                let row = $(this).closest('tr'); // Obtener la fila padre del botón
                let additionalQuantity = parseFloat(row.find('.additional-quantity').val()); // Obtener la cantidad adicional
                let quantityInput = row.find('input[name="quantity_dispatched"]'); // Obtener el campo de entrada de la cantidad
                let currentQuantity = parseFloat(quantityInput.val()); // Obtener la cantidad actual

                let newQuantity = currentQuantity + additionalQuantity; // Calcular la nueva cantidad

                quantityInput.val(newQuantity); // Actualizar el campo de entrada con la nueva cantidad
                row.find('.additional-quantity').val(0);
            });

            // Restricción para evitar valores negativos en el campo de cantidad adicional
            $(document).on('input', '.additional-quantity', function() {
                let value = parseFloat($(this).val());
                if (value < 0) {
                    $(this).val(0);
                }
            });
        });
    </script>

    {{-- Abonos --}}
    <script>
        function renewAbono(abono_id,abono_price,client_id) {
            Swal.fire({
            title: '¿Desea renovar abono?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText : 'Cancelar'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    totalRenewAbono += abono_price;
                    $('input[name="renew_abono"]').val(abono_price);
                    $("#totalAmount").html("Total pedido: $" + totalRenewAbono);
                    $.ajax({
                        url: "{{ url('/abono/renew') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            abono_id: abono_id,
                            client_id: client_id,
                            cart_id: $("input[name='cart_id']").val(),
                        },
                        success: function(response) {
                            let cont = "";
                            cont += '<input type="hidden" name="abono_id" value="'+ response.data.abonoClient.id +'">';
                            cont += '<div class="table-responsive"><table class="table"><thead><tr>';
                            cont += '<th>Abono</th>';
                            cont += '<th>Disponible</th>';
                            cont += '<th>Baja</th></tr>';
                            cont += '</thead><tr>';
                            cont += '<td>' + response.data.abonoType.name + ' $' + response.data.abonoType.price + '</td>';
                            if (response.data.abonoClient.available === 0) {
                            cont += '<td>no disponible</td>';
                            } else if (response.client_abono_id !== null){
                            cont += '<td>' + response.data.abonoClient.available + '</td>';
                            cont += '<td><input class="form-control" type="number" min="0" max="' + response.data.abonoClient.available + '" id="dump_truck" value="0"></td>';
                            }
                            cont += '</tr>';
                            cont += '</tbody></table><hr></div>';
                            $("#colAbono").html(cont);
                        },
                        error: function(errorThrown) {
                            SwalError(errorThrown.responseJSON.message);
                        }
                    });
                }
            });
        }

        function discountAbono() {
            return new Promise((resolve, reject) => {
                let abono_id = $("input[name='abono_id']").val();
                let discount = $("#dump_truck").val();
                resolve();
                if (abono_id !== undefined && discount !== undefined) {
                    $.ajax({
                        url: "{{ url('/abono/discount') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            abono_id: abono_id,
                            discount: discount,
                        },
                        success: function(response) {
                            resolve();
                        },
                        error: function(errorThrown) {
                            SwalError(errorThrown.responseJSON.message);
                            reject(errorThrown);
                        }
                    });
                } else {
                    resolve();
                }
            });
        }
    </script>

    <script>
        // Calcular el total del carrito
        function calculateTotal() {
            let total = 0;
            $(".cart-items tr").each(function() {
                let precioUnit = parseFloat($(this).find(".precioProducto").text().replace("$ ", ""));
                let cantidad = parseInt($(this).find(".quantityedit-input").val());
                let subtotal = precioUnit * cantidad;
                total += subtotal;
            });
            return total;
        }
        // METODO EDITAR CARRITO
        function editCart(cart) {
            $("#form-edit-bajada input[name='cart_id']").val(cart.id);
            calculateTotal();
            console.log(cart);
            $("#modalEditCart").modal("show");
            let content = '';
            cart.products_cart.forEach(p => {
                content += '<tr>';
                content += '<td>' + p.product.name + '</td>';
                content += '<td class="precioProducto">$ ' + p.product.price + '</td>';
                content += '<td><input type="number" min="0" max="1000" class="form-control quantityedit-input" value="' + p.quantity + '" data-id="' + p.product.id + '" ></td>';
                content += "</tr>";
            });

            // Actualizar el total al modificar la cantidad
            $(document).ready(function() {
                function updateTotal() {
                    let total = calculateTotal();
                    $("#totalAmountEditCart").text("Total carrito: $" + total);
                }
                updateTotal();
                $(".quantityedit-input").on("input", updateTotal);
            });

            if (cart.cart_payment_method && cart.cart_payment_method.length > 0) {
                $("#cash_input_edit_cart").val(cart.cart_payment_method[0].amount || '0');
            } else {
                $("#cash_input_edit_cart").val('0');
            }


            $("#tableEditCart").html(content);

            $.ajax({
                url: "{{ url('/abono/getlog') }}",
                type: "GET",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cart_id: cart.id,
                },

                success: function(response) {
                    if (response.data !== null) {
                        console.log(response);
                        let cont = "";
                        cont += '<input type="hidden" name="abono_log_id_edit" value="'+ response.data.id +'">';
                        cont += '<div class="table-responsive"><table class="table"><thead><tr>';
                        cont += '<th>Abono</th>';
                        cont += '<th>Disponia</th>';
                        cont += '<th>Bajo</th></tr>';
                        cont += '</thead><tr>';
                        cont += '<td>' + response.data.name + ' $' + response.data.abonoclient.setted_price + '</td>';
                        cont += '<td>' + response.data.available + '</td>';
                        cont += '<td><input class="form-control" type="number" min="0" max="' + response.data.available + '" id="abono_log_quantity_edit" value="' + response.data.quantity + '"></td>';
                        cont += '</tr>';
                        cont += '</tbody></table><hr></div>';

                        $("#colAbonoEdit").html(cont);
                    }
                },
            })

        }

        $("#btnEditCart").on("click", function() {
            let cart_id = $("#form-edit-bajada input[name='cart_id']").val();
            let cash = $("#cash_input_edit_cart").val();
            let products = [];
            $(".quantityedit-input").each(function() {
                let product_id = $(this).data("id");
                let quantity = $(this).val();
                products.push({
                    product_id: product_id,
                    quantity: quantity
                });
            });

            let abono_log_quantity_edit = $("#abono_log_quantity_edit").val();
            let abono_log_id_edit = $("#abono_log_id_edit").val();
            $.ajax({
                url: "{{ url('/cart/edit') }}",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    cart_id: cart_id,
                    cash: cash,
                    products_quantity: JSON.stringify(products),
                    abono_log_id_edit: abono_log_id_edit ? abono_log_id_edit : null,
                    abono_log_quantity_edit: abono_log_quantity_edit ? abono_log_quantity_edit : null,
                },
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
        });
    </script>

    <script>
        function returnProduct(id, product, type_id, cart_id, quantity) {
            // acá el type_id puede ser
            $.ajax({
                url: "{{ url('/cart/return') }}",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    product: product,
                    type_id: type_id,
                    quantity: quantity,
                    cart_id: cart_id,
                },
                success: function(response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'bottom-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    if (response.data !== null) {
                        if (product === true) {
                            $(`#product_id_${type_id}`).val(response.data.id);
                        } else{
                            $(`#bottle_id_${type_id}`).val(response.data.id);
                        }
                    }
                },
                error: function(errorThrown) {
                    SwalError(errorThrown.responseJSON.message);
                }
            });
        }
    </script>
@endsection
