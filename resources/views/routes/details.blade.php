@extends('layouts.app')

@section('content')
<!-- Modal -->
<div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
    <form>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar productos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Guardar</button>
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
                <li class="breadcrumb-item"><a href="{{ url('/products/index') }}">Repartos</a></li>
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
                <h3 class="card-header">Lunes 27/03/2023</h1>
                    <div class="card-body">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-badge danger"><i class="bi bi-truck"></i></div>
                                <div class="timeline-panel">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title">Samuelson - Deuda: $12500</h4>
                                        <p><small class="text-muted"><i class="bi bi-house-door"></i> Mendoza 379</small> </p>
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
                                                                <td>12</td>
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
                                        <p><b>Observaciones:</b> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt obcaecati, quaerat tempore officia.</p>
                                        <hr>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-gear"></i> <span class="caret"></span> </button>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modalConfirmation" style="cursor: pointer;"><b>Confirmar</b></button>
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
                                        <p><small class="text-muted"><i class="bi bi-house-door"></i> Mendoza 379</small> </p>
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
                                                                <td>12</td>
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
                                        <p><b>Observaciones:</b> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt obcaecati, quaerat tempore officia.</p>
                                        <hr>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-gear"></i> <span class="caret"></span> </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"><b>Confirmar</b></a>
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
@endsection