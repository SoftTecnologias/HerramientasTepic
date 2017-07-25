@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    {{Html::style('css/admin/movimientos.css')}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <section class="content-header">
            <h1>Movimientos </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-archive"> Movimientos</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Movimientos</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Nuevo Movimiento</a>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="movimiento_wrapper"
                                 class="dataTables_wrapper form-inline dt-bootstrap table-responsive">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="movementTable"
                                               class="display nowrap table table-bordered table-hover dataTable table-responsive "
                                               role="grid" aria-describedby="provider_info" width="100%">
                                            <thead>
                                            <tr role="row">
                                                <th></th>
                                                <th class="sorting " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Folio: ID del movimiento">
                                                    Folio Movimiento
                                                </th>

                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Nombre del proveedor">
                                                    Proveedor
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Factura del movimienot">
                                                    Folio Factura
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="movimentTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="total">
                                                    Total
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="fecha">
                                                    Fecha
                                                </th>
                                                <th class="sorting_asc " tabindex="0" aria-controls="providerTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Observaciones">
                                                    Observaciones
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

        <!-- Formulario de movimiento-->
        <div class="modal" id="modalMovement">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i
                                    class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="movementForm">
                            <fieldset>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="proveedor_id">Proveedor:</label>
                                    <div class="col-md-5">
                                        <select id="proveedor_id" name="proveedor_id" placeholder="" class="form-control" style="width: 220px">
                                            <option value="">Seleccione un proveedor</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="tipo">Tipo de movimiento:</label>
                                    <div class="col-md-5">
                                        <select name="tipo" id="tipo" class="form-control input-md">
                                            <option value="">Seleccione un tipo de movimiento</option>
                                            <option value="1">Entrada</option>
                                            <option value="2">Salida</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="factura">No. Factura:</label>
                                    <div class="col-md-5">
                                        <input id="factura" name="factura" placeholder="" class="form-control input-md "
                                               type="text">
                                    </div>
                                </div>
                                <!-- Numero de observaciones -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="observaciones">Observaciones:</label>
                                    <div class="col-md-5">
                                        <textarea id="observaciones" name="observaciones" placeholder=""
                                                  class="form-control input-md" required="" type="text"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnMovement" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formulario de detalle-->
        <div class="modal" id="modalDetail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="resetD()"><i
                                    class="fa fa-times"></i></button>
                        <h3 id="titulo-modalD"></h3>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="detailForm">
                            <fieldset>
                                <input type="hidden" name="movement_id" id="movement_id">
                                <!-- Producto-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="producto_id">Producto:</label>
                                    <div class="col-md-8">
                                        <select id="producto_id" name="producto_id" placeholder=""
                                                class="form-control" style="width:220px">
                                            <option value="">Seleccione un producto</option>
                                            @foreach($productos as $producto)
                                                <option value="{{$producto->id}}">{{$producto->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="precio">Precio Unitario:</label>
                                    <div class="col-md-5">
                                        <input id="precio" name="precio" placeholder="" class="form-control input-md "
                                               type="number">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="cantidad">Cantidad:</label>
                                    <div class="col-md-5">
                                        <input id="cantidad" name="cantidad" placeholder=""
                                               class="form-control input-md " type="number">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnDetail" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/movimientos.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection