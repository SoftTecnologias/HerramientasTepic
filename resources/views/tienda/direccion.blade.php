@extends('layouts.tienda',['marcas'=>$marcas,'categorias'=>$categorias,'servicios'=>$servicios])

@section('content')
    <div id="cuerpo">
        <div class="container">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="#step-1" type="button" class="btn btn-primary btn-circle">
                            <i class="glyphicon glyphicon-exclamation-sign"></i></a>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">
                            <i class="fa fa-home"></i></a>
                    </div>
                    <div class="stepwizard-step">
                        <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">
                            <i class="glyphicon glyphicon-ok"></i></a>
                    </div>

                </div>
            </div>
            <form role="form">
                <div class="row setup-content" id="step-2">
                    <div class="col-xs-12">
                        <div class="col-md-12">
                            <h3>Informacion de Usuario</h3>
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Estado</label>
                                    <select id="estado" name="estado"  class="btn btn-default col-md-12">
                                        <option value="00">Seleccione un Estado</option>
                                        @foreach($estados as $estado)
                                            <option value="{{$estado->id_estado}}">{{$estado->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Municipio</label>
                                    <select id="municipio" name="municipio"  class="btn btn-default col-md-12">
                                        <option value="00">Seleccione un Municipio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject">
                                        Localidad</label>
                                    <select id="localidad" name="localidad"  class="btn btn-default col-md-12">
                                        <option value="00">Seleccione una Localidad</option>
                                    </select>
                                </div>
                            </div>
                            </div>

                            <h3>Direccion</h3>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Calle</label>
                                    <input id="calle1" name="calle1" placeholder="principal" class="form-control input-md" type="text">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Calle 2</label>
                                    <input id="calle2" name="calle2" placeholder="entre" class="form-control input-md" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Calle 3</label>
                                    <input id="calle3" name="calle3" placeholder="entre" class="form-control input-md" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Numero Exterior</label>
                                    <input id="numext" name="numext" placeholder="Numero Exterior" class="form-control input-md" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Codigo Postal</label>
                                    <input id="cp" name="cp" placeholder="C. P." class="form-control input-md" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Referencias</label>
                                    <textarea id="ref" name="ref" placeholder="Referenias Visuales" class="form-control input-md" type="text"></textarea>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Siguiente</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12">
                    <div class="container">
                        <h3>Advertencia!!!</h3>
                        <div class="row">
                            <h1 class="col-md-1"><span class="glyphicon glyphicon-exclamation-sign"></span></h1>
                            <h4 class="col-md-4">Antes de continuar rellene los datos que se le piden, puede posponer esta opcion pero sera redireccionado a la tienda</h4>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Siguiente</button>
        </div>


            <div class="row setup-content" id="step-3">
                <div class="col-xs-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <h3>Felicidades!!!</h3>
                        <div class="row center-block">


                                <h1 class="col-md-1"><span class="glyphicon glyphicon-ok"></span></h1>
                                <h4 class="col-md-7">Esta a un paso de terminar, si todo esta correcto por favor presionar el boton de guardar,
                                    sera redireccionado automaticamente al perfil del usuario, en caso contario favor de realizar las modificaciones necesarias antes de continuar</h4>
                        </div>
                    </div>
                    <a class="btn btn-primary pull-right" id="guardarDir" >Guardar</a>
                </div>
            </div>

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/tienda/direccion.css')}}" type="text/css"/>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('js/tienda/direccion.js')}}"></script>
@endsection


