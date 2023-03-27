@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">Productos</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/products/index') }}">Productos</a></li>
                <li class="breadcrumb-item active">Editar</li>
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
            <div class="card sombra">
                <div class="card-body">
                    <h4 class="card-title">Editar producto</h4>
                    <form class="needs-validation form" method="POST" action="" novalidate>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="productName">Nombre</label>
                                <input type="text" class="form-control form-control-line" id="productName" placeholder="Nombre del producto" required>
                                <div class="valid-feedback">
                                    Válido!
                                </div>
                                <div class="invalid-feedback">
                                    Por favor, ingrese un nombre
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="productStock">Stock</label>
                                <input type="number" class="form-control form-control-line" min="0" max="1000000" id="productStock" placeholder="X" required>
                                <div class="valid-feedback">
                                    Válido!
                                </div>
                                <div class="invalid-feedback">
                                    Por favor, ingrese un stock
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="productPrice">Precio</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0" max="1000000" class="form-control form-control-line" id="productPrice" place aria-describedby="inputGroupPrepend" required>
                                    <div class="valid-feedback">
                                        Válido!
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor, ingrese un precio
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection