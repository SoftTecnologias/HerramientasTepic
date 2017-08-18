@extends('layouts.tienda')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css"
      integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
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
