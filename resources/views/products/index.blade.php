@extends('layouts.app')

@section('content')
    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/product/edit') }}" id="form-edit" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar producto</h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                <input type="hidden" name="id" id="productID">
                                <div class="form-column">
                                    <div class="col-12 mb-3">
                                        <label for="productName" class="mb-0">Nombre</label>
                                        <input type="text" class="form-control" id="productName" name="name"
                                            placeholder="Nombre del producto" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese un nombre
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="productStock" class="mb-0">Stock</label>
                                        <input type="number" class="form-control" min="0" max="1000000" id="productStock" name="stock" placeholder="X" required>
                                        <div class="valid-feedback">
                                        </div>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese un stock
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="productPrice" class="mb-0">Precio</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="productPrice" name="price" place
                                                aria-describedby="inputGroupPrepend" required>
                                            <div class="valid-feedback">
                                            </div>
                                            <div class="invalid-feedback">
                                                Por favor, ingrese un precio
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">Actualizar</button>
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
                <h3 class="text-themecolor m-b-0 m-t-0">Productos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Productos </li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                        <div>
                            <a class="btn btn-primary waves-effect waves-light" href="{{ url('/product/create') }}">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row el-element-overlay">
            @foreach ($products as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="el-card-item">
                            <div class="el-card-avatar el-overlay-1"> <img src="{{ asset('images/botella.jpg') }}"
                                    alt="Botella" />
                                <div class="el-overlay">
                                    <ul class="el-info">
                                        <li>
                                            <a class="btn default btn-outline" href="{{ url('/products/stats') }}">
                                                <i class="bi bi-graph-up"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="btn default btn-outline" onclick="openModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }}, {{ $product->price }})" data-toggle="modal" data-target="#modalConfirmation">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="el-card-content">
                                <h3 class="box-title">{{ $product->name }}</h3> <small>{{ $product->stock }} en stock</small>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================= -->

    <script>
        function openModal(id, name, stock, price) {
            $("#productID").val(id);
            $("#productName").val(name);
            $("#productStock").val(stock);
            $("#productPrice").val(price);
            $("#form-edit").removeClass("was-validated");
            /*$.ajax({
                url: "/product/getOne",
                method: "GET",
                data: id, 
                success: function(response) {
                    $("#productName").val(response.name);
                    $("#productStock").val(response.stock);
                    $("#productPrice").val(response.price);
                },
                error: function(errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: errorThrown.responseJSON.message,
                    });
                }
            });*/
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
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#form-edit').on('submit', function(event) {
                event.preventDefault(); // Prevenir la acción predeterminada del formulario

                // Enviar solicitud AJAX
                $.ajax({
                    url: $(this).attr('action'), // Utiliza la ruta del formulario
                    method: $(this).attr('method'), // Utiliza el método del formulario
                    data: $(this).serialize(), // Utiliza los datos del formulario
                    success: function(response) {
                        $("#btnCloseModal").click();
                        Swal.fire(
                            'Todo piola gato',
                            'Se agregó correctamente',
                            'success'
                        );
                    },
                    error: function(errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: errorThrown.responseJSON.message,
                        });
                    }
                });
            });
        });
    </script>
@endsection
