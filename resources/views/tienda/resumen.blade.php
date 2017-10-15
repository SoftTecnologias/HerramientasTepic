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
                    <h1>Revisión de pedido</h1>
                </div>
                <div class="col-md-5">
                    <ul class="breadcrumb">
                        <li><a href="{{route("tienda.index")}}">Inicio</a>
                        </li>
                        <li>Orden</li>
                        <li>Resumen de pedido</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">

                <div class="col-md-9 clearfix" id="basket">
                    <div class="box">
                        <ul class="nav nav-pills nav-justified">
                            <li><a href="#"><i class="fa fa-truck"></i><br>Metodo de entrega</a>
                            </li>
                            <li class="disabled"><a href=""><i class="fa fa-map-marker"></i><br>Direccion de
                                    entrega</a>
                            </li>
                            <!-- <li class="disabled"><a href="#"><i class="fa fa-money"></i><br>Pago</a>
                            </li> -->
                            <li class="active"><a href="#"><i class="fa fa-eye"></i><br>Resumen de venta</a>
                            </li>
                        </ul>
                        <form>
                            <div class="table-responsive">
                                <table class="table" id="checkoutTable">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th colspan="3">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($details['detalles'] != null)
                                        @foreach($details['detalles'] as $producto)
                                            <tr id="row{{$producto->product()->first()->id}}" data-qty="{{$producto['qty']}}" data-id="{{$producto->product()->first()->id}}">
                                                <td>
                                                    <a href="#">
                                                        <img src="{{asset('img/productos/'.$producto->product()->first()->photo)}}" alt="{{$producto->product()->first()->code}}">
                                                    </a>
                                                </td>
                                                <td style="font-size:.80em; ">{{$producto->product()->first()->name}}</td>
                                                <td style="text-align: center;">{{$producto['qty']}}</td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto->product()->first()->price->scopePrice($cookie['userprice']), 2,".",",")." ".$producto->product()->first()->price->currency}}</td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto->product()->first()->price->scopePrice($cookie['userprice']) * $producto['qty'], 2,".",",")." ".$producto->product()->first()->currency}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th colspan="2" id="total">$ {{number_format($details['total'], 2,".",",")}} MXN</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{ url()->previous()}}" class="btn btn-default"><i class="fa fa-chevron-left"></i> Regresar</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{route('carrito.finishOrder')}}" class="btn btn-template-main" id="next">Finalizar pedido <i class="fa fa-chevron-right"></i>
                                    </a>
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
                                        <th>No aplica</th>
                                    @else
                                        <th>{{$details['delivery_cost']}}</th>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Impuestos </td>
                                    @if($details['taxes'] == -1)
                                        <th>No aplica</th>
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

        </div>
        <!-- /.container -->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('/js/tienda/checkout.js')}}" type="text/javascript"></script>
@endsection
