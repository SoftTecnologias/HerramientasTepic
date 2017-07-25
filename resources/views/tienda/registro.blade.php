@extends('layouts.tienda',['marcas'=> $marcas,'categorias'=>$categorias,'servicios'=> $servicios])

<!-- registro -->
@section('styles')
    <link media="all" type="text/css" rel="stylesheet" href="{{asset("css/fileinput.min.css")}}"/>
@endsection
@section('content')
<div id="heading-breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1>Registro de clientes</h1>
            </div>
            <div class="col-md-5">
                <ul class="breadcrumb">
                    <li><a href="{{route('tienda.index')}}">Inicio</a>
                    </li>
                    <li>Registro</li>
                </ul>

            </div>
        </div>
    </div>
</div>

<div id="content">
    <div class="container">

        <div class="row">
            <div class="col-md-9 col-md-offset-2">
                <div class="box">
                    <h2 class="text-uppercase">Nuevo Cliente</h2>

                    <p class="lead">¿Aun no eres nuestro cliente?</p>
                    <p>¿Te gustaria adquirir los excelentes productos que ofrecemos? Registrate ahora mismo y tendremos para ti excelentes ofertas, utiles servicios y la posibilidad de adquirir los mejores productos desde la comodidad de tu hogar</p>
                    <p class="text-muted">¿ Tienes preguntas acerca del registro? <a href="#">Contactanos</a>, resolveremos las dudas que puedas tener.</p>
                    <hr>
                    <form id="clientForm" >
                            {{csrf_field()}}
                            <!-- Text input-->
                            <div class="form-group">
                                <label  for="name">Nombre:</label>
                                    <input id="name" name="name" placeholder="" class="form-control " required=""
                                           type="text">
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="lastanem">Apellido (s):</label>

                                    <input id="lastname" name="lastname" placeholder="" class="form-control "
                                           required="" type="text">
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="email">Correo Electronico:</label>
                                    <input id="email" name="email" placeholder="" class="form-control" type="text">
                            </div>

                            <!-- Text input password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="password" >Contraseña:</label>
                                    <input id="password" name="password" placeholder="" class="form-control input-md"  type="password">
                            </div>
                            <!-- Text input confirmar password-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="cpassword">Confirme la contraseña:</label>
                                    <input id="cpassword" name="cpassword" placeholder="" class="form-control input-md" type="password">
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="phone">Telefono :</label>

                                    <input id="phone" name="phone" placeholder="111-111-11-11 ó 111-11-11"
                                           class="form-control input-md" type="text">

                            </div>
                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="username">Nombre de usuario:</label>

                                    <input id="username" name="username" placeholder="" class="form-control input-md"
                                           type="text">

                            </div>

                            <!-- File Button -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="photo">Foto: </label>
                                <input id="photo" name="photo" class="input-file" type="file">
                            </div>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-template-main" id="registro"><i class="fa fa-user-md"></i> Concluir Registro</a>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->
</div>
<!-- /#content -->
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/plugins/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/tienda/registro.js')}}"></script>
@endsection