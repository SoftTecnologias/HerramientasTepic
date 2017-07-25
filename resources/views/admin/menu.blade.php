<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset("img/user.png")}}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Usuario Muestra</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="{{ route('area.index') }}"><i class='fa fa-dashboard'></i> <span>Inicio</span></a></li>
            <li><a href="{{route('area.pedidos')}}"><i class='fa fa-cart-plus'></i> <span>Pedidos</span></a></li>
            <li><a href="{{route('area.productos')}}"><i class='fa fa-list-alt'></i> <span>Productos</span></a></li>
            <li><a href="{{route('area.marcas')}}"><i class='fa fa-shirtsinbulk'></i> <span>Marcas</span></a></li>
            <li><a href="{{route('area.categorias')}}"><i class='fa fa-tasks'></i> <span>Categorias</span></a></li>
            <li><a href="{{route('area.subcategorias')}}"><i class='fa fa-tags'></i><span>Subcategorias</span></a></li>
            <li><a href="{{route('area.usuarios')}}"><i class='fa fa-user'></i> <span>Usuarios</span></a></li>
            <li><a href="{{route('area.servicios')}}"><i class='fa fa-bug'></i> <span>Servicios</span></a></li>
            <li><a href="{{route('area.banner')}}"><i class="glyphicon glyphicon-wrench"></i><span>Banner</span></a></li>
            <li><a href="{{route('area.provider')}}"><i class="fa fa-truck"></i><span>Proveedores</span></a></li>
            <li><a href="{{route('area.movements')}}"><i class="fa fa-archive"></i> <span>Movimientos de almacén</span></a></li>


<!--php artisan make:controller ServiciosController --resource
            routes
            Route::resource('','');
            php artisan make:model Servicio -->

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
