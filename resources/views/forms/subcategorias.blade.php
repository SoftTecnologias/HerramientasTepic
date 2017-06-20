@extends('layouts.administracion')
<!-- Subcategorias -->
@section('styles')
    <link media="all" type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Subcategorias
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-book">Subcategorias</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Subcategorias</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Subcategorias</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="subcategory_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="subcategoryTable" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid" aria-describedby="categorias_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Nombre: Nombre de la marca">
                                                    Nombre
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Nombre: Nombre de la marca">
                                                    Categoria
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="0" aria-controls="brandTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Acciones">
                                                    Acciones
                                                </th>
                                            </tr >
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th rowspan="1" colspan="1">Nombre</th>
                                                <th rowspan="1" colspan="1">Categoria</th>
                                                <th rowspan="1" colspan="1">Acciones</th>
                                            </tr>
                                            </tfoot>
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
        <div class="modal" id="modalSubcategory">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="model-body">
                        <form class="form-horizontal" id="subcategoryForm">
                            <fieldset>
                            {{csrf_field()}}
                            <!-- Text input-->
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <input id="subcategoryid" name="subcategoryid" placeholder="" class="form-control input-md" required="" type="hidden">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="name" name="name" placeholder="" class="form-control input-md" required="" type="text">

                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="categoryid">Categoria:</label>
                                    <div class="col-md-5">
                                        <select id="categoryid" name="categoryid" class="form-control">
                                            <option value="">Seleccione una categoria</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnSubcategory" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/admin/subcategorias.js')}}"></script>
@endsection