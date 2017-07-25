@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>Proveedores </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-truck"> Proveedores</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Proveedores</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Proveedores</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="proveedor_wrapper" class="dataTables_wrapper form-inline dt-bootstrap table-responsive">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="providerTable" class="display nowrap table table-bordered table-hover dataTable table-responsive "
                                               role="grid" aria-describedby="provider_info" width="100%" >
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending" aria-label="Muestra: Nombre del proveedor">
                                                    Nombre del Proveedor
                                                </th>

                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Telefono1">
                                                    Telefono 1
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Telefono2">
                                                    Telefono 2
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="email">
                                                    Email
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Contacto">
                                                    Nombre de Contacto
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="acciones">
                                                    Acciones
                                                </th>
                                            </tr>
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
        <!-- Formulario de provider-->
        <div class="modal" id="modalProvider">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="providerForm" >
                            <fieldset>
                                <!-- Text input-->
                                <input type="hidden" id="id" name="id"/>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="code">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="nombre" name="nombre" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="calle">Calle:</label>
                                    <div class="col-md-5">
                                        <input id="calle" name="calle" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="colonia">Colonia:</label>
                                    <div class="col-md-5">
                                        <input id="colonia" name="colonia" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Numero de domicilio -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="numero_int">Numero interior:</label>
                                    <div class="col-md-5">
                                        <input id="numero_int" name="numero_int" placeholder="" class="form-control input-md" required="" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="numero_ext">Numero exterior:</label>
                                    <div class="col-md-5">
                                        <input id="numero_ext" name="numero_ext" placeholder="" class="form-control input-md" required="" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="cp">Codigo Postal:</label>
                                    <div class="col-md-5">
                                        <input id="cp" name="cp" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="estado">Estado:</label>
                                    <div class="col-md-5">
                                        <select id="estados" name="estados" class="form-control input-md">
                                            <option value="">Seleccione un estado</option>
                                            @foreach($estados as $estado)
                                                <option value="{{$estado->id_estado}}">{{$estado->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="municipio">Municipio:</label>
                                    <div class="col-md-5">
                                        <select id="municipio" name="municipio" placeholder="" class="form-control input-md">
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="telefono1">Telefono 1:</label>
                                    <div class="col-md-5">
                                        <input id="telefono1" name="telefono1" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="telefono2">Telefono 2:</label>
                                    <div class="col-md-5">
                                        <input id="telefono2" name="telefono2" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="email">Correo Electronico:</label>
                                    <div class="col-md-5">
                                        <input id="email" name="email" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="contacto">Contacto:</label>
                                    <div class="col-md-5">
                                        <input id="contacto" name="contacto" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnProvider" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/proveedores.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
@endsection