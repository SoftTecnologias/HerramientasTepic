@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios])
<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>{{$actual}}</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Inicio</a>
                        </li>
                        <li>categorias</li>
                        <li>{{$actual}}</li>
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
                            <a class="btn btn-xs btn-danger pull-right" href="#"><i class="fa fa-times-circle"></i>
                                <span class="hidden-sm">Clear</span></a>
                        </div>

                        <div class="panel-body">

                            <form id="filtroPrecios">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-val="MQ==" class="fprice">$ 0.00 - $ 299.00
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-val="Mg==" class="fprice"> $ 300.00 - $ 599.00
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-val="Mw==" class="fprice"> $ 600.00 - $ 999.00
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-val="NA==" class="fprice"> $ 1000.00 - $ 1499.00
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-val="NQ==" class="fprice"> + $ 1500.00
                                        </label>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Subcategorias</h3>
                        </div>
                        <div class="panel-body">
                            <form id="filtroSubcategorias">
                                <div class="form-group nav nav-pills nav-stacked category-menu">
                                    <a href="shop-category.html">{{$actual}} <span
                                                class="badge pull-right">{{$productos->total()}}</span></a>
                                    @foreach($filtroSubcategoria as $subcategoria)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" value="{{$subcategoria->id}}"
                                                       class="fprice">{{$subcategoria->name}} ({{$subcategoria->total}})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Marcas</h3>
                            <a class="btn btn-xs btn-danger pull-right" href="#"><i class="fa fa-times-circle"></i>
                                <span class="hidden-sm">Clear</span></a>
                        </div>

                        <div class="panel-body">

                            <form id="filtroMarca">
                                <div class="form-group">
                                    @foreach($filtroMarcas as $filtroMarca)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       value="{{$filtroMarca->id}}">{{$filtroMarca->name}}
                                                ({{$filtroMarca->total}})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </form>

                        </div>
                        <button class="btn btn-default btn-sm btn-template-main"><i class="fa fa-pencil"></i>
                            Aplicar filtros
                        </button>

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