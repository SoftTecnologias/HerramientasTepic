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
                    <h1>Pedido - Metodo de pago</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route('tienda.index')}}">Inicio</a>
                        </li>
                        <li>Pedido</li>
                        <li>Metodo de pago</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">
                <div class="alert alert-warning {{($entrega) ? 'hidden' :''}}">
                    <strong>¡Hey!</strong> ¿Quieres envio a domicilio? ingresa a tu perfil y configura
                    tu <a href="{{route('tienda.user.profile')}}">perfil</a> para configurarlo
                </div>
                <div class="col-md-9 clearfix" id="checkout">

                    <div class="box">
                        <form method="post" action="{{route("carrito.make.delivery")}}">
                            {{ csrf_field() }}
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a href="#"><i class="fa fa-truck"></i><br>Metodo de entrega</a>
                                </li>
                                <li class="disabled"><a href=""><i class="fa fa-map-marker"></i><br>Direccion de entrega</a>
                                </li>
                                <li class="disabled"><a href="#"><i class="fa fa-eye"></i><br>Resumen de venta</a>
                                </li>
                            </ul>

                            <div class="content">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="box shipping-method">

                                            <h4>Recoger en sucursal</h4>

                                            <p>Con pago previo.</p>

                                            <div class="box-footer text-center">
                                                <input type="radio" name="delivery" value="1" checked="checked">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="box shipping-method">

                                            <h4>Recoger en sucursal</h4>

                                            <p>Pagar total en la sucursal</p>

                                            <div class="box-footer text-center">
                                                <input type="radio" name="delivery" value="2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 {{($entrega) ? '' :'hidden'}}">
                                        <div class="box shipping-method">

                                            <h4>Entrega a domicilio</h4>

                                            <p>Entrega localmente (Nayarit).</p>

                                            <div class="box-footer text-center">

                                                <input type="radio" name="delivery" value="3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 {{($entrega) ? '' :'hidden'}}">
                                        <div class="box shipping-method">

                                            <h4>Envio a toda la Republica Mexicana</h4>

                                            <p>Envio a cualquier parte del pais .</p>

                                            <div class="box-footer text-center">

                                                <input type="radio" name="delivery" value="4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.row -->

                            </div>
                            <!-- /.content -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{route("carrito.destroy.Order")}}" class="btn btn-default"><i class="fa fa-chevron-left"></i>Regresar al Checkout</a>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-template-main" id="btnContinuar">Continuar<i class="fa fa-chevron-right"></i>
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
@section('scripts')
    <script src="{{asset('/js/tienda/delivery.js')}}" type="text/javascript"></script>
@endsection