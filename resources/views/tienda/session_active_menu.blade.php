<?php
$cookie = Illuminate\Support\Facades\Cookie::get("cliente");
?>
<div id="top">
    <div class="container">
        <div class="row">
            <div class="col-xs-5 contact">
                <p class="hidden-sm hidden-xs">Contactanos al 01-311-258-0540 o herramientas_tepic@hotmail.com.</p>
                <p class="hidden-md hidden-lg"><a href="#" data-animate-hover="pulse"><i
                                class="fa fa-phone"></i></a> <a href="#" data-animate-hover="pulse"><i
                                class="fa fa-envelope"></i></a>
                </p>
            </div>
            <!-- Con el ingreso cambiaremos este menu-->
            <div class="user-block">
                <a href="{{route('tienda.user.profile')}}">
                    <span class="hidden-xs text-uppercase">{{$user->name." ".$user->lastname}} </span></a>
                <a id="carrito" data-toggle="modal" data-target="#carrito-modal"><i class="fa fa-shopping-cart"></i>
                    <span
                            class="hidden-xs text-uppercase">$ {{number_format($cookie['carrito']->total, 2,".",",")}}
                        ({{$cookie['carrito']->cantidadProductos}} articulos)</span></a>
                <a href="{{route('area.logout',['id'=>1])}}"><i class="fa fa-sign-out"></i> <span
                            class="hidden-xs text-uppercase"></span>Cerrar Sesión</a>
            </div>
            <!-- Hasta aqui-->
        </div>
        <div class="modal fade" id="carrito-modal" tabindex="-2" role="dialog" aria-labelledby="Carrito"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" >Mi carrito de compras</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="cart-table" class="table">
                                <thead>
                                  <tr>
                                      <th>Producto</th>
                                      <th>Cantidad</th>
                                      <th>Precio Unitario</th>
                                      <th colspan="2">Total</th>
                                      <th></th>
                                  </tr>
                                </thead>
                                <tbody id="body-cart">
                                @if($cookie['carrito']->productos != null)
                                  @foreach($cookie['carrito']->productos as $producto)
                                    <tr>
                                        <td style="font-size:.80em; ">{{$producto['item']['name']}}</td>
                                        <td style="text-align: center;">{{$producto['cantidad']}}</td>
                                        <td style="text-align: center;">$ {{number_format($producto['item']['precio'], 2,".",",")}}</td>
                                        <td style="text-align: center;">$ {{number_format($producto['total'], 2,".",",")}}</td>
                                        <td style="text-align: center;"><a id='btnEliminar' onclick='removeToCart({{$producto['item']['id']}})'><i class='fa fa-trash-o'></i></a></td>
                                    </tr>
                                  @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" id="btnCheckout" class="btn btn-block btn-primary" style="font-size: 1.20em;">
                            Finalizar pedido ($ {{number_format($cookie['carrito']->total, 2,".",",")}}) </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>