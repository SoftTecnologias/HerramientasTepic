@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
<!-- Subcategorias -->
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Servicios
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-paw"> Servicios</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Servicios</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Servicio</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="product_wrapper" class="dataTables_wrapper form-inline dt-bootstrap table-layout:fixed">
                                <div class="row">
                                    <div class="col-sm-12">
                                    <form class="form-horizontal" id="divc" enctype="multipart/form-data"> <input type="hidden" id="no" name="no" class="form-control input-md"></form>
                                        <table id="tblServicios" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc col-sm-3" tabindex="0" aria-controls="brandTable"
                                                    aria-sort="ascending"
                                                    aria-label="logo: Imagen representativa del servicio">
                                                </th>
                                                <th class="sorting" tabindex="1" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="titulo del servicio">
                                                    Titulo
                                                </th>
                                                <th class="sorting" tabindex="2" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="descripcion corta">
                                                    Descripcion Corta
                                                </th>
                                                <th class="sorting" tabindex="3" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="descripcion larga">
                                                    Descripcion Larga
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="4" aria-controls="brandTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Acciones">
                                                    Acciones
                                                </th>
                                            </tr >
                                          </thead>
                                            <tbody id="bodyt">

                                            </tbody>
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
    </div>
<!-- Modal Servicios -->
    <div class="modal" id="modalServices">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times"></i></button>
                    <h3 id="titulo-modal"></h3>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="serviceForm" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            {{csrf_field()}}
                            <input type="hidden" id="serviceid" name="serviceid"/>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="code">Titulo del Servicio</label>
                                <div class="col-md-5">
                                    <input id="title" name="title" placeholder="" class="form-control input-md"
                                           required="" type="text"/>

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="shortdesc">Descripcion Corta:</label>
                                <div class="col-md-5">
                                    <input id="shortdesc" name="shortdesc" placeholder="" class="form-control input-md"
                                           required="" type="text"/>

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="longdesc">Descripcion Larga:</label>
                                <div class="col-md-5">
                                    <textarea id="longdesc" name="longdesc"
                                              class="form-control"></textarea>
                                </div>
                            </div>
                            <!-- url imagen 1 -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="img">url imagen:</label>
                                <div class="col-md-5">
                                    <input id="imgu" name="imgu" placeholder="" class="form-control input-md"
                                           type="text">
                                    <img src="" alt="" class="image img-responsive hidden" id="im1">
                                </div>
                            </div>
                            <!-- File Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="img1">Imagen</label>
                                <div class="col-md-4">
                                    <input id="img1" name="img1" class="input-file" type="file">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-4 control-label">Encargado</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="encargado" name="encargado">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-md-4 control-label">Precio</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="precio" name="precio">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-md-4 control-label">Horario</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="horario" name="horario">
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
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/services.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection
