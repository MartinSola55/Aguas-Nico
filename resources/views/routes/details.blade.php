@extends('layouts.app')

@section('content')
    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form>
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
                                        <tbody>
                                            <tr>
                                                <td>3</td>
                                                <td>Botella de agua 1L</td>
                                                <td class="precioProducto">$1500</td>
                                                <td>
                                                    <select class="form-control cantidadProducto">
                                                        <option value="0" selected>0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Bidón de agua</td>
                                                <td class="precioProducto">$3650</td>
                                                <td>
                                                    <select class="form-control cantidadProducto">
                                                        <option value="0" selected>0</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr/>
                                    <p id="totalAmount" class="mr-2">Total pedido: $0</p>
                                    <p>Deuda: $12500</p>
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
                    <li class="breadcrumb-item"><a href="{{ url('/routes/index') }}">Repartos</a></li>
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
                    <h3 class="card-header">Lunes 27/03/2023 - Martín Sola</h1>
                        <div class="card-body">
                            <ul class="timeline">
                                <li>
                                    <div class="timeline-badge danger"><i class="bi bi-truck"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">Samuelson - Deuda: $12500</h4>
                                            <p><small class="text-muted"><i class="bi bi-house-door"></i> Mendoza
                                                    379</small></p>
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
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>Botella de agua 1L</td>
                                                                    <td>$1500</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>7</td>
                                                                    <td>Bidón de agua</td>
                                                                    <td>$3650</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>6</td>
                                                                    <td>Botella de agua 3L</td>
                                                                    <td>$4200</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <p><b>Observaciones:</b> Lorem ipsum dolor sit amet, consectetur adipisicing
                                                elit. Deserunt obcaecati, quaerat tempore officia.</p>
                                            <hr>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                <div class="dropdown-menu">
                                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal()"><b>Confirmar</b></button>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">No estaba</a>
                                                    <a class="dropdown-item" href="#">No necesitaba</a>
                                                    <a class="dropdown-item" href="#">Vacaciones</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-inverted">
                                    <div class="timeline-badge danger"><i class="bi bi-truck"></i></div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title">Samuelson - Deuda: $12500</h4>
                                            <p><small class="text-muted"><i class="bi bi-house-door"></i> Mendoza
                                                    379</small></p>
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
                                                                <tr>
                                                                    <td>3</td>
                                                                    <td>Botella de agua 1L</td>
                                                                    <td>$1500</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>7</td>
                                                                    <td>Bidón de agua</td>
                                                                    <td>$3650</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>6</td>
                                                                    <td>Botella de agua 3L</td>
                                                                    <td>$4200</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <p><b>Observaciones:</b> Lorem ipsum dolor sit amet, consectetur adipisicing
                                                elit. Deserunt obcaecati, quaerat tempore officia.</p>
                                            <hr>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">Acción</button>
                                                <div class="dropdown-menu">
                                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;" onclick="openModal()"><b>Confirmar</b></button>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">No estaba</a>
                                                    <a class="dropdown-item" href="#">No necesitaba</a>
                                                    <a class="dropdown-item" href="#">Vacaciones</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Calcular el total dentro del modal --}}
    <script>
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
    </script>

    <script>
        function openModal() {
            $("#debtCheckbox").prop("checked", false);
            $("#paymentInput").css("display", "none");
            $("#paymentInput input").prop("disabled", true);
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
    </script>
@endsection
