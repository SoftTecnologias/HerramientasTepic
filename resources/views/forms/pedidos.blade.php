@extends('layouts.administrador',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style type="text/css">
        li { text-align: left; }
    </style>
    <style type="text/css">
        .fa-times{
         color: red;
        }
        .fa-check{
            color: green;
        }
    </style>
@endsection
<!-- Subcategorias -->
@section('content')
    <div class="content-wrapper" id="ContenidoPrincipal">
        <!-- Todo el contenido irá aquí -->
        <section class="content-header">
            <h1>
                Pedidos
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-paw"> Pedidos</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Pedidos</h3>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table">
                                <thead>
                                <th>
                                    <tr>Simbologia
                                    </tr>
                                </th>
                                </thead>
                                <tbody>
                                <th>
                                <td class="default">No Asignado</td>
                                <td class="active">Tomado</td>
                                <td class="info">Despachado</td>
                                <td class="warning">Enviado</td>
                                <td class="success">Recibido</td>
                                <td class="danger">Cancelado</td>
                                <td align="center" width="10%"><button class="btn btn-success" id="precioEnvio">Agregar Precio Envios</button></td>
                                </th>
                                </tbody>
                            </table>
                            <hr>
                            <div>
                                <label>Filtros</label>
                                <input type="checkbox" id="filtroNombre" checked><b>Nombre</b>
                                <input type="checkbox" id="filtroEstado" checked><b>Estado</b>
                            </div>
                            <div id="product_wrapper" class="dataTables_wrapper form-inline dt-bootstrap table-layout:fixed">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" id="divc" enctype="multipart/form-data"> <input type="hidden" id="no" name="no" class="form-control input-md"></form>
                                        <table id="tblPedidos" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Nombre del Cliente">
                                                    Cliente
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Telefono del Cliente">
                                                    Telefono
                                                </th>
                                                <th class="sorting" tabindex="1" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Estado del Pedido">
                                                    Estado
                                                </th>
                                                <th class="sorting" tabindex="2" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Usuario Asignado">
                                                    Usuario Asignado
                                                </th>
                                                <th class="sorting" tabindex="3" aria-controls="example2" rowspan="1"
                                                    colspan="1" aria-label="Fecha del Pedido">
                                                    Fecha de la orden
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="4" aria-controls="brandTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Total">
                                                    Total
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="5" aria-controls="brandTable"
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
    <!-- Modal asignacion -->
    <div class="modal" id="modtr">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Seleccione un Trabajador</h3>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="trabajadorForm" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            {{csrf_field()}}
                            <input type="hidden" id="pedidoid" name="pedidoid"/>
                            <div class="form-group">
                                <div class="col-md-12" align="center">
                                    <select name="trabajadores" id="trabajadores" class="btn btn-primary">
                                        <option value="">Selecciona Trabajador</option>
                                    </select>

                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAsignar" class="btn btn-sm btn-primary">Asignar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="moddetalle">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Detalle del Pedido</h3>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="trabajadorForm" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            {{csrf_field()}}
                            <input type="hidden" id="pedidoid" name="pedidoid"/>
                            <div class="form-group">
                                <div class="col-md-12" align="center">
                                    <table class="table table-bordered table-hover dataTable table-responsive" id="detalles">
                                        <thead>
                                        <tr role="row" class="info">
                                            <th>Productos</th>
                                            <th>Precios</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cuerpodetalles">

                                        </tbody>
                                        <tfoot class="success" id="celtotal">
                                            <tr class="success"  >
                                                <td style="font-weight: bold">Total</td>
                                                <td style="font-weight: bold"></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAceptar" class="btn btn-sm btn-primary">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modprecio">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="fa fa-times"></i></button>
                    <h3 id="titulo-modal">Precios de envio</h3>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="prenform" enctype="multipart/form-data">
                        <fieldset>
                            <!-- Text input-->
                            <div class="form-group">
                                <div class="col-md-3"></div>
                                <div class="col-md-6" align="left">
                                    <label for="cppr">Codigo Postal</label>
                                    <input type="text" class="form-control" id="cppr" name="cppr" placeholder="Codigo Postal">
                                    <br/>
                                    <label for="colpr">Colonia</label>
                                    <input type="text" class="form-control" id="colpr" name="colpr" placeholder="Colonia">
                                    <br/>
                                    <label for="prenvio">Precio Envio</label> <a href="http://www.google.com.mx" target="_blank">consultar</a>
                                    <input type="text" class="form-control" id="prenvio" name="prenvio" placeholder="Precio Envio">

                                </div>
                                
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnAgregar" class="btn btn-sm btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/admin/pedidos.js')}}"></script>
    <script src="{{asset('/css/font-awesome.min.css')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
@endsection
