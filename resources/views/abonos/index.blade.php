@extends('layouts.app')

@section('content')
    <!-- Modal -->
    <div id="modalConfirmation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form role="form" class="needs-validation" method="POST" action="{{ url('/abono/updatePrice') }}" id="form-edit" autocomplete="off" novalidate>
                @csrf
                <input type="hidden" id="abono_id" name="abono_id" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar abono</h4>
                        <button type="button" class="close" id="btnCloseModal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                @csrf
                                <input type="hidden" name="id" id="abonoID">
                                <div class="form-column">
                                    <div class="col-12 mb-3">
                                        <label for="abonoPrice" class="mb-0">Precio</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" max="1000000" class="form-control" id="abonoPrice" name="price" place
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

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Abonos</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Abonos </li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="d-flex m-t-10 justify-content-end">
                    <div class="d-flex m-r-20 m-l-10">
                        <a class="btn btn-info waves-effect waves-light" href="{{ route('abono.clientes') }}">Ver abonos de clientes</a>
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
                    @foreach ($abonos as $abono)
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="ribbon-wrapper card shadow">
                                <div class="ribbon ribbon-default ribbon-bookmark" id="abonoName{{ $abono->id }}">{{ $abono->name }}</div>
                                <div class="my-2">
                                    <p class="ribbon-content" id="abonoPrice{{ $abono->id }}">Precio: ${{ $abono->price }}</p>
                                </div>
                                <button type="button" class="btn btn-outline-info btn-rounded mr-4 waves-effect waves-light" onclick="openModal({{ json_encode($abono) }})" data-toggle="modal" data-target="#modalConfirmation">
                                    Editar precio <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================= -->

    <script>
        function openModal(item) {
            //Edit product
            $("#abono_id").val(item.id);
            $("#abonoPrice").val(item.price);
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
                            createAbono();
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

        function updatedSuccess(response) {
            $("#btnCloseModal").click();

            let id = $("#abono_id").val();
            $("#abonoPrice" + id).html("Precio: $" + $("#abonoPrice").val());

            Swal.fire({
                icon: 'success',
                title: response.message,
                confirmButtonColor: '#1e88e5',
            });
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
