<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Herramienta y Servicios de Tepic</title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,500,700,800'
          rel='stylesheet' type='text/css'>

    <!-- Bootstrap and Font Awesome css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link media="all" type="text/css" rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.4.4/sweetalert2.min.css"/>
</head>
<body>
<div id="all">
    <header class="col-md-11 col-sm-11 col-lg-11">
        <div class="col-md-3 col-sm-3 col-lg-3">
                <img src="{{asset('/img/image.png')}}" alt="Herramientas de Tepic logo"
                     class="hidden-xs hidden-sm img-responsive"/>
                <img src="{{asset('/img/minilogo50.png')}}" alt="Herramientas de Tepic logo"
                     class="visible-xs visible-sm"><span
                        class="sr-only">Herramientas y Servicios de Tepic</span>
        </div>
        <div class=" col-md-5 col-lg-5 col-sm-5" style="text-align: center;">
            <h4><strong>Herramientas y Servicios de Tepic</strong></h4>
            <em>Mazatlan No. 177-A Col. Centro Tepic, Nayarit México, CP. 63000 herramientas_tepic@hotmail.com o 01-311-258-0540</em>
            <h4><u>Es un mundo de herramientas</u></h4>
        </div>
    </header>
    <hr width="100%">
    <div id="content" >
        <div class="row">
             <div class="well col-md-10  col-md-offset-1 col-sm-10 col-lg-10 col-sm-offset-1 col-lg-offset-1">
                <div class="row">
                    <div class="col-md-11 col-sm-11 col-lg-11">
                        <address>
                                <div class="col-md-3 col-sm-3 col-lg-3">
                                        <p>Cliente: <strong>{{$cliente['cliente']['name']}} {{$cliente['cliente']['lastname']}}</strong> Tel: {{$cliente['cliente']['phone']}} Email: {{$cliente['cliente']['email']}}
                                </div>
                                
                                <div class="col-md-3 col-sm-3 col-lg-3">
                                    <p> Dirección:
                                        @if($cliente['cliente']['address'] != null)
                                        {{$cliente['cliente']['address']->street}} #{{$cliente['cliente']['address']->streetnumber}}, Col. {{$cliente['cliente']['address']->neigborhood}}
                                        Tepic, Nayarit Mexico {{$cliente['cliente']['address']->zipcode}}
                                        @else
                                            <br>                    
                                            Tepic, Nayarit Mexico       
                                            <strong>No hay dirección capturada</strong>
                                        @endif
                                    </p>        
                                </div>
                        </address>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="clearfix" id="basket">
                    <div class="box">
                            <div class="table-responsive">
                                <table class="table" id="checkoutTable">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cantidad</th>
                                        <th>Codigo</th>
                                        <th colspan="2">Producto</th>
                                        <th>Marca</th>
                                        <th>Precio</th>
                                        <th colspan="2">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $index=1;?>
                                    @if($carrito['productos'] != null)
                                        @foreach($carrito['productos'] as $producto)
                                            <tr id="row{{base64_decode($producto['item']['id'])}}"
                                                data-qty="{{$producto['cantidad']}}"
                                                data-id="{{$producto['item']['id']}}">
                                                <td>{{$index}}</td>
                                                <td style="text-align: center;">{{$producto['cantidad']}}</td>
                                                <td>{{$producto['item']['code']}}</td>
                                                <td>
                                                    <a href="#">
                                                        <img src="{{asset('img/productos/'.$producto['item']['photo'])}}"
                                                             alt="{{$producto['item']['code']}}" width="50%" size="50%"/>
                                                    </a>
                                                </td>
                                                <td style="font-size:.80em; ">{{$producto['item']['name']}}</td>
                                                <td>{{$producto['item']['brand']}}</td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto['item']['precio'], 2,".",",")." ".$producto['item']['currency']}}</td>
                                                <td style="text-align: center;">
                                                    $ {{number_format($producto['total'], 2,".",",")." ".$producto['item']['currency']}}</td>

                                            </tr>
                                            <?php $index+=1;?>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th >Total</th>
                                        <th colspan="3" id="total">$ {{number_format($carrito['total'], 2,".",",")}}MXN
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col-md-9 -->
            </div>
            <div class="row">
                <ul>
                    <li>Este es un <strong><em>PRESUPUESTO</em></strong> es solo de caracter informativo</li>
                    <li>Los precios pueden variar al momento de la compra y no representa un compromiso de venta</li>
                    <li>La existencia está sujeta a disposicion en almacen</li>
                </ul>
            </div>
        </div>
        <!-- /.container -->
    </div>
</div>
<br><br>

</body>
</html>