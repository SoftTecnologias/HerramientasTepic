@extends('layouts.tienda')
<?php

$cookie = Illuminate\Support\Facades\Cookie::get("cliente");
?>
<!-- Index -->
@section('content')
    <div id="heading-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1>Dirección de entrega</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route("carrito.index")}}">Inicio</a>
                        </li>
                        <li>Entrega</li>
                        <li>Dirección</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">

                <div class="col-md-9 clearfix" id="checkout">

                    <div class="box">
                        <form method="post" action="{{route('carrito.delivery.addresses')}}">
                            {{ csrf_field() }}
                            <ul class="nav nav-pills nav-justified">
                                <li><a href="{{route('carrito.goStep',['step'=>1])}}"><i class="fa fa-truck"></i><br>Metodo de entrega</a>
                                </li>
                                <li class="active"><a href="{{route('carrito.goStep',['step'=>2])}}"><i class="fa fa-map-marker"></i><br>Direccion de
                                        entrega</a>
                                </li>
                                <li class="disabled"><a href="#"><i class="fa fa-eye"></i><br>Resumen de venta</a>
                                </li>
                            </ul>

                            <div class="content">
                                <input type="text" hidden id="type">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" class="form-control" id="nombre"
                                                   value="{{$address->name}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="apellidos">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidos"
                                                   value="{{$address->lastname}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="estado">Estado</label>
                                            <select class="form-control" id="estado" disabled>
                                                @foreach( $estado as $edo)
                                                    <option {{($edo->id_estado == $address->state)? "selected": ""}}>{{$edo->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="municipio">Municipio</label>
                                            <select class="form-control" id="municipio" disabled>
                                                @foreach( $municipio as $mpo)
                                                    <option {{($mpo->id_municipio == $address->country)? "selected": ""}}>{{$mpo->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="localidad">Localidad</label>
                                            <select class="form-control" id="localidad" disabled>
                                                @foreach( $localidad as $loc)
                                                    <option {{($loc->id_localidad == $address->city)? "selected": ""}}>{{$loc->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="calle1">Calle</label>
                                            <input type="text" class="form-control" id="calle1"
                                                   value="{{$address->street}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="numero">Número</label>
                                            <input type="text" class="form-control" id="numero"
                                                   value="{{$address->streetnumber}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="calle2">Entre calle </label>
                                            <input type="text" class="form-control" id="calle2"
                                                   value="{{$address->street2}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label for="calle3">y calle</label>
                                            <input type="text" class="form-control" id="calle3"
                                                   value="{{$address->street3}}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="zip">Codigo postal</label>
                                            <input type="text" class="form-control" id="zip"
                                                   value="{{$address->zipcode}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="telefono">Telefono</label>
                                            <input type="text" class="form-control" id="telefono"
                                                   value="{{$address->phone}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="references">Referencias</label>
                                            <input type="text" class="form-control" id="references"
                                                   value="{{$address->reference}}" disabled>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.row -->
                            </div>

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{route("carrito.back")}}" class="btn btn-default"><i
                                                class="fa fa-chevron-left"></i>Regresar</a>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-template-main">Resumen de pago<i
                                                class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->


                </div>
                <!-- /.col-md-9 -->

                <div class="col-md-3">
                    <div class="box" id="order-summary">
                        <div class="box-header">
                            <h3>Resumen de orden</h3>
                        </div>
                        <p class="text-muted">El costo del envio es un estimado, el precio real de envio se acordará con el vendendor.</p>

                        <div class="table-responsive">
                            <table class="table" id="summary">
                                <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <th id="subtotal">$ {{number_format($details['subtotal'], 2,".",",")}} MXN</th>
                                </tr>
                                <tr>
                                    <td>Envio y entrega </td>
                                    @if($details['delivery_cost'] == -1)
                                        <th>pendiente</th>
                                    @else
                                        <th>{{$details['delivery_cost']}}</th>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Impuestos </td>
                                    @if($details['taxes'] == -1)
                                        <th>Pendiente</th>
                                    @else
                                        <th>{{$details['taxes']}}</th>
                                    @endif
                                </tr>
                                <tr class="total">
                                    <td>Total</td>
                                    <th id="total">${{number_format($details['total'] , 2,".",",")}} MXN</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- /.col-md-3 -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->
    </div>
@endsection