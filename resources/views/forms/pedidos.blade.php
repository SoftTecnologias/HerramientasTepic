@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
@endsection
<!-- Marcas -->
@section('content')
    <div class="content-wrapper">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Pedidos
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-tag"> Pedidos</i></a></li>
            </ol>
        </section>

        <h1>Los pedidos se verán reflejados aquí</h1>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/admin/marcas.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection