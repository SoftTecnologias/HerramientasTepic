<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel de Administración Herramientas y Servicios de Tepic</title>
    <!-- JQuery 3.1.1 -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    {{ Html::style('css/AdminLTE.min.css')}}
    {{ Html::style('css/skins/skin-blue.css') }}
    {{ Html::style('css/skins/_all-skins.css') }}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css"/>

    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link media="all" type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.4.4/sweetalert2.min.css" />
    @yield('styles')
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
    <!-- En esta parte colocaré el menú donde aparecé la sesión -->
    @include('partials.sesionmenu',['user_info',$user_info])
    <!-- aquí colocaré las operaciones del menu (administrador) -->
    @include('partials.menu',['user_photo'=>$user_info['photo'],
                              'user_name' => $user_info['username']
    ])
    <!-- contenido dinamico -->
    @yield('content')
    <!--footer del panel -->
    @include('partials.footer')
    <!-- AdminLTE App -->
    <script src="{{ asset('/js/admin/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/fastclick/fastclick.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.4.4/sweetalert2.min.js" type="text/javascript"></script>
    @yield('scripts')
</div>

</body>
