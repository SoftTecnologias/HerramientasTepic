@extends('layouts.tienda')

<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>{{$f}} </h1> <h3>Coincidencias Encontradas:{{$filtro->total()}}</h3>
                    <!-- Aqui iran los filtros actuales-->
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href={{route('tienda.index')}}>Inicio</a>
                        </li>
                        <li>Busqueda</li>
                        <li>{{$f}}</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">
                <div class="col-sm-9">
                    <div class="row products">
                        @foreach($filtro as $producto)
                            <div class="col-md-4 col-sm-6">
                                <div class="product">
                                    <div class="image" style="height: 262px;">
                                        <p class="viewProduct">
                                            <img src="{{asset('/img/productos/'.$producto->photo)}}" alt=""
                                                 class="img-responsive image1">
                                        </p>
                                    </div>
                                    <!-- /.image -->
                                    <div class="text">
                                        <h3><p class="verProducto">{{$producto->name}}</p></h3>
                                        <p class="price">{{isset($producto->price)? "$ $producto->price $producto->currency ": "Inicia sesión para verlos precios"}}</p>
                                    </div>
                                    <!-- /.text -->
                                </div>
                                <!-- /.product -->
                            </div>
                        @endforeach
                    </div>
                    <!-- /.products -->
                </div>
                <!-- /.col-md-9 -->

                <!-- *** LEFT COLUMN END *** -->
                <div class="col-sm-3">
                <div class="panel panel-default sidebar-menu">

                    <div class="panel-heading">
                        <h3 class="panel-title clearfix">Precios</h3>
                        <a class="btn btn-xs btn-danger pull-right" href="{{route('tienda.search.productos',base64_encode($f))}}"><i class="fa fa-times-circle"></i>
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
                        <h3 class="panel-title">Categorias</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked category">
                            @foreach($filtroCategorias as $categoria)
                                <li data-val="{{$categoria['id']}}">
                                    <a   data-val="{{$categoria['id']}}" href="#" class="fcategory">{{$categoria['name']}}<span class="badge pull-right">{{$categoria['total']}}</span></a>
                                    <ul class="nav nav-pills nav-stacked subcategory">
                                        @foreach($categoria['subcategorias'] as $subcategoria)
                                            <li data-val="{{$subcategoria->id}}"><a data-val="{{$subcategoria->id}}"href="#"  class="fsubcategory">{{$subcategoria->name}} ({{$subcategoria->total}})</a>
                                            </li>
                                        @endforeach
                                    </ul>
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
                </div>
            </div>

                <!-- *** RIGHT COLUMN ***
        _________________________________________________________ -->
                <!-- /.col-md-3 -->

                <!-- *** RIGHT COLUMN END *** -->
                <!-- *** LEFT COLUMN ***
        _________________________________________________________ -->


            </div>

        </div>
        <!-- /.container -->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('/js/tienda/marcas.js')}}"></script>
@endsection