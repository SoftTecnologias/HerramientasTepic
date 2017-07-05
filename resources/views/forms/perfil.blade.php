@extends('layouts.administracion',['user_info' => $datos])
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
    href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')

    <div class="content-wrapper">
        <form id="userForm">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad"
                     id="datos">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{$datos['name']}}</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 " align="center"><img alt="Foto Usuario"
                                                                                    src="{{asset('img/usuarios/'.$datos['photo'])}}"
                                                                                    class="img-circle img-responsive">

                                </div>

                                <div class=" col-md-9 col-lg-9 ">
                                    <input type="hidden" id="userid" name="userid">
                                    <table class="table table-user-information">
                                        <tbody>
                                        <tr>
                                            <td>Telefono:</td>
                                            <td><input type="text" value="{{$usuario['phone']}}" readonly id="tel" name="tel"></td>
                                            <td>
                                                <a id="editTelefono" data-original-title="Editar Telefono"
                                                   data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i
                                                            class="glyphicon glyphicon-edit"></i></a>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>Fecha de Registro:</td>
                                            <td>{{$datos['ingreso']}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><input type="text" value="{{$usuario['email']}}" readonly id="mail" name="mail">
                                            </td>
                                            <td>
                                                <a id="editEmail" data-original-title="Editar Email"
                                                   data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i
                                                            class="glyphicon glyphicon-edit"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Contraseña Actual</td>
                                            <td><input type="password" id="conactual" name="conactual"></td>
                                        </tr>
                                        <tr>
                                            <td>Nueva Contraseña</td>
                                            <td><input type="password" id="nuevacon" name="nuevacon"></td>
                                        </tr>
                                        <tr>
                                            <td>Confirmar</td>
                                            <td><input type="password" id="confirmcon" name="confirmcon"></td>
                                        </tr>

                                        </tbody>
                                        <tfoot>
                                         <div class="form-group">
                                             <td><label class="col-md-4 control-label" for="photo">Foto: </label></td>
                                                <td><div class="col-md-4">
                                                    <input id="photo" name="photo" class="input-file" type="file">
                                                    </div></td>
                                            </div>
                                        </tfoot>
                                    </table>

                                    <a class="btn btn-primary" id="btnGuardar">Guardar Cambios</a>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </form>
    </div>


    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>
    <script src="{{  asset('/js/admin/EditarPerfil.js')}}" type="text/javascript"></script>

    <style type="text/css">
        #datos {
            margin-top: 100px;
        }
    </style>
@endsection

