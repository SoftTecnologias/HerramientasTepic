@extends('layouts.tienda')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.6.3/font-awesome.min.css"
      integrity="sha384-Wrgq82RsEean5tP3NK3zWAemiNEXofJsTwTyHmNb/iL3dP/sZJ4+7sOld1uqYJtE" crossorigin="anonymous">
<link rel="stylesheet" href="{{asset('css/tienda/profile.css')}}" type="text/css"/>

@section('content')
<div class="container" style="margin-top: 30px;">
    <div class="profile-head">
        <!--col-md-4 col-sm-4 col-xs-12 close-->
        <div class="col-md- col-sm-4 col-xs-12">
            <input type="hidden" value="{{$user->id}}" id="userid">
            <img src="{{asset('img/usuarios/'.$user->photo)}}" class="img-responsive"/>
            <h6>{{$user->uname." ".$user->lastname}}</h6>
            <form id="passform">
            <div class="form-group"style="margin-left: 90px;">
                <span class="btn btn-warning uplod-file">
                        Actualizar Foto <input type="file" id="foto" name="foto" class="input-file" accept=".jpg, .jpeg, .png"/>
                </span>
            </div>
            </form>
        </div>
        <!--col-md-4 col-sm-4 col-xs-12 close-->

        <div class="col-md-5 col-sm-5 col-xs-12">
            <h5>{{$user->uname." ".$user->lastname}}</h5>
            <p>Perfil del Cliente</p>
            <ul>
                <li><span class="fa fa-calendar-check-o"></span>{{$user->signindate}}</li>
                <li><span class="glyphicon glyphicon-map-marker"></span> {{$user->esnombre}}</li>
                <li><span class="glyphicon glyphicon-home"></span>{{$user->street.', No. '.$user->streetnumber}}</li>
                <li><span class="glyphicon glyphicon-phone"></span> <a href="#" title="call">{{$user->phone}}</a></li>
                <li><span class="glyphicon glyphicon-envelope"></span><a href="#" title="mail">{{$user->email}}</a></li>
            </ul>
        </div>
    </div>
    <!--profile-head close-->
</div>
<!--container close-->


<br/>
<br/>

<div class="container">
    <div class="col-sm-8">
        <div data-spy="scroll" class="tabbable-panel">
            <div class="tabbable-line">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab_default_1" data-toggle="tab" id="iu">Informacion de Usuario </a>
                    </li>
                    <li>
                        <a href="#tab_default_2" data-toggle="tab" id="ic">Informacion de Contacto</a>
                    </li>
                    <li>
                        <a href="#tab_default_3" data-toggle="tab" id="seg">Seguridad</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_default_1">
                        <div class="well well-sm">
                            <h4>Informacion Perosnal</h4>
                        </div>
                        <p align="right">
                            <button type="button" class="btn btn-primary btn-sm" id="editper">
                                <span class="glyphicon glyphicon-edit"></span> Editar</button>
                        </p>
                        <table class="table bio-table">
                            <tbody>
                            <form id="formPersonal">
                            <tr>
                                <td width="300">Nombre</td>
                                <td><input type="text" value="{{$user->uname}}" disabled id="nombre" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Apellidos</td>
                                <td> <input type="text" value="{{$user->lastname}}" disabled id="apellido" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Fecha de Registro</td>
                                <td> {{$user->signindate}}</td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td> {{$user->user}}</td>
                            </tr>
                            <tr>
                                <td>Tipo de Usuario</td>
                                <td> Cliente</td>
                            </tr>
                            </form>
                            <tr>
                                <td width="300" hidden id="acciones"><button type="submit" class="btn btn-primary" id="guardarper">Guardar Cambios</button>
                                    <button type="submit" class="btn btn-danger" id="cancelar">Cancelar</button></td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="tab_default_2">
                        <div class="well well-sm">
                            <h4>Informacion de Contacto</h4>
                        </div>
                        <p align="right">
                            <button type="button" class="btn btn-primary btn-sm" id="editcontact">
                                <span class="glyphicon glyphicon-edit"></span> Editar</button>
                        </p>
                        <table class="table bio-table">
                            <tbody>
                            <form id="formContacto">
                            <tr>
                                <td width="300">Estado</td>
                                <td> <select name="" id="selestado" disabled class="col-md-12 btn btn-default">
                                        <option value="00">Seleccione un estado</option>
                                        @foreach($estados as $estado)
                                            <?php
                                                if($estado->nombre == $user->esnombre){
                                                 ?>
                                            <option value="{{$estado->id_estado}}" selected>{{$estado->nombre}}</option>
                                            <?php }else{?>
                                                <option value="{{$estado->id_estado}}">{{$estado->nombre}}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Municipio</td>
                                <td> <select name="" id="selciudad" disabled class="col-md-12 btn btn-default">
                                        <option value="">Seleccione un Municipio</option>
                                        @foreach($municipios as $municipio)
                                            <?php
                                            if($municipio->nombre == $user->muname){
                                            ?>
                                            <option value="{{$municipio->id_municipio}}" selected>{{$municipio->nombre}}</option>
                                            <?php }else{?>
                                            <option value="{{$municipio->id_municipio}}">{{$municipio->nombre}}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Localidad</td>
                                <td> <select name="" id="sellocalidad" disabled class="col-md-12 btn btn-default">
                                        <option value="">Seleccione una localidad<
                                        @foreach($localidades as $localidad)
                                            <?php
                                            if($localidad->nombre == $user->loname){
                                            ?>
                                            <option value="{{$localidad->id_localidad}}" selected>{{$localidad->nombre}}</option>
                                            <?php }else{?>
                                            <option value="{{$localidad->id_localidad}}">{{$localidad->nombre}}</option>
                                            <?php } ?>
                                        @endforeach
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Calle 1</td>
                                <td> <input type="text" value="{{$user->street}}" disabled id="calle1" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Calle 2</td>
                                <td> <input type="text" value="{{$user->street2}}" disabled id="calle2" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Calle 3</td>
                                <td> <input type="text" value="{{$user->street3}}" disabled id="calle3" class="form-control input-md"></td>
                            </tr>
                                <tr>
                                    <td>Numero</td>
                                    <td> <input type="text" value="{{$user->streetnumber}}" disabled id="numext" class="form-control input-md"></td>
                                </tr>
                            <tr>
                                <td>Codigo Postal</td>
                                <td> <input type="text" value="{{$user->zipcode}}" disabled id="cp" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Referencias</td>
                                <td> <input type="text" value="{{$user->reference}}" disabled id="ref" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Telefono</td>
                                <td> <input type="text" value="{{$user->phone}}" disabled id="tel" class="form-control input-md"></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td> <input type="text" value="{{$user->email}}" disabled id="mail" class="form-control input-md"></td>
                            </tr>
                            </form>
                            <tr>
                                <td width="300" hidden id="accionesc"><button type="submit" class="btn btn-primary" id="guardarcontact">Guardar Cambios</button>
                                    <button type="submit" class="btn btn-danger" id="cancelarc">Cancelar</button></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="tab_default_3">
                        <div class="well well-sm">
                            <h4>Seguridad</h4>
                        </div>
                        <p align="right">
                            <button type="button" class="btn btn-primary btn-sm" id='edpas'>
                                <span class="glyphicon glyphicon-edit"></span> Editar</button>
                        </p>
                        <table class="table bio-table">
                            <tbody>
                            <form id="passwordForm">
                            <tr>
                                <td width="300">Contraseña Actual</td>
                                <td><input type="password" placeholder="Contraseña Actual" id="passactual" disabled class="form-control input-md"> </td>
                            </tr>
                            <tr>
                                <td>Nueva Contraseña</td>
                                <td><input type="password" placeholder="Nueva Contraseña" id="newpass" disabled class="form-control input-md"> </td>
                            </tr>
                            <tr>
                                <td>Confirmar Contraseña</td>
                                <td><input type="password" placeholder="Confirmar Contraseña" id="confirmpass" disabled class="form-control input-md"></td>
                            </tr>
                            </form>
                            </tbody>
                            <tfoot><tr>
                                <td><a class="btn btn-primary" id="changepass">Cambiar Contraseña</a></td>
                            </tr></tfoot>
                        </table>
                        <br/>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="menu_title">
                <b>Compras</b>
                <p>No tiene compras Realizadas</p>
            </div>
            
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/tienda/profile.js')}}"></script>
@endsection