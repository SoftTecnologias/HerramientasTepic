@extends('layouts.tienda')

<!-- Index -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>
                       ¡Ahora eres cliente de Herramientas y Servicios de Tepic!</h1>
                    <div class="success">
                        Todo está preparado para empezar, no pierdas tiempo y da un vistazo a nuestros productos y servicios
                    </div>
                    <div class="actions">
                        <a href="{{route('tienda.index')}}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-tags"></span>
                            Ir a la tienda </a><a disabled href="{{route('tienda.index')}}" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-home"></span> Configurar mi domicilio </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
@endsection