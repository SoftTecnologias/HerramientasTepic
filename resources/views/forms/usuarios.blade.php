@extends('layouts.administrador',['user_info' => $datos])
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
                Usuarios
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
                <li><a href="#"><i class="fa fa-user"> Usuarios</i></a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="pull-left">
                                <h3 class="box-title">Usuarios</h3>
                            </div>
                            <div class="pull-right">
                                <a class="btn btn-success" id="btnNew"><i class="fa fa-plus"></i>Agregar Usuario</a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div id="user_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="row">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="userTable" class="table table-bordered table-hover dataTable table-responsive"
                                               role="grid" aria-describedby="User_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc col-sm-2" tabindex="0" aria-controls="userTable"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Foto del usuario">
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                                    colspan="1" aria-label="Nombre: Nombre del usuario">
                                                    Nombre
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                                    colspan="1" aria-label="Apellido Paterno: apellido paterno del usuario">
                                                    Apellidos
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                                    colspan="1" aria-label="Apellido Materno: apellido materno del usuario">
                                                    Telefono
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                                    colspan="1" aria-label="Email: Correo del usuario">
                                                    Correo Electronico
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="userTable" rowspan="1"
                                                    colspan="1" aria-label="rol de permisos: permisos del usuario">
                                                    Rol
                                                </th>
                                                <th class="sorting_asc col-sm-3" tabindex="0" aria-controls="userTable"
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
                                                <th rowspan="1" colspan="1">Apellidos </th>
                                                <th rowspan="1" colspan="1">Telefono</th>
                                                <th rowspan="1" colspan="1">Correo Electronico</th>
                                                <th rowspan="1" colspan="1">Rol</th>
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
        <!-- Formulario de usuarios-->
        <div class="modal" id="modalUser">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reset()"><i class="fa fa-times"></i></button>
                        <h3 id="titulo-modal"></h3>
                    </div>
                    <div class="model-body">
                        <form class="form-horizontal" enctype="multipart/form-data" id="userForm">
                            <fieldset>
                                {{csrf_field()}}
                                {{ method_field('POST') }}
                                <input type="hidden" name="userid" id="userid">
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="name">Nombre:</label>
                                    <div class="col-md-5">
                                        <input id="name" name="name" placeholder="" class="form-control input-md" required=""
                                               type="text">
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="lastanem">Apellido (s):</label>
                                    <div class="col-md-5">
                                        <input id="lastname" name="lastname" placeholder="" class="form-control input-md"
                                               required="" type="text">
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="email">Correo Electronico:</label>
                                    <div class="col-md-5">
                                        <input id="email" name="email" placeholder="" class="form-control input-md" type="text">
                                    </div>
                                </div>

                                <!-- Text input password-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="password" >Contraseña:</label>
                                    <div class="col-md-5">
                                        <input id="password" name="password" placeholder="" class="form-control input-md"  type="password">
                                    </div>
                                </div>
                                <!-- Text input Nueva password-->
                                <div class="form-group hidden" id="npass">
                                    <label class="col-md-4 control-label" for="password" >Nueva Contraseña:</label>
                                    <div class="col-md-5">
                                        <input id="npassword" name="npassword" placeholder="" class="form-control input-md"  type="password">
                                    </div>
                                </div>
                                <!-- Text input confirmar password-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="cpassword">Confirme la contraseña:</label>
                                    <div class="col-md-5">
                                        <input id="cpassword" name="cpassword" placeholder="" class="form-control input-md" type="password">
                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="phone">Telefono :</label>
                                    <div class="col-md-5">
                                        <input id="phone" name="phone" placeholder="111-111-11-11 ó 111-11-11"
                                               class="form-control input-md" type="text">
                                    </div>
                                </div>
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="username">Nombre de usuario:</label>
                                    <div class="col-md-5">
                                        <input id="username" name="username" placeholder="" class="form-control input-md"
                                               type="text">
                                    </div>
                                </div>
                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="roleid">Rol de usuario:</label>
                                    <div class="col-md-5">
                                        <select id="roleid" name="roleid" class="form-control">
                                            <option value="0">Seleccione un rol</option>
                                            @foreach($roles as $rol)
                                                <option value="{{$rol->id}}">{{$rol->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Multiple Radios -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Status">Estatus:</label>
                                    <div class="col-md-4">
                                        <div class="radio">
                                            <label for="Status-0">
                                                <input name="status" id="status-0" value="A" checked="checked" type="radio">
                                                Activo
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label for="Status-1">
                                                <input name="status" id="status-1" value="I" type="radio">
                                                Inactivo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- File Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="photo">Foto: </label>
                                    <div class="col-md-4">
                                        <input id="photo" name="photo" class="input-file" type="file">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="btnUser" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script src="{{asset('js/admin/usuarios.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection