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
                <li class="breadcrumb-item active">Productos </li>
            </ol>
        </div>
        <div class="col-md-7 col-4 align-self-center">
            <div class="d-flex m-t-10 justify-content-end">
                <div class="d-flex m-r-20 m-l-10 hidden-md-down">
                    <div>
                    <a class="btn btn-primary waves-effect waves-light" href="{{ url('/products/create') }}">
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
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="{{ asset('images/botella.jpg') }}" alt="user" />
                        <a class="el-overlay" href="{{ url('/products/stats') }}"></a>
                    </div>
                    <div class="el-card-content">
                        <h3 class="box-title">Botella de agua</h3> <small>1 litro</small>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="{{ asset('images/botella grande.jpg') }}" alt="user" />
                        <a class="el-overlay" href="{{ url('/products/stats') }}"></a>
                    </div>
                    <div class="el-card-content">
                    <h3 class="box-title">Botella de agua</h3> <small>3 litros</small>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="{{ asset('images/bidon.jpg') }}" alt="user" />
                        <a class="el-overlay" href="{{ url('/products/stats') }}"></a>
                    </div>
                    <div class="el-card-content">
                        <h3 class="box-title">Bidón de agua</h3> <small>15 litros</small>
                        <br />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1"> <img src="{{ asset('images/botella.jpg') }}" alt="user" />
                        <a class="el-overlay" href="{{ url('/products/stats') }}"></a>
                    </div>
                    <div class="el-card-content">
                    <h3 class="box-title">Botella de agua</h3> <small>1 litro</small>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================= -->
@endsection