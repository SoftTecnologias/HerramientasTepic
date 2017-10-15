<style>
    #search {
        border-radius: 6px;
        width: 300px;
        padding: 8px 20px;
        text-align: center;
    }
    #buscar{
        width: 35px;
        height: 35px;
        border-radius: 6px;
        border: 0px;
        background-color: #337ab7;
        color: white;
    }

</style>

<div class="navbar-affixed-top" data-spy="affix" data-offset-top="200">

    <div class="navbar navbar-default yamm" role="navigation" id="navbar">

        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand home" href="{{route('tienda.index')}}">
                    <img src="{{asset('/img/image.png')}}" alt="Herramientas de Tepic logo" class="hidden-xs hidden-sm img-responsive" />
                    <img src="{{asset('/img/minilogo50.png')}}" alt="Herramientas de Tepic logo" class="visible-xs visible-sm"><span
                            class="sr-only">Herramientas y Servicios de Tepic</span>
                </a>
                <div class="navbar-buttons">
                    <button type="button" class="navbar-toggle btn-template-main" data-toggle="collapse"
                            data-target="#navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-align-justify"></i>
                    </button>
                </div>
            </div>
            <!--/.navbar-header -->

            <div class="navbar-collapse collapse" id="navigation">

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown active">
                        <a href="{{route('tienda.index')}}">Inicio </a>

                    </li>
                    <li class="dropdown use-yamm yamm-fw">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Marcas<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="yamm-content">
                                    <div class="row">
                                        @foreach(array_chunk($marcas,10) as $column)
                                            <div class="col-md-3">
                                                <ul>
                                                    @foreach($column as $item)
                                                        <li>
                                                            <a href="{{route('tienda.marcas',$item->id)}}">{{$item->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- ========== FULL WIDTH MEGAMENU ================== -->
                    <li class="dropdown use-yamm yamm-fw">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-delay="200">Categorias <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="yamm-content">
                                    <div class="row">
                                        @foreach(array_chunk($categorias, 10) as $columna)
                                            <div class="col-sm-3">
                                                <ul>
                                                    @foreach($columna as $item)
                                                        <li><a href="{{route('tienda.categorias',$item->id)}}">{{$item->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- /.yamm-content -->
                            </li>
                        </ul>
                    </li>
                    <!-- ========== FULL WIDTH MEGAMENU END ================== -->

                    <li class="dropdown">
                        <a href="javascript: void(0)" class="dropdown-toggle" data-toggle="dropdown">Servicios <b
                                    class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <input type="hidden" value="{{$i =0}}">
                            @foreach($servicios as $servicio)
                                @if($i++<4)
                                <li><a href="{{route('tienda.detalleServicio',$servicio->id)}}">{{$servicio->title}}</a>
                                </li>
                                @endif
                            @endforeach
                            <li><a href="{{route('tienda.servicios')}}">Ver Todos</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown use-yamm yamm-fw">
                        <div class="yamm-content">
                            <input type="text" id="search" placeholder="Busca por nombre, descripcion o codigo" style="text-align:left"/>
                            <button  class="glyphicon glyphicon-search" id="buscar" name="buscar"></button>
                        </div>
                    </li>


                </ul>

            </div>
            <!--/.nav-collapse -->

            <div class="collapse clearfix" id="search">

                <form class="navbar-form" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <span class="input-group-btn">

                    <button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button>

                </span>
                    </div>
                </form>

            </div>
            <!--/.nav-collapse -->
            <br>
            <br>

        </div>


    </div>
    <!-- /#navbar -->

</div>
