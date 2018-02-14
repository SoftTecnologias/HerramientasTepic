<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/* Se carga y redirecciona a la pagina que sea (si existe el apikey guardada!)*/
//Sección de la tienda (index)

/*Area de tienda */
Route::group(['middleware' => 'web'],function(){
    Route::get('/', [
        'uses' => 'UsersController@getIndex',
        'as' => 'tienda.index'
    ]);
//Marcas
    Route::get('/brand/{id}',[
        'uses' => 'UsersController@getMarcaSearch',
        'as' => 'tienda.marcas'
    ]);
//Categorias
    Route::get('/category/{id}',[
        'uses' => 'UsersController@getCategoriaSearch',
        'as' => 'tienda.categorias'
    ]);

//Servicios
    Route::get('/servicios',[
        'uses' => 'UsersController@getAllServices',
        'as' => 'tienda.servicios'
    ]);

    Route::get('/servicios/detalle/{id}',[
        'uses' => 'UsersController@getServiceDetail',
        'as' => 'tienda.detalleServicio'
    ]);
//Productos
    Route::get('/productos/search/{filtro}',[
        'uses' => 'UsersController@searchProductos',
        'as' => 'tienda.search.productos'
    ]);
    /* Obtener formulario y hacer login */

    Route::post('/login',[
        'uses' => 'UsersController@doLogin',
        'as' => 'panel.dologin'
    ]);
    Route::post('/usuario/login',[
        'uses' => 'UsersController@doCLogin',
        'as' => 'panel.doclogin'
    ]);

//Registro de clientes!!
    Route::get('/usuario/registro',[
        'as' => 'tienda.registro',
        'uses' => 'UsersController@getRegisterForm'
    ]);

    Route::post('/usuario/registro',[
        'as' => 'tienda.registro',
        'uses' => 'UsuariosController@store'
    ]);

    Route::get('/user/infocompra/{id}',[
        'uses' => 'UsersController@getinfocompra'
    ]);

    Route::get('/usuario/{id}',[
        'uses' => 'UsersController@confirmEmail',
        'as' => 'tienda.confirmar'
    ]);
    Route::get('/usuario/situacion/{id}',[
        'uses' => 'UsersController@errorMail',
        'as' => 'tienda.problema'
    ]);
    Route::get('/user/profile',[
        'uses' => 'UsersController@getUserProfile',
        'as' => 'tienda.user.profile'
    ]);
    Route::post('/user/profile/{id}',[
        'uses' => 'UsuariosController@udateUser',
        'as' => 'tienda.user.profile.update'
    ]);

    Route::post('/user/direccion',[
        'uses' => 'DireccionesController@add',
        'as' => 'user.direccion'
    ]);

    Route::post('/user/profile/contact/{id}',[
        'uses' => 'DireccionesController@udateContactUser',
        'as' => 'tienda.user.profile.contact.update'
    ]);

    Route::post('/user/profile/pass/{id}',[
        'uses' => 'UsuariosController@updatePassword',
        'as' => 'tienda.user.profile.pass.update'
    ]);

    Route::post('/user/profile/imagen/{id}',[
        'uses' => 'UsuariosController@updateImage',
        'as' => 'tienda.user.profile.image.update'
    ]);

    Route::get('/getMunicipios/{estado}',[
        'uses' => 'UsersController@getMunicipios',
        'as' => 'getmuicipio.estado'
    ]);

    Route::get('/user/cart/',[
        'uses'=>'UsersController@getCarritoView',
        'as' => 'carrito.index'
    ]);

    Route::post('/user/cart/add',[
        'uses'=>"ShoppingCartController@addProduct",
        'as' => 'carrito.add'
    ]);

    Route::delete('/user/cart/removeToCart',[
        'uses' => "ShoppingCartController@removePartial",
        'as' => "carrito.removeCart"
    ]);

    Route::get('/user/cart/checkout',[
        'uses' => "UsersController@getCheckout",
        'as' => "carrito.getCheckout"
    ]);
    Route::get('/user/cart/printcheckout',[
        'uses' => "UsersController@printCheckout",
        'as' => "carrito.printCart"
    ]);
//Parte del checkout
    Route::get('/user/cart/makeCheckout',[
        'uses' => "ShoppingCartController@makeCheckout",
        'as' => "carrito.makeOrder"
    ]);

    Route::get('/user/cart/finishOrder',[
        'uses' => "ShoppingCartController@finishOrder",
        'as' => "carrito.finishOrder"
    ]);
    Route::get('/user/cart/complete',[
        'uses' => "UsersController@getFinish",
        'as' => "carrito.finish.Order"
    ]);

    Route::get('/user/cart/CancelCheckout',[
        'uses' => "ShoppingCartController@destroyOrder",
        'as' => "carrito.destroy.Order"
    ]);

    Route::post('/user/cart/updateCart',[
        'uses' => "ShoppingCartController@updateCart",
        'as' => "carrito.updateCart"
    ]);

    Route::get('/user/cart/payment',[
        'uses' => "ShoppingCartController@payment",
        'as' => 'carrito.payment'
    ]);

    Route::get('/user/cart/delivery',[
        'uses' => "UsersController@getDelivery",
        'as' => 'carrito.delivery'
    ]);

    Route::post('/user/cart/delivery',[
        'uses' => "ShoppingCartController@setDeliveryType",
        'as' => 'carrito.make.delivery'
    ]);

    Route::get('/user/cart/destroyOrder',[
        'uses' => "ShoppingCartController@destroyOrder",
        'as' => 'carrito.destroy.Order'
    ]);

    Route::get('/user/cart/addresses',[
        'uses' => "UsersController@addresses",
        'as' => 'carrito.addresses'
    ]);

    Route::post('/user/cart/addresses',[
        'uses' => "ShoppingCartController@setAddress",
        'as' => 'carrito.delivery.addresses'
    ]);
    Route::get('/user/cart/destroyAddresses',[
        'uses' => "UsersController@destroyAddresses",
        'as' => 'carrito.destroy.addresses'
    ]);

    Route::get('/user/cart/summary',[
        'uses' => "UsersController@summary",
        'as' => 'carrito.summary'
    ]);

    Route::get('/getLocalidades/{municipio}',[
        'uses' => 'UsersController@getLocalidades',
        'as' => 'getlocalidades.municipio'
    ]);

    Route::get('/user/cart/back',[
        'uses' => "UsersController@stepBack",
        'as' => 'carrito.back'
    ]);

    Route::get('/user/cart/step/{step}',[
        'uses' => "UsersController@goStep",
        'as' => 'carrito.goStep'
    ]);
});

/* Area de administracion */
Route::group(['prefix' => 'area','middleware' => 'web'],function(){
    /*Obtenemos los formularios ... */

    Route::get('/perfil',[
        'uses' => 'UsersController@getProfile',
        'as' => 'area.perfil'
    ]);

    Route::post('/pedidos/precioenvio',[
        'uses' => 'PedidosController@guardarPrecioEnvio',
        'as' => 'area.precio.envio'
    ]);

    Route::get('/getTrabajadores',[
        'uses' => 'UsersController@getTrabajadores',
        'as' => 'area.tra'
    ]);

    Route::get('/pedidos/detail/{id}',[
        'uses' => 'PedidosController@pedidoDetail',
        'as' => 'area.pedido.detail'
    ]);

    Route::post('/pedidos/regitra/{id}',[
        'uses' => 'PedidosController@asignar',
        'as' => 'area.asignar'
    ]);
    Route::post('/pedidos/accion/{id}',[
        'uses' => 'PedidosController@accion',
        'as' => 'area.desapchar'
    ]);

    Route::resource('/resource/pedidos','PedidosController');
    Route::resource('/resource/servicios','ServiciosController');

    Route::get('/servicios',[
       'uses' => 'UsersController@getServiciosForm',
        'as' => 'area.servicios'
    ]);

    Route::POST('/resource/servicios/{id}',[
       'uses' => 'ServiciosController@update',
        'as' => 'area.resource.servicios.update'
    ]);
    Route::POST('/resource/servicio/{id}',[
       'uses' => 'ServiciosController@verMiniatura',
        'as' => 'area.resource.servicio.verMiniatura'
    ]);

    Route::POST('/resource/servicio/{id}',[
       'uses' => 'serviciosController@verMiniatura',
        'as' => 'area.resource.servicio.verMiniatura'
    ]);

    Route::POST('/resource/verproducto/{id}',[
        'uses' => 'ProductosController@verMiniatura',
        'as' => 'area.resource.producto.verMiniatura'
    ]);

    Route::POST('/resource/marcaauthorizada/{id}',[
       'uses' => 'MarcasController@verMiniatura',
        'as' => 'area.resource.marca.verMiniatura'
    ]);


    Route::get('/',[
        'uses' => 'UsersController@getAreaIndex',
        'as' => 'area.index'
    ]);


    Route::get('/productos',[
        'uses' => 'UsersController@getProductosForm',
        'as' => 'area.productos'
    ]);

    Route::get('/marcas',[
        'uses' => 'UsersController@getMarcasForm',
        'as' => 'area.marcas'
    ]);

    Route::get('/categorias',[
        'uses' => 'UsersController@getCategoriasForm',
        'as' => 'area.categorias'
    ]);

    Route::get('/subcategoria',[
        'uses' => 'UsersController@getSubcategoriasForm',
        'as' => 'area.subcategorias'
    ]);

    Route::get('/usuarios',[
       'uses' => 'UsersController@getUsuariosForm',
        'as' => 'area.usuarios'
    ]);

    Route::get('/pedidos',[
        'uses' => 'UsersController@getPedidosForm',
        'as' => 'area.pedidos'
    ]);

    /* Recursos */

    Route::resource('/resource/banner','BannerController');
    Route::get('/banner',[
        'uses' => 'UsersController@getBannerForm',
        'as' => 'area.banner'
    ]);
    Route::post('/resource/banner/{id}',[
        'uses' => 'BannerController@update',
        'as' => 'area.resource.banner.update'
    ]);

    Route::resource('/resource/marcas','MarcasController');
    Route::post('/resource/marcas/{id}',[
        'uses' => 'MarcasController@update',
        'as' => 'area.resource.marca.update'
    ]);

    Route::resource('/resource/productos','ProductosController');
    Route::get('/productos/con',[
        'uses' => 'ProductosController@conimagen',
        'as' => 'filtro.con.imagenes'
    ]);
    Route::get('/productos/sin',[
        'uses' => 'ProductosController@sinimagen'
    ]);
    Route::get('/productos/all',[
        'uses' => 'ProductosController@all'
    ]);
    Route::post('/resource/productos/{id}',[
        'uses' => 'ProductosController@update',
        'as' => 'area.resource.post.update'
    ]);

    Route::resource('/resource/categorias','CategoriasController');
    Route::post('/resource/categorias/{id}',[
        'uses' => 'CategoriasController@update',
        'as' => 'area.resource.categorias.update'
    ]);

    Route::resource('/resource/subcategorias','SubcategoriasController');
    Route::post('/resource/subcategorias/{id}',[
        'uses' => 'SubcategoriasController@update',
        'as' => 'area.resource.subcategorias.update'
    ]);

    Route::resource('/resource/usuarios','UsuariosController');
    Route::post('/resource/usuarios/{id}',[
        'uses' => 'UsuariosController@update',
        'as' => 'area.resource.usuarios.update'
    ]);

    Route::post('/resource/usuarios/userprice/{id}',[
        'uses' => 'UsuariosController@updateUserPrice',
        'as' => 'area.resource.usuarios.updateUserPrice'
    ]);


    Route::post('/perfil',[
        'uses' => 'UsuariosController@areaProfileEdit',
        'as' => 'area.perfil.update'
    ]);

    Route::get('/logout',[
        'uses' => 'UsersController@doLogout',
        'as' => 'area.logout'
    ]);
    Route::resource('/resource/movimientos','MovimientosController');
    Route::get('/movimientos', [
        'uses'=>'UsersController@showMovementForm',
        'as' =>'area.movements'
        ]);
    Route::get('/resource/movimientos/{id}/detalle',[
        'uses' => 'MovimientosController@detailIndex',
        'as' => 'panel.resource.movimientos.indexdetalle'
    ]);
    Route::post('/resource/movimientos/{mov}/create',[
        'uses'=>'MovimientosController@create',
        'as' => 'area.resource.movimientos.detalle'
    ]);
    Route::POST('/resource/proveedores/update/{id}',[
        'uses' => 'proveedoresController@update',
        'as' => 'area.resource.proveedores.update'
    ]);

    Route::get("/resource/proveedores/municipios/{id}", [
        'uses' => 'ProveedoresController@municipios',
            'as' => 'municipio.get'
    ]);

    //Eliminar Detalle
    Route::delete('/resource/movimientos/detail/{id}',[
        'uses'=>'MovimientosController@removeDetail',
        'as' => 'area.resource.movimientos.remove'
    ]);
    Route::resource('/resource/proveedores','ProveedoresController');
    Route::get('/proveedores',[
        'uses'=>'UsersController@showProviderForm',
        'as' =>'area.provider'
    ]);

});

Route::group(['prefix' => 'sale'],function (){
    Route::get('/',[
        'uses' => 'UsersController@getAreaIndex',
        'as' => 'sale.index'
        ]);


});

/* Rutas para Ajax*/
Route::group(['prefix'=>'/api','middleware' => 'web'],function(){
    Route::get('/getsubcategoria/{id}',[
       'uses' => 'UsersController@getSubcategorias',
       'as' => 'api.subcategorias'
    ]);
    Route::get('/productos',[
        'uses' => 'UsersController@getProductos',
        'as' => 'api.productos'
    ]);

});

Route::get('pagenotfound',function(){
    abort(404);
});

Route::get('servererror',function(){
    abort(500);
});






