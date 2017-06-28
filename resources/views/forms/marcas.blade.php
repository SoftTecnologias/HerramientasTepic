@extends('layouts.administracion',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
<!-- Marcas -->
@section('content')
    <div class="content-wrapper">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Marcas
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-tag"> Marcas</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Marcas</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Marca</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="brand_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="brandTable" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid" aria-describedby="Marcas_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc col-sm-3" tabindex="0" aria-controls="brandTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="logo: Imagen representativa de la marca">
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Nombre: Nombre de la marca">
                                                    Nombre
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
                                                <th rowspan="1" colspan="1"></th>
                                                <th rowspan="1" colspan="1">Nombre</th>
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
        <!-- formulario marcas -->
        <div class="modal" id="modalBrand">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="model-body">
                        <form class="form-horizontal" id="brandForm" enctype="multipart/form-data">
                            <fieldset>
                                {{csrf_field()}}
                                <input type="hidden" id="brandid" name="brandid"/>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="name" name="name" placeholder="" class="form-control input-md"
                                               type="text">
                                    </div>
                                </div>
                                <!-- url imagen 1 -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="imgu1">url logo:</label>
                                    <div class="col-md-5">
                                        <input id="imgu1" name="imgu1" placeholder="" class="form-control input-md"  type="text">
                                        <img src="" alt="" class="image img-responsive hidden" id="im1">
                                    </div>
                                </div>
                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="img1">Logo</label>
                                    <div class="col-md-4">
                                        <input id="img1" name="img1" class="input-file" type="file">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnBrand" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/marcas.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection
