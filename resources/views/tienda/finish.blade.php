@extends('layouts.tienda')
<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1></h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route("tienda.index")}}">Inicio</a>
                        </li>
                        <li>Orden</li>
                        <li>Pedido Finalizado</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">
            <h1>¡ Pedido Completado !</h1>
            <hr>
            <p> Tu pedido ha sido registrado con el numero de orden: <b>{{$summary}}</b> </p>
            <p> En breve uno de nuestros operadores se comunicará con usted para continuar con el proceso </p>
            <p> La llama se realizará entre las 8:00 - 19:00 de hoy </p>
        </div>
        <!-- /.container -->
    </div>
@endsection

