@extends('layouts.usuario')

@section('content')
    <form class="form-horizontal">

            <!-- Form Name -->
            <legend><span>Informacion de Contacto</span></legend>

            <!-- Text input-->
            <div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Estado</label>
                <div class="col-md-4">

                    <select id="estado" name="estado"  class="btn btn-default col-md-12">
                        <option value="00">Seleccione un Estado</option>
                    @foreach($estados as $estado)
                            <option value="{{$estado->id_estado}}">{{$estado->nombre}}</option>
                    @endforeach
                    </select>

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Municipio</label>
                <div class="col-md-4">
                    <select id="municipio" name="municipio"  class="btn btn-default col-md-12">
                        <option value="00">Seleccione un Municipio</option>
                    </select>

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Localidad</label>
                <div class="col-md-4">
                    <select id="localidad" name="localidad"  class="btn btn-default col-md-12">
                        <option value="00">Seleccione una Localidad</option>
                    </select>

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Calle</label>
                <div class="col-md-4">
                    <input id="calle1" name="calle1" placeholder="principal" class="form-control input-md" type="text">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Calle 2</label>
                <div class="col-md-4">
                    <input id="calle2" name="calle2" placeholder="entre" class="form-control input-md" type="text">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Calle 3</label>
                <div class="col-md-4">
                    <input id="calle3" name="calle3" placeholder="entre" class="form-control input-md" type="text">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Numero Exterior</label>
                <div class="col-md-4">
                    <input id="numext" name="numext" placeholder="Numero Exterior" class="form-control input-md" type="text">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Codigo Postal</label>
                <div class="col-md-4">
                    <input id="cp" name="cp" placeholder="C. P." class="form-control input-md" type="text">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Referencias</label>
                <div class="col-md-4">
                    <textarea id="ref" name="ref" placeholder="Referenias Visuales" class="form-control input-md" type="text"></textarea>

                </div>
            </div>

            <div class="form-group" >
                <div class="col-md-7"></div>
                <a class="btn btn-primary" id="guardarDir">Guardar</a>
            </div>
            </div>

    </form>


@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('js/tienda/direccion.js')}}"></script>
@endsection