@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
<!-- Productos -->
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Productos
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-paw"> Productos</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Productos</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Producto</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="product_wrapper" class="dataTables_wrapper form-inline dt-bootstrap table-layout:fixed">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" id="divc" enctype="multipart/form-data"> <input type="hidden" id="no" name="no" class="form-control input-md"></form>
                                        <table id="tblProducts" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid" aria-describedby="Marcas_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc col-sm-3" tabindex="0" aria-controls="brandTable"
                                                     aria-sort="ascending"
                                                    aria-label="logo: Imagen representativa del producto">
                                                </th>
                                                <th class="sorting" tabindex="1" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="codigo del producto">
                                                    Codigo
                                                </th>
                                                <th class="sorting" tabindex="2" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="nombre del producto">
                                                    Nombre
                                                </th>
                                                <th class="sorting" tabindex="3" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Marca del producto">
                                                    Marca
                                                </th>
                                                <th class="sorting" tabindex="4" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="categoria del producto">
                                                    Categoria
                                                </th>
                                                <th class="sorting" tabindex="5" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="subcategoria del producto">
                                                    Subcategoria
                                                </th>
                                                <th class="sorting" tabindex="6" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="codigo del producto">
                                                    Precio 1
                                                </th>
                                                <th class="sorting" tabindex="7" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="codigo del producto">
                                                    Precio 2
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="8" aria-controls="brandTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Acciones">
                                                    Acciones
                                                </th>
                                            </tr >
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
        </section>
        <!-- Formulario de productos-->
        <div class="modal" id="modalProduct">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="productForm" enctype="multipart/form-data">
                            <fieldset>
                                <!-- Text input-->
                                {{csrf_field()}}
                                <input type="hidden" id="productid" name="productid"/>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="code">Codigo del producto:</label>
                                    <div class="col-md-5">
                                        <input id="code" name="code" placeholder="" class="form-control input-md"
                                               required="" type="text">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="name" name="name" placeholder="" class="form-control input-md"
                                               required="" type="text">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="currency">Divisa:</label>
                                    <div class="col-md-5">
                                        <input id="currency" name="currency" placeholder="MXN"
                                               class="form-control input-md" type="text">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="shortdescription">Descripcion
                                        corta:</label>
                                    <div class="col-md-5">
                                        <input id="shortdescription" name="shortdescription" placeholder=""
                                               class="form-control input-md" type="text">

                                    </div>
                                </div>

                                <!-- Textarea -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="longdescription">Descripcion
                                        larga:</label>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="longdescription"
                                                  name="longdescription"></textarea>
                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="brandid">Marca:</label>
                                    <div class="col-md-5">
                                        <select id="brandid" name="brandid" class="form-control">
                                            <option value="00">Seleccione una marca</option>
                                            @foreach($marcas as $marca)
                                                <option value="{{$marca->id}}">{{$marca->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-add addO" data-val="b"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="categoryid">Categoria:</label>
                                    <div class="col-md-5">
                                        <select id="categoryid" name="categoryid" class="form-control">
                                            <option value="00">Seleccione una categoria</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-add addO" data-val="c"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="subcategoryid">Subcategoria:</label>
                                    <div class="col-md-5">
                                        <select id="subcategoryid" name="subcategoryid" class="form-control">
                                            <option value="00">Seleccione antes una categoria</option>
                                        </select>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-add addO" data-val="s"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="price1">Precio 1:</label>
                                    <div class="col-md-5">
                                        <input id="price1" name="price1" placeholder="" class="form-control input-md"
                                               required="" type="number">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="price2">Precio 2:</label>
                                    <div class="col-md-5">
                                        <input id="price2" name="price2" placeholder="" class="form-control input-md"
                                               required="" type="number" value="0">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="price3">Precio 3:</label>
                                    <div class="col-md-5">
                                        <input id="price3" name="price3" placeholder="" class="form-control input-md"
                                               required="" type="number" value="0">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="price4">Precio 4:</label>
                                    <div class="col-md-5">
                                        <input id="price4" name="price4" placeholder="" class="form-control input-md"
                                               required="" type="number" value="0">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="price5">Precio 5:</label>
                                    <div class="col-md-5">
                                        <input id="price5" name="price5" placeholder="" class="form-control input-md"
                                               required="" type="number" value="0">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="reorderpoint">Punto de
                                        Reabastecimiento:</label>
                                    <div class="col-md-5">
                                        <input id="reorderpoint" name="reorderpoint" placeholder=""
                                               class="form-control input-md" required="" type="number" value="0">

                                    </div>
                                </div>

                                <!-- url imagen 1 -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="imgu1">url imagen A:</label>
                                    <div class="col-md-5">
                                        <input id="imgu1" name="imgu1" placeholder="" class="form-control input-md"
                                               type="text">
                                        <img src="" alt="" class="image img-responsive hidden" id="im1">
                                    </div>
                                </div>
                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="img1">Imagen A</label>
                                    <div class="col-md-4">
                                        <input id="img1" name="img1" class="input-file" type="file">
                                    </div>
                                </div>
                                <!-- url imagen 2 -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="imgu2">url imagen B:</label>
                                    <div class="col-md-5">
                                        <input id="imgu2" name="imgu2" placeholder="" class="form-control input-md"
                                               type="text">
                                        <img src="" alt="" class="image img-responsive hidden" id="im2">
                                    </div>
                                </div>
                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="fileB">Imagen B</label>
                                    <div class="col-md-4">
                                        <input id="img2" name="img2" class="input-file" type="file">
                                    </div>
                                </div>

                                <!-- url imagen 3 -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="imgu3">url imagen C:</label>
                                    <div class="col-md-5">
                                        <input id="imgu3" name="imgu3" placeholder="" class="form-control input-md"
                                               type="text">
                                        <img src="" alt="" class="image img-responsive hidden" id="im3">
                                    </div>
                                </div>
                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="fileC">Imagen C</label>
                                    <div class="col-md-4">
                                        <input id="img3" name="img3" class="input-file" type="file">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnAceptar" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formulario Marcas -->
        <div class="modal" id="modalBrand">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <h3>Nueva Marca</h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="brandform" enctype="multipart/form-data">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="brandname">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="brandname" name="brandname" placeholder=""
                                               class="form-control input-md" required="" type="text">
                                    </div>
                                </div>

                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="brandphoto">Logo</label>
                                    <div class="col-md-4">
                                        <input id="fileA" name="brandphoto" class="input-file" type="file">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnBrand" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formulario Category -->
        <div class="modal" id="modalCategory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <h3>Nueva Categoria</h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="categoryform">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="categoryname">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="name" name="categoryname" placeholder=""
                                               class="form-control input-md" required="" type="text">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnCategory" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formulario Category -->
        <div class="modal" id="modalSubCategory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <h3>Nueva SubCategoria</h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="subcategoryform">
                            <fieldset>
                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="category">Categoria:</label>
                                    <div class="col-md-5">
                                        <select id="category" name="category" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="subcategoryname">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="subcategoryname" name="subcategoryname" placeholder=""
                                               class="form-control input-md" required="" type="text">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSubcategory" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/productos.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection
