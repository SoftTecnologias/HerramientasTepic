@extends('layouts.tienda')

<!-- Index -->
@section('content')
    <section>
        <!-- *** HOMEPAGE CAROUSEL ***
_________________________________________________________ -->

        <div class="home-carousel">

            <div class="dark-mask"></div>

            <div class="container">
                <div class="homepage owl-carousel">
                    @foreach($banner as $row)
                        <div class="item">
                            <div class="row">
                                <div class="col-sm-5 right">
                                    <h1>{{$row->titulo}}</h1>
                                    <p>{{$row->contenido}}</p>
                                </div>
                                <div class="col-sm-7">
                                    <img class="img-responsive" src="{{asset('img/banner/'.$row->img)}}" alt="">
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- /.project owl-slider -->
            </div>
        </div>

        <!-- *** HOMEPAGE CAROUSEL END *** -->
    </section>

    <section class="bar background-white">
        <div class="container">
            <div class="col-md-12">
                <div class="heading text-center">
                    <h2>Los productos mas vendidos</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-9">
                    <!-- Controls -->
                    <div class="controls pull-right hidden-xs">
                        <a class="left fa fa-chevron-left btn btn-primary" href="#carousel-productos"
                           data-slide="prev"></a><a class="right fa fa-chevron-right btn btn-primary"
                                                    href="#carousel-productos"
                                                    data-slide="next"></a>
                    </div>
                </div>
            </div>
            <div id="carousel-productos" class="carousel slide hidden-xs" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php $items = 0; $indice = 0?>
                    @foreach(array_chunk($productos,3) as $chunk)
                        <div class="item {{($items==0)? "active" : "" }}">
                            <div class="row">
                                @foreach($chunk as $producto)
                                    <div class="col-sm-4">
                                        <div class="col-item">
                                            <div class="photo">
                                                <img src="{{asset('img/productos/'.$producto->photo)}}"
                                                     class="img-responsive" alt="a"
                                                     style="width: 360px; height: 260px;"/>
                                            </div>
                                            <div class="info">
                                                <div class="row">
                                                    <div class="price col-md-11">
                                                        <h5>
                                                            {{$producto->name}}
                                                        </h5>
                                                        <p>{{$producto->shortdescription}}</p>
                                                        <h5 class="price-text-color">
                                                            {{isset($producto->price1)? "$".$producto->price1." ".$producto->currency : "Inicia sesión para verlos precios"}}</h5>
                                                    </div>
                                                </div>
                                                <div class="separator clear-left">
                                                    <p class="btn-add">
                                                        <i class="fa fa-shopping-cart"></i><a href="#"
                                                                                              class="hidden-sm">Agregar
                                                            al carrito</a></p>
                                                    <p class="btn-details">
                                                        <i class="fa fa-list"></i><a href="#" class="hidden-sm"
                                                                                     data-toggle="modal"
                                                                                     data-target="#product_view{{$indice+1}}">Más
                                                            detalles</a></p>
                                                </div>
                                                <div class="clearfix">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $indice++?>
                                @endforeach
                            </div>
                        </div>
                        <?php $items++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- /.bar -->
    <section class="bar background-white no-mb">
        <div class="container">

            <div class="col-md-12">
                <div class="heading text-center">
                    <h2>Nuestros Servicios</h2>
                </div>

                <p class="lead">Especialistas en herramientas y equipos, tanto para diagnósticos como hidráulicos,
                    eléctricos, neumáticos, a gasolina, escaleras de tijera, extensión, convertibles, manómetros,
                    filtros, reguladores y lubricadores, ruedas y rodajas, un extenso surtido en herramienta manual y
                    calzado industrial.
                    <span class="accent">¡Revisa nuestro catalogo de Servicios!</span>
                </p>

                <!-- *** BLOG HOMEPAGE ***
_________________________________________________________ -->

                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="box-image-text blog">
                            <div class="top">
                                <div class="image">
                                    <img src="http://i2.ytimg.com/vi/uToZS0kgsZw/mqdefault.jpg" alt=""
                                         class="img-responsive"> <!-- photo -->
                                </div>
                                <div class="bg"></div>
                                <div class="text">
                                    <p class="buttons">
                                        <a href="blog-post.html" class="btn btn-template-transparent-primary"><i
                                                    class="fa fa-link"></i> Ver mas</a>
                                    </p>
                                </div>
                            </div>
                            <div class="content">
                                <h4><a href="blog-post.html">Reparación de Motores electricos</a></h4> <!-- Titulo -->
                                <p class="intro">Reparación de este tipo de motores, usados comunmente dentro de otros
                                    aparatos, tales como lavadoras, refrigeradores, autos, etc.</p>
                                <!-- Descripcion corta -->
                                <p class="read-more"><a href="blog-post.html" class="btn btn-template-main">Continuar
                                        leyendo</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.box-image-text -->

                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="box-image-text blog">
                            <div class="top">
                                <div class="image">
                                    <img src="http://www.codesolar.com/Energia-Solar/Fotos/Bombas_agua_F_32_Pedrollo.jpg"
                                         alt="" class="img-responsive"> <!-- photo -->
                                </div>
                                <div class="bg"></div>
                                <div class="text">
                                    <p class="buttons">
                                        <a href="blog-post.html" class="btn btn-template-transparent-primary"><i
                                                    class="fa fa-link"></i> Ver mas</a>
                                    </p>
                                </div>
                            </div>
                            <div class="content">
                                <h4><a href="blog-post.html">Reparación de Bombas de agua</a></h4> <!-- Titulo -->
                                <p class="intro">Las bombas de agua, nos ayudan a bombear agua a aquellos lugares cuando
                                    la presión del agua no es la suficiente.</p> <!-- Descripcion corta -->
                                <p class="read-more"><a href="blog-post.html" class="btn btn-template-main">Continuar
                                        leyendo</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.box-image-text -->

                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="box-image-text blog">
                            <div class="top">
                                <div class="image">
                                    <img src="http://www.blindajesdemexico.com.mx/upload/Fotosnuevas/lol/SERVICIOMECANICO.jpg"
                                         alt="" class="img-responsive"> <!-- photo -->
                                </div>
                                <div class="bg"></div>
                                <div class="text">
                                    <p class="buttons">
                                        <a href="blog-post.html" class="btn btn-template-transparent-primary"><i
                                                    class="fa fa-link"></i> Ver mas</a>
                                    </p>
                                </div>
                            </div>
                            <div class="content">
                                <h4><a href="blog-post.html">Servicio Mecanico de automotores</a></h4> <!-- Titulo -->
                                <p class="intro">¿Tu automovil sufrió una avería? Nosotros lo solucionamos, trae tu auto
                                    y nosotros le hacemos un diagnostico.</p> <!-- Descripcion corta -->
                                <p class="read-more"><a href="blog-post.html" class="btn btn-template-main">Continuar
                                        leyendo</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.box-image-text -->

                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="box-image-text blog">
                            <div class="top">
                                <div class="image">
                                    <img src="http://solariseco.com/uploads/products/3.jpg" alt=""
                                         class="img-responsive"> <!-- photo -->
                                </div>
                                <div class="bg"></div>
                                <div class="text">
                                    <p class="buttons">
                                        <a href="blog-post.html" class="btn btn-template-transparent-primary"><i
                                                    class="fa fa-link"></i> Ver mas</a>
                                    </p>
                                </div>
                            </div>
                            <div class="content">
                                <h4><a href="blog-post.html">Instalación de calentadores solares</a></h4>
                                <!-- Titulo -->
                                <p class="intro">¿Necesitas instalar un calentador solar? Nosotros somos la mejor
                                    opcion, llamanos e iremos a donde estés.</p> <!-- Descripcion corta -->
                                <p class="read-more"><a href="blog-post.html" class="btn btn-template-main">Continuar
                                        leyendo</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.box-image-text -->

                    </div>

                </div>
                <!-- /.row -->

                <!-- *** BLOG HOMEPAGE END *** -->

            </div>
        </div>
        <!-- /.container -->
    </section>
    <!-- /.bar -->

    <section class="bar background-gray no-mb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading text-center">
                        <h2>Las mejores marcas </h2>
                    </div>

                    <ul class="owl-carousel customers">
                        @foreach($bMarcas as $bMarca)
                            <li class="item">
                                <img src="{{asset("img/marcas/".$bMarca->logo)}}" alt="" class="img-responsive">
                            </li>
                        @endforeach
                    </ul>
                    <!-- /.owl-carousel -->
                </div>

            </div>
        </div>
    </section>
    <?php $items=0;?>
    @foreach($productos as $producto)
        <!-- Modal correspondiente al producto -->
        <div class="modal fade product_view " id="product_view{{$items+1}}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#" data-dismiss="modal" class="class pull-right"><span
                                    class="glyphicon glyphicon-remove"></span></a>
                        <h3 class="modal-title">{{$producto->name}}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 product_img">
                                <div class="preview-pic tab-content ">
                                    <div class="tab-pane active" id="{{$items+1}}pic-1"><img src="{{asset('/img/productos/'.$producto->photo)}}" class="img-responsive" /></div>
                                    <div class="tab-pane" id="{{$items+1}}pic-2"><img src="{{asset('/img/productos/'.$producto->photo2)}}" class="img-responsive"/></div>
                                    <div class="tab-pane" id="{{$items+1}}pic-3"><img src="{{asset('/img/productos/'.$producto->photo3)}}"   class="img-responsive"/></div>
                                </div>
                                <ul class="preview-thumbnail nav nav-tabs">
                                    <li class="active" ><a data-target="#{{$items+1}}pic-1" data-toggle="tab"><img src="{{asset('/img/productos/'.$producto->photo)}}" class="img-responsive"/></a></li>
                                    <li ><a data-target="#{{$items+1}}pic-2" data-toggle="tab"><img src="{{asset('/img/productos/'.$producto->photo2)}}" /></a></li>
                                    <li ><a data-target="#{{$items+1}}pic-3" data-toggle="tab"><img src="{{asset('/img/productos/'.$producto->photo3)}}" /></a></li>
                                </ul>
                            </div>
                            <div class="col-md-12 product_content">
                                <h4>Codigo del producto: <span>{{$producto->code}}</span></h4>

                                <p>{{$producto->longdescription}}.</p>
                                <h3 class="cost"> {{isset($producto->price1)? '<span
                                            class="glyphicon glyphicon-usd"></span> '.$producto->price1." ".$producto->currency : "Inicia sesión para verlos precios"}}
                                </h3>
                                <div class="space-ten"></div>
                                <div class="btn-ground">
                                    <button type="button" class="btn btn-primary"><span
                                                class="glyphicon glyphicon-shopping-cart"></span>
                                        Agregar al carrito
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $items++;?>
    @endforeach
@endsection
<style>
    .preview {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column; }
    @media screen and (max-width: 996px) {
        .preview {
            margin-bottom: 20px; } }

    .preview-pic {
        -webkit-box-flex: 1;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;

    }

    .preview-thumbnail.nav-tabs {
        border: none;
        margin-top: 15px; }
    .preview-thumbnail.nav-tabs li {
        width: 20%;
        margin-right: 2.5%; }
    .preview-thumbnail.nav-tabs li img {
        max-width: 100%;
        display: block; }
    .preview-thumbnail.nav-tabs li a {
        padding: 0;
        margin: 0; }
    .preview-thumbnail.nav-tabs li:last-of-type {
        margin-right: 0; }


</style>