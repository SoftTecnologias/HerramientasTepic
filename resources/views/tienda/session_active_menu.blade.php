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
                <a href="{{route('tienda.user.profile')}}"  >
                    <span class="hidden-xs text-uppercase">{{$user->name." ".$user->lastname}} </span></a>
                <a href="{{route('tienda.index')}}"><i class="fa fa-shopping-cart"></i> <span
                            class="hidden-xs text-uppercase">$ 0.00 (0 articulos)</span></a>
                <a href="{{route('area.logout',['id'=>1])}}"><i class="fa fa-sign-out"></i> <span
                            class="hidden-xs text-uppercase"></span>Cerrar Sesión</a>
            </div>
            <!-- Hasta aqui-->
        </div>
    </div>
</div>