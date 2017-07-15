@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios])

@section('content')

    <!-- parte alta de la pagina despues de la barra de menus-->
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>Servicios</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route('tienda.index')}}">Inicio</a>
                        </li>
                        <li>Servicios</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- parte central donde esta la descripcion y las imagenes-->
    <div id="content">
        <div class="container">

            <section>

                <div class="row">
                    <div class="col-md-12">
                        <div class="heading">
                            <h2>Nuestros Servicios</h2>
                        </div>

                        <p class="lead">Lista de todos los servicios que se ofrecen dentro de la empresa</p>
                    </div>
                </div>

                <div class="row portfolio no-space" >

                    @foreach($servicios as $ser)

                    <div class="col-sm-4">
                        <div class="box-image">
                            <div class="image">
                                <img src="{{asset('img/servicios/'.$ser->img)}}" alt=""height="300px">
                            </div>
                            <div class="bg"></div>
                            <div class="name">
                                <h3><a href="portfolio-detail.html">{{$ser->title}}</a></h3>
                            </div>
                            <div class="text">
                                <p class="hidden-sm hidden-lg hidden-md">{{$ser->shortdescription}}</p>
                                <p class="buttons">
                                    <a href="{{route('tienda.detalleServicio',$ser->id)}}" class="btn btn-template-transparent-primary">
                                    <i class="fa fa-link"></i> Ver mas</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.box-image -->
                    </div>
                    @endforeach

                </div>

            </section>

        </div>
        <!-- /#contact.container -->
    </div>

@endsection
