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
                        <div class="d-flex flex-row justify-content-between w-100">
                            <div class="d-flex">
                                <button type="button" id="btnDeleteProduct" class="btn btn-outline-danger waves-effect waves-light">Eliminar</button>
                            </div>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                            </div>
                        </div>
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
                    <div class="d-flex m-r-20 m-l-10">
                        <div>
                            <a class="btn btn-info waves-effect waves-light" href="{{ url('/product/new') }}">
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
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-4 col-md-8 col-xlg-3 col-xs-12">
                            <div class="ribbon-wrapper card">
                                <div class="ribbon ribbon-default ribbon-bookmark" id="productName{{ $product->id }}">{{ $product->name }}</div>
                                <div class="my-4">
                                    <p class="ribbon-content" id="productStock{{ $product->id }}">Stock: {{ $product->stock }} u.</p>
                                    <p class="ribbon-content" id="productPrice{{ $product->id }}">Precio: ${{ $product->price }}</p>
                                </div>
                                <div class="d-flex flex-direction-row justify-content-between">
                                    <button type="button" class="btn btn-outline-info btn-rounded mr-4 waves-effect waves-light" onclick="openModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }}, {{ $product->price }})" data-toggle="modal" data-target="#modalConfirmation">
                                        Editar <i class="bi bi-pencil"></i>
                                    </button>
                                    <a class="btn btn-info btn-rounded waves-effect waves-light" href="{{ route('product.stats', ['id' => $product->id]) }}">
                                        Estadísticas <i class="bi bi-graph-up"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form id="formDeleteProduct" action="{{ url('/product/delete') }}" method="POST">
            @csrf
            <input type="hidden" id="product-id" name="id" value="">
        </form>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================= -->

    <script>
        $("#btnDeleteProduct").on("click", function() {
            Swal.fire({
                title: "Esta acción no se puede revertir",
                text: '¿Seguro deseas eliminar el producto?',
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
                        url: $("#formDeleteProduct").attr('action'), // Utiliza la ruta del formulario
                        method: $("#formDeleteProduct").attr('method'), // Utiliza el método del formulario
                        data: $("#formDeleteProduct").serialize(), // Utiliza los datos del formulario
                        success: function(response) {
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
                };
            });
        });
    </script>

    <script>
        function openModal(id, name, stock, price) {
            //Delete product
            $("#product-id").val(id);

            //Edit product
            $("#productID").val(id);
            $("#productName").val(name);
            $("#productStock").val(stock);
            $("#productPrice").val(price);
            $("#form-edit").removeClass("was-validated");
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
                url: $("#form-edit").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-edit").attr('method'), // Utiliza el método del formulario
                data: $("#form-edit").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    updatedSuccess(response);
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

        function updatedSuccess(response) {
            $("#btnCloseModal").click();

            let id = $("#productID").val();
            $("#productName" + id).html($("#productName").val());
            $("#productStock" + id).html("Stock: " + $("#productStock").val() + " u.");
            $("#productPrice" + id).html("Precio: $" + $("#productPrice").val());

            Swal.fire({
                icon: 'success',
                title: response.message,
                confirmButtonColor: '#1e88e5',
            });
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
