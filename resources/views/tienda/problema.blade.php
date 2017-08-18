@extends('layouts.tienda')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css"
      integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
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