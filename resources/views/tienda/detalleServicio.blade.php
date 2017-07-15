@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios, 'actual'=> $actual])


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
                            <p>Juan Perez</p>
                            <h4>Precio</h4>
                            <p>$400</p>
                            <h4>Duracion</h4>
                            <p>1:30 hrs Aproximadamente</p>
                            <h4>Horario</h4>
                            <p>8:00 - 19:00</p>
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