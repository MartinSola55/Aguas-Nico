@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0">Máquinas</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Máquinas </li>
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
                <div class="row">
                    @foreach ($machines as $machine)
                        <div class="col-lg-4 col-md-8 col-xlg-3 col-xs-12">
                            <div class="ribbon-wrapper card shadow">
                                <div class="ribbon ribbon-default ribbon-bookmark">{{ $machine->name }}</div>
                                <div class="my-2">
                                    <p class="ribbon-content" id="machine_price_{{ $machine->id }}">Precio: ${{ $machine->price }}</p>
                                </div>
                                <button type="button" class="btn btn-outline-info btn-rounded mr-4 waves-effect waves-light" onclick="editPrice({{ $machine->id }})">
                                    Editar precio <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form action="/machine/updatePrice" method="POST" id="form-edit">
            @csrf
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="price" id="price">
        </form>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================= -->

    <script>
        function editPrice(id) {
            Swal.fire({
                input: 'number',
                title: 'Precio',
                inputPlaceholder: 'Ingrese el nuevo precio',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-default waves-effect waves-light px-3 py-2',
                    cancelButton: 'btn btn-danger waves-effect waves-light px-3 py-2'
                },
                inputValidator: (value) => {
                    if (!value || value < 0) {
                        return 'Debes ingresar un precio'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#id").val(id);
                    $("#price").val(result.value);
                    sendForm();
                }
            });
        }

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
            $("#machine_price_" + response.data.id).text("Precio: $" + response.data.price);
            Swal.fire({
                icon: 'success',
                title: response.message,
                confirmButtonColor: '#1e88e5',
            });
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
