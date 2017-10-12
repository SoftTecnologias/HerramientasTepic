@extends('layouts.tienda')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css"
      integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
@section('content')
@foreach($actual as $a)
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>Detalle de Servicios</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route('tienda.index')}}">Inicio</a>
                        </li>
                        <li><a href="{{route('tienda.servicios')}}">Servicios</a>
                        </li>
                        <li>Detalle</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div id="content">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <h2>{{$a->title}}</h2>
                    </div>

                    <p class="lead">{{$a->shortdescription}}</p>
                </div>
            </div>
            <div class="row portfolio-project">

                <section>

                    <div class="col-sm-8">
                        <div class="project owl-carousel">
                            <div class="item">
                                <img src="{{asset('img/servicios/'.$a->img)}}" alt="" class="img-responsive">
                            </div>
                        </div>
                        <!-- /.project owl-slider -->

                    </div>

                    <div class="col-sm-4">
                        <div class="project-more">
                            <h4>Encargado</h4>
                            <p>{{$servicio_detail->encargado}}</p>
                            <h4>Precio</h4>
                            <p>{{$servicio_detail->precio_base}}</p>
                            <h4>Horario</h4>
                            <p>{{$servicio_detail->horario}}</p>
                        </div>
                    </div>

                </section>

                <section>

                    <div class="col-sm-12">

                        <div class="heading">
                            <h3>Descripcion del servicio</h3>
                        </div>

                        <p>{{$a->longdescription}}</p>
                    </div>
                </section>

            </div>

            <section>
                <div class="row portfolio">

                    <div class="col-md-12">
                        <div class="heading">
                            <h4><a href="{{route('tienda.servicios')}}">Mas Servicios</a></h4>
                        </div>
                    </div>
                    <input type="hidden" value="{{$i=0}}">

                </div>
            </section>

        </div>
        <!-- /.container -->


    </div>
    <!-- /#content -->

    <!-- *** GET IT ***
_________________________________________________________ -->

@endforeach

@endsection