@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios])
<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>{{$actual['name']}}</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Inicio</a>
                        </li>
                        <li>categorias</li>
                        <li>{{$actual['name']}}</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">

                <div class="col-sm-3">

                    <!-- *** MENUS AND FILTERS ***
    _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title clearfix">Precios</h3>
                            <a class="btn btn-xs btn-danger pull-right" href="{{route('tienda.categorias',[base64_encode($actual['id'])])}}"><i class="fa fa-times-circle"></i>
                                <span class="hidden-sm">Limpiar filtros</span></a>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked price">
                                <li data-val="MC0zOTk="><a href="#"  class="fprice" data-val="MC0zOTk=">$ 0.00 - $ 399.00 </a>
                                </li>
                                <li data-val="NDAwLTc5OQ=="><a href="#"  class="fprice" data-val="NDAwLTc5OQ==">$ 400.00 - $ 799.00 </a>
                                </li>
                                <li data-val="ODAwLTExOTk=" ><a href="#" class="fprice" data-val="ODAwLTExOTk=">$ 800.00 - $ 1199.00 </a>
                                </li>
                                <li data-val="MTIwMC0xNTk5"><a href="#"  class="fprice" data-val="MTIwMC0xNTk5">$ 1200.00 - $ 1599.00 </a>
                                </li>
                                <li data-val="MTYwMC0xOTk5"><a href="#" class="fprice" data-val="MTYwMC0xOTk5">$ 1600.00 - $ 1999.00 </a>
                                </li>
                                <li data-val="MjAwMA=="><a href="#"  class="fprice" data-val="MjAwMA==">$ 2000.00 + </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Subcategorias</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked subcategory">
                                    @foreach($filtroSubcategoria as $subcategoria)
                                    <li data-val="{{$subcategoria->id}}"><a data-val="{{$subcategoria->id}}"href="#"  class="fsubcategory">{{$subcategoria->name}} ({{$subcategoria->total}})</a>
                                    </li>
                                    @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Marcas</h3>


                        </div>

                        <div class="panel-body">

                            <ul class="nav nav-pills nav-stacked brand">
                                    @foreach($filtroMarcas as $filtroMarca)
                                    <li data-val="{{$filtroMarca->id}}"><a data-val="{{$filtroMarca->id}}"href="#"  class="fbrand">{{$filtroMarca->name}} ({{$filtroMarca->total}})</a>
                                    </li>
                                    @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- *** MENUS AND FILTERS END *** -->

                    <div class="banner">
                        <a href="shop-category.html">
                            <img src="{{asset('img/banner.jpg')}}" alt="sales 2014" class="img-responsive">
                        </a>
                    </div>
                    <!-- /.banner -->

                </div>
                <!-- /.col-md-3 -->

                <!-- *** RIGHT COLUMN END *** -->
                <!-- *** LEFT COLUMN ***
        _________________________________________________________ -->

                <div class="col-sm-9">
                    <h1>Total de productos: {{$productos->total()}}</h1>
                    <div class="row products">
                        @foreach($productos as $producto)
                            <div class="col-md-4 col-sm-6">
                                <div class="product">
                                    <div class="image" style="height: 262px;">
                                        <a href="shop-detail.html">
                                            <img src="{{asset('/img/productos/'.$producto->photo)}}" alt=""
                                                 class="img-responsive image1">
                                        </a>
                                    </div>
                                    <!-- /.image -->
                                    <div class="text">
                                        <h3><a href="shop-detail.html">{{$producto->name}}</a></h3>
                                        <p class="price">{{isset($producto->price1)? '<span
                                            class="glyphicon glyphicon-usd"></span> '.$producto->price1." ".$producto->currency : "Inicia sesión para verlos precios"}}</p>
                                        <p class="buttons">
                                            <a href="shop-detail.html" class="btn btn-default">Ver detalle</a>
                                            <a href="shop-basket.html" class="btn btn-template-main"><i
                                                        class="fa fa-shopping-cart"></i>Agregar al carrito</a>
                                        </p>
                                    </div>
                                    <!-- /.text -->
                                </div>
                                <!-- /.product -->
                            </div>
                        @endforeach
                    </div>
                    <!-- /.products -->

                    <div class="row">

                        <div class="col-md-12 banner">
                            <a href="#">
                                <img src="{{asset('img/banner2.jpg')}}" alt="" class="img-responsive">
                            </a>
                        </div>

                    </div>


                    <div class="pages">
                        {{$productos->render()}}
                    </div>


                </div>
                <!-- /.col-md-9 -->

                <!-- *** LEFT COLUMN END *** -->

                <!-- *** RIGHT COLUMN ***
        _________________________________________________________ -->
            </div>

        </div>
        <!-- /.container -->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('/js/tienda/categorias.js')}}" type="text/javascript"></script>
@endsection