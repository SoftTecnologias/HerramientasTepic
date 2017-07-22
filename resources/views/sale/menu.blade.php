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
            <li class="active"><a href="{{ route('sale.index') }}"><i class='fa fa-dashboard'></i> <span>Inicio</span></a></li>
            <li><a href="#"><i class='fa fa-truck'></i> <span>Pedidos</span></a></li>


<!--php artisan make:controller ServiciosController --resource
            routes
            Route::resource('','');
            php artisan make:model Servicio -->

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
