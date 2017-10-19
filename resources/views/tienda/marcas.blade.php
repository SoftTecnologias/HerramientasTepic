@extends('layouts.tienda')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css"
      integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>{{$actual['name']}}</h1>
                    <!-- Aqui iran los filtros actuales-->
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route('tienda.index')}}">Inicio</a>
                        </li>
                        <li>marcas</li>
                        <li>{{$actual['name']}}</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">

                <div class="col-sm-9">
                    <h3>Total de productos: {{$productos->total()}}</h3>
                    <div class="row products">
                        <?php $indice = 0; ?>
                        @foreach($productos as $producto)
                            <div class="col-md-4 col-sm-6">
                                <div class="product">
                                    <div class="image" style="height: 262px;">
                                        <p class="viewProduct">
                                            <a data-toggle="modal"
                                               data-target="#product_view{{$indice+1}}"><img src="{{asset('/img/productos/'.$producto->photo)}}" alt=""
                                                    class="img-responsive image1"></a>
                                        </p>
                                    </div>
                                    <!-- /.image -->
                                    <div class="text">
                                        <h3><p class="verProducto">{{$producto->name}}</p></h3>
                                        <p class="price"> {{isset($producto->price)?  "$ ".number_format($producto->price, 2,".",",")." ".$producto->currency." " : "Inicia sesión para ver los precios"}}</p>
                                    </div>
                                    <!-- /.text -->
                                </div>
                                <!-- /.product -->
                            </div>
                            <?php $indice++; ?>
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

                <!-- Detalles de los productos -->
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
                                            <h3 class="cost">{{isset($producto->price)?  "$ ".number_format($producto->price, 2,".",",")." ".$producto->currency." " : "Inicia sesión para ver los precios"}}
                                            </h3>
                                            <div class="space-ten"></div>
                                            <div class="btn-ground">
                                                <button type="button" class="btn btn-primary" onclick="agregarProducto('{{$producto->id}}')"><span
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
                <!-- *** LEFT COLUMN END *** -->

                <!-- *** RIGHT COLUMN ***
        _________________________________________________________ -->
                <div class="col-sm-3">

                    <!-- *** MENUS AND FILTERS ***
    _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title clearfix">Precios</h3>
                            <a class="btn btn-xs btn-danger pull-right" href="{{route('tienda.marcas',[base64_encode($actual['id'])])}}"><i class="fa fa-times-circle"></i>
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

                    <!-- *** MENUS AND FILTERS END *** -->

                    <div class="banner">
                        <a href="shop-category.html">
                            <img src="{{asset('/img/banner.jpg')}}" alt="sales 2014" class="img-responsive">
                        </a>
                    </div>
                    <!-- /.banner -->

                </div>
                <!-- /.col-md-3 -->

                <!-- *** RIGHT COLUMN END *** -->
                <!-- *** LEFT COLUMN ***
        _________________________________________________________ -->

            </div>

        </div>
        <!-- /.container -->
    </div>
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
@endsection
@section('scripts')
    <script src="{{asset('/js/tienda/marcas.js')}}"></script>
@endsection