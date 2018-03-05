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
                        <li>categorias</li>
                        <li>Revisión de pedido</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <p class="text-muted lead" id="cantidadProductos">Tienes {{$carrito['cantidadProductos']}} articulo(s) en tu
                        carrito.</p>
                </div>
                <div class="col-md-9 clearfix" id="basket">
                    <div class="box">
                        <form>
                            <div class="table-responsive">
                                <table class="table" id="checkoutTable">
                                    <thead>
                                    <tr>
                                        <th colspan="2">Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th colspan="2">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($carrito['productos'] != null)
                                        @foreach($carrito['productos'] as $producto)
                                            <tr id="row{{base64_decode($producto['item']['id'])}}" data-qty="{{$producto['cantidad']}}" data-id="{{$producto['item']['id']}}">
                                                <td>
                                                    <a href="#">
                                                        <img src="{{asset('img/productos/'.$producto['item']['photo'])}}" alt="{{$producto['item']['code']}}">
                                                    </a>
                                                </td>
                                                <td style="font-size:.80em; ">{{$producto['item']['name']}}</td>
                                                <td style="text-align: center;"><input type="number" value="{{$producto['cantidad']}}" class="form-control"></td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto['item']['precio'], 2,".",",")." ".$producto['item']['currency']}}</td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto['total'], 2,".",",")." ".$producto['item']['currency']}}</td>
                                                <td style="text-align: center;"><a id='btnEliminar'
                                                                                   onclick='removeToCart("{{$producto['item']['id']}}")'
                                                                                   data-toggle="modal"
                                                                                   data-target="#eliminar-modal"><i
                                                                class='fa fa-trash-o'></i></a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th colspan="2" id="total">$ {{number_format($carrito['total'], 2,".",",")}} MXN</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{ route('tienda.index')}}" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continuar Comprando</a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default" id="updateCart"><i class="fa fa-refresh"></i>Actualizar Carrito</a>
                                    <a href="{{route('carrito.makeOrder')}}" class="btn btn-template-main" id="next">Continuar con el metodo de envio <i class="fa fa-chevron-right"></i>
                                    </a>
                                    <br>
                                    <div class="pull-right">
                                        <a href="{{route('carrito.printCart')}}" class="btn btn-danger" id="printCart" target="_blank"><i class="fa fa-print"></i>Imprimir Carrito</a>
                                    </div>
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
                                    <th id="subtotal">$ {{number_format($carrito['total'], 2,".",",")}} MXN</th>
                                </tr>
                                <tr>
                                    <td>Envio y entrega</td>
                                    <th>Pendiente</th>
                                </tr>
                                <tr>
                                    <td>Impuestos </td>
                                    <th>Pendiente</th>
                                </tr>
                                <tr class="total">
                                    <td>Total</td>
                                    <th id="total">${{number_format($carrito['total']  , 2,".",",")}} MXN</th>
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