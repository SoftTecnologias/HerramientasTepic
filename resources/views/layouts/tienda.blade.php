<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Herramienta y Servicios de Tepic</title>

    <meta name="keywords" content="">

    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,500,700,800'
          rel='stylesheet' type='text/css'>

    <!-- Bootstrap and Font Awesome css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <!-- Css animations  -->
{{ Html::style('css/tienda/animate.css')}}
<!-- Theme stylesheet, if possible do not edit this stylesheet -->
{{ Html::style('css/tienda/style.default.css')}}
<!-- Custom stylesheet - for your changes -->
{{ Html::style('css/tienda/custom.css')}}
<!-- Responsivity for older IE -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Favicon and apple touch icons-->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" sizes="57x57" href="img/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon" sizes="120x120" href="img/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon" sizes="144x144" href="img/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon" sizes="152x152" href="img/apple-touch-icon-152x152.png"/>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    @yield('styles')
    <!-- owl carousel css -->
    {{ Html::style('css/tienda/owl.carousel.css')}}
    {{ Html::style('css/tienda/owl.theme.css')}}

</head>

<body>
<div id="all">
    <header>

        <!-- *** TOP *** -->
        @include('tienda.sessionmenu')


        <!-- *** TOP END *** -->

        <!-- *** NAVBAR ***
_________________________________________________________ -->
        @include('tienda.menu',['marcas'=>$marcas,'categorias'=>$categorias,'servicios'=>$servicios])

        <!-- *** NAVBAR END *** -->

    </header>

    <!-- *** LOGIN MODAL ***
_________________________________________________________ -->

    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
        <div class="modal-dialog modal-sm">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="Login">Customer login</h4>
                </div>
                <div class="modal-body">
                    <form action="customer-orders.html" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="email_modal" placeholder="email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password_modal" placeholder="password">
                        </div>

                        <p class="text-center">
                            <button class="btn btn-template-main"><i class="fa fa-sign-in"></i> Log in</button>
                        </p>

                    </form>

                    <p class="text-center text-muted">Not registered yet?</p>
                    <p class="text-center text-muted"><a href="customer-register.html"><strong>Register now</strong></a>!
                        It is easy and done in 1&nbsp;minute and gives you access to special discounts and much more!
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- *** LOGIN MODAL END *** -->

@yield('content')
@include('tienda.footer',['servicios'=>$servicios])

</div>
<!-- /#all -->

<!-- #### JAVASCRIPT FILES ### -->

<!-- JQuery 3.1.1 -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="{{ asset('/js/tienda/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/tienda/waypoints.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/tienda/jquery.counterup.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/tienda/jquery.parallax-1.1.3.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/tienda/front.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/tienda/products.js')}}" type="text/javascript"></script>
<!-- owl carousel -->
<script src="{{ asset('/js/tienda/owl.carousel.min.js') }}" type="text/javascript"></script>
@yield('scripts')
</body>

</html>