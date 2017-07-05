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
Route::get('/', [
    'uses' => 'UsersController@getIndex',
    'as' => 'tienda.index'
]);

/* Obtener formulario y hacer login */
Route::get('/login',[
    'uses' => 'UsersController@getLoginForm',
    'as' => 'panel.login'
]);

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






