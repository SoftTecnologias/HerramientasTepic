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

/* Area de administracion */
Route::group(['prefix' => 'area'],function(){
    /*Obtenemos los formularios ... */

    Route::get('/perfil',[
        'uses' => 'UsersController@getProfile',
        'as' => 'area.perfil'
    ]);

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


    Route::post('/perfil',[
        'uses' => 'UsuariosController@areaProfileEdit',
        'as' => 'area.perfil.update'
    ]);

    Route::get('/logout',[
        'uses' => 'UsersController@doLogout',
        'as' => 'area.logout'
    ]);
});

/* Rutas para Ajax*/
Route::group(['prefix'=>'/api'],function(){
    Route::get('/getsubcategoria/{id}',[
       'uses' => 'UsersController@getSubcategorias',
       'as' => 'api.subcategorias'
    ]);
    Route::get('/productos',[
        'uses' => 'UsersController@getProductos',
        'as' => 'api.productos'
    ]);

});






