@extends('layouts.administracion')
@section('styles')
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
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
        <article id="userform">
            <div class="container">
                <form class="form-horizontal">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Nuevo Usuario</legend>

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
                                <input id="lastanem" name="lastanem" placeholder="" class="form-control input-md"
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

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">Contraseña:</label>
                            <div class="col-md-5">
                                <input id="password" name="password" placeholder="" class="form-control input-md"
                                       type="password">

                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="passwordconfirm">Confirmar Contraseña:</label>
                            <div class="col-md-5">
                                <input id="passwordconfirm" name="passwordconfirm" placeholder=""
                                       class="form-control input-md" type="password">

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
                                </select>
                            </div>
                        </div>

                        <!-- Multiple Radios -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="Status">Estatus:</label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <label for="Status-0">
                                        <input name="Status" id="Status-0" value="A" checked="checked" type="radio">
                                        Activo
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="Status-1">
                                        <input name="Status" id="Status-1" value="I" type="radio">
                                        Inactivo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="photo">Foto:</label>
                            <div class="col-md-4">
                                <input id="photo" name="photo" class="input-file" type="file">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Concluir registro</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </article>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/admin/marcas.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/additional-methods.min.js"></script>

@endsection