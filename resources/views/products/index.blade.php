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
                        <div class="d-flex flex-row justify-content-end w-100">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal create -->
    <div id="modalCreateProduct" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/product/create') }}" id="form-create" autocomplete="off" novalidate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar producto</h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-column">
                                    {{-- TOKEN --}}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    
                                    <div class="col-12 mb-3">
                                        <label for="productName" class="mb-0">Nombre</label>
                                        <input type="text" class="form-control" id="productName" name="name" required>
                                        <div class="invalid-feedback">
                                            Por favor, ingrese un nombre
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="productPrice" class="mb-0">Precio</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="productPrice" name="price" placearia-describedby="inputGroupPrepend" required>
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
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="btnCloseCreate">Cerrar</button>
                        <button onclick="createProduct()" type="button" class="btn btn-success waves-effect waves-light">Agregar</button>
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
                            <button class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#modalCreateProduct">
                                <i class="bi bi-plus-lg"></i>
                            </button>
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
                            <div class="ribbon-wrapper card shadow">
                                <div class="ribbon ribbon-default ribbon-bookmark" id="productName{{ $product->id }}">{{ $product->name }}</div>
                                <div class="my-4">
                                    <p class="ribbon-content" id="productPrice{{ $product->id }}">Precio: ${{ $product->price }}</p>
                                </div>
                                <div class="d-flex flex-direction-row justify-content-between">
                                    <button type="button" class="btn btn-outline-info btn-rounded mr-4 waves-effect waves-light" onclick="openModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" data-toggle="modal" data-target="#modalConfirmation">
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
        function openModal(id, name, price) {
            //Delete product
            $("#product-id").val(id);

            //Edit product
            $("#productID").val(id);
            $("#productName").val(name);
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
                var forms = $('.needs-validation');
                forms.on('submit', function(event) {
                    event.preventDefault();
                    
                    var form = $(this);
                    if (form[0].checkValidity() === false) {
                        event.stopPropagation();
                    } else {
                        if (form.attr('id') == "form-edit") {
                            sendForm();
                        } else {
                            createProduct();
                        }
                    }
                    form.addClass('was-validated');
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
        
        function createProduct() {
            // Enviar solicitud AJAX
            $.ajax({
                url: $("#form-create").attr('action'), // Utiliza la ruta del formulario
                method: $("#form-create").attr('method'), // Utiliza el método del formulario
                data: $("#form-create").serialize(), // Utiliza los datos del formulario
                success: function(response) {
                    $("#btnCloseCreate").click();
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
