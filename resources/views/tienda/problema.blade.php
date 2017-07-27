@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios])

<!-- Index -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    Hubo un problema con tu activación </h2>
                <div class="error-details">
                    El error arrojó el siguiente codigo:{{$exception->getCode()}}
                </div>
                <div class="error-actions">
                    <a href="{{route('tienda.index')}}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Ir a la tienda  </a></div>
            </div>
        </div>
    </div>
</div>
    <br>
    <br>
    <br>
@endsection