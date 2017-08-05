@extends('layouts.tienda')
<!-- Index -->
@section('content')
    <div id="content">
        <div class="container">

            <div class="col-sm-6 col-sm-offset-3" id="error-page">

                <div class="box">

                    <p class="text-center">
                        <a href="{{route('tienda.index')}}">
                            <img src="{{asset("/img/minilogo.png")}}" alt="Herramientas y Servicios de Tepic">
                        </a>
                    </p>

                    <h3>Lo sentimos, la pagina que solicitaste no existe</h3>
                    <h4 class="text-muted">Error 404 - Pagina no encontrada</h4>

                    <p class="buttons"><a href="{{route('tienda.index')}}" class="btn btn-template-main"><i class="fa fa-home"></i> Ir a la tienda</a>
                    </p>
                </div>


            </div>
            <!-- /.col-sm-6 -->
        </div>
        <!-- /.container -->
    </div>
@endsection
