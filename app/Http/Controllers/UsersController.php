<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Marca;
use App\Roles;
use App\Servicio;
use App\Subcategoria;
use App\Usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Redis\Database;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use \Exception;



class UsersController extends Controller
{
    /*
     * la cookies seran llamadas de distintas maneras
     *  cliente: para los usuarios con rol de cliente
     *  admin: para vendedores y administradores
     * */


    

      /*Parte de la tienda de Herramientas Tepic*/
    public function getIndex(Request $request){
        if ($request->cookie('cliente') != null) {
            //Se tomará en cuenta si hay una session de cliente para el carrito
        } else {
            //no existe una sesion y lo manda a la tienda (se colocará una liga al panel)
            $banner = DB::table('banner_principal')->get();
            $productos = DB::table('product')
                ->select('product.code',
                    'product.name',
                    'product.stock',
                    'product.currency',
                    'product.photo',
                    'product.photo2',
                    'product.photo3',
                    'product.shortdescription',
                    'product.longdescription',
                    'product.quotation',
                    'product.show'
                //         ,'price.price1'
                )
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('show',1)
                ->take(39)->get();
            $bMarcas = DB::table('brand')
                ->select('logo')
                ->where('logo', 'not like', 'minilogo.png')
                ->where('authorized','=',1)
                ->take(12)->get();
            //Menu de marcas
            $marcas = DB::table('brand')->select('id', 'name')
                ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->take(40)->orderBy('name', 'asc')->get();
            //Encriptamos los id
            foreach ($marcas as $marca)
                $marca->id = base64_encode($marca->id);
            //Menu de categorias
            $categorias = DB::table('category')->select('id', 'name')->take(40)
                ->where('name', 'not like', 'Nota de credito')
                ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->orderBy('name', 'asc')->get();
            //Encriptar id de categorias
            foreach ($categorias as $categoria)
                $categoria->id = base64_encode($categoria->id);
            //menu de servicios
            $servicios = DB::table('services')->select('id', 'title','img','shortdescription','longdescription','show')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            return view('tienda.index', ['banner' => $banner, 'productos' => $productos, 'bMarcas' => $bMarcas, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios]);
        }
    }
    /*Parte administratia de Herramientas Tepic */
    public function getAreaIndex(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('admin.area', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('sale.area', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Vendedor']
                ]);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getProductosForm(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                $categorias = Categoria::all();
                $marcas = Marca::all();
                return view('forms.productos', [
                    'marcas' => $marcas,
                    'categorias' => $categorias,
                    'usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                        'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                        'photo' => $user->photo,
                        'username' => $user->username,
                        'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
        $categorias = Categoria::all();
        $marcas = Marca::all();
        return view('forms.productos', [
            'marcas' => $marcas,
            'categorias' => $categorias
        ]);
    }
    public function getMarcasForm(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.marcas', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getCategoriasForm(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.categorias', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getSubcategoriasform(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $categorias = Categoria::all();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.subcategorias', ['categorias' => $categorias, 'usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getUsuariosForm(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $roles = Roles::all();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.usuarios', ['roles' => $roles, 'usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getPedidosForm(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.pedidos', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getServiciosForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.servicios',['usuario' => $user, 'datos'=>['name'=>$user->name. ' '. $user->lastname ,
                    'ingreso' =>  Carbon::createFromDate($fecha[0],$fecha[1],$fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            }elseif ($cookie['rol'] == 3){
                //vendedor solo que tenga el id 3
                return view('sale.area',['nombre'=> 'Vendedor puñetas']);
            }
        }else{
            //no existe una session de administrador y lo manda al login
            return view('login');

        }
    }
    public function getBannerForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.banner',['usuario' => $user, 'datos'=>['name'=>$user->name. ' '. $user->lastname ,
                    'ingreso' =>  Carbon::createFromDate($fecha[0],$fecha[1],$fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            }elseif ($cookie['rol'] == 3){
                //vendedor solo que tenga el id 3
                return view('sale.area',['nombre'=> 'Vendedor puñetas']);
            }
        }else{
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getSubcategorias(Request $request, $id){
        $subcategorias = DB::table('subcategory')->where('categoryid', $id)->get();
        $respuesta = ['code' => 200,
            'msg' => json_encode($subcategorias),
            'detail' => 'OK'
        ];

        return Response::json($respuesta);
    }
    public function searchProductos(Request $request){
        $x = "%".base64_decode($request->filtro)."%";
        //parte de los filtros
        $url =base64_decode($request->filtro);
        $request->filtro = base64_decode($request->filtro);
        $precioUsuario = $this->precioUsuario($request);
        $consultaPrecios= null;
        $consultaCategorias = null;
        $consultaSubcategorias = null;
        $consultaMarcas = null;
        $select=[
            'product.code',
            'product.name',
            'product.stock',
            'product.currency',
            'product.photo',
            'product.photo2',
            'product.photo3',
            'product.shortdescription',
            'product.longdescription',
            'product.quotation'
        ];
        if($precioUsuario!=""){
            array_push($select,$precioUsuario);
        }
        if(strpos($url, "/")){ //Existe al menos un parametro a parte del id
            $partes = explode("/",$url); //Separamos el id y los parametros
            $urlid = $partes[0]; //ID en cuestion
            $parametros = explode('&',$partes[1]); //Separamos los parametros con &
            foreach ( $parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                $filtro = explode('=',$parametro); //Separamos filtros[0] y valor[1]
                switch ($filtro[0]){
                    case 'precio': //Revisamos los valores que podria tener
                        $valores = explode(',',$filtro[1]); //Valores a filtrar
                        $consultaPrecios= "(";
                        if(sizeof($valores)>1){
                            $i = 0;
                            foreach ($valores as $valor){ //Recorremos los valores del filtro
                                if(strpos($valor,'-')){ //Existe un rango
                                    $precios =explode('-', $valor);
                                    if ( $i == 0){
                                        $consultaPrecios .= "(".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                    } else {
                                        $consultaPrecios .= " OR (".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                    }
                                } else { //no existe el rango
                                    if( $i == 0 ){
                                        $consultaPrecios.= "( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                    } else {
                                        $consultaPrecios.= " OR ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                    }
                                }
                                $i++;
                            }
                        } else { //Solo hay un filtro
                            if(strpos($valores[0],'-')){ //Existe un rango
                                $rango = explode('-',$valores[0]);
                                $consultaPrecios .= "(". (( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $rango[0] . " AND " . $rango[1] . ") ";
                            } else {
                                $consultaPrecios.= " ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valores[0] )";
                            }
                        }
                        $consultaPrecios .= ")";
                        break;
                    case 'categoria':
                        $valores = explode(',',$filtro[1]); //Valores a filtrar
                        $consultaCategorias="(";
                        if(sizeof($valores)>1) {

                            $consultaCategorias .= " product.categoryid IN( ";
                            $i=0;
                            foreach ($valores as $valor){ //Recorremos los valores del filtro
                                if($i == 0){
                                    $consultaCategorias .= $valor ;
                                    $i++;
                                }else{
                                    $consultaCategorias .= ", $valor";
                                }
                            }
                            $consultaCategorias .= " ) ";

                        } else { //Solo hay un filtro
                            $consultaCategorias .= "( product.categoryid = $valores[0] ) ";
                        }
                        $consultaCategorias .= ' )';
                        break;
                    case 'subcategoria':
                        $valores = explode(',',$filtro[1]); //Valores a filtrar
                        $consultaSubcategorias="(";
                        if(sizeof($valores)>1) {
                            $consultaSubcategorias .= " product.subcategoryid IN( ";
                            $i=0;
                            foreach ($valores as $valor){ //Recorremos los valores del filtro
                                if($i == 0){
                                    $consultaSubcategorias .= $valor ;
                                    $i++;
                                }else{
                                    $consultaSubcategorias .= ", $valor";
                                }
                            }
                            $consultaSubcategorias .= " ) ";

                        } else { //Solo hay un filtro
                            $consultaSubcategorias .= "( product.subcategoryid = $valores[0] ) ";
                        }
                        $consultaSubcategorias .= ' )';
                        break;
                    case 'marca':
                        $valores = explode(',',$filtro[1]); //Valores a filtrar
                        $consultaMarcas="(";
                        if(sizeof($valores)>1) {
                            $consultaMarcas .= " product.brandid IN( ";
                            $i=0;
                            foreach ($valores as $valor){ //Recorremos los valores del filtro
                                if($i == 0){
                                    $consultaMarcas .= $valor ;
                                    $i++;
                                }else{
                                    $consultaMarcas .= ", $valor";
                                }
                            }
                            $consultaMarcas .= " ) ";
                        } else { //Solo hay un filtro
                            $consultaMarcas .= "( product.brandid = $valores[0] ) ";
                        }
                        $consultaMarcas .= ' )';
                        break;
                }
            }
        }else{
            $urlid= $url;
        }
        $filtromarcas = [];
        $filtrobusqueda = DB::table('product')
            ->select($select)
            ->join('price', 'price.id', '=', 'product.priceid')
            ->where('photo', 'not like', 'minilogo.png')
            ->where('name','like','%'.$urlid.'%');
        /*Aplicacion de los filtros con o sin precios ... */
        if( $consultaPrecios != null ){
            $filtrobusqueda->whereRaw($consultaPrecios);
            /* Parte de los filtros con precios */
            $todasCategorias = DB::table('category')
                ->select('category.id',
                    'category.name',
                    DB::raw("(SELECT count(*) FROM product  inner join price on product.id = price.id where  product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.name like '%$urlid%' AND $consultaPrecios) as total"))
                ->where(DB::raw("(SELECT count(*) FROM product inner join price on product.id = price.id where  product.categoryid = category.id AND product.photo not like 'minilogo.png'  AND product.name like '%$urlid%' AND $consultaPrecios)"), '>', 0)
                ->orderBy('name', 'asc')
                ->get();
            //Filtro  de categorias y subcategorias
            $filtroCategorias = [];
            foreach ($todasCategorias as $categoria) {
                $subcategorias = DB::table('subcategory')
                    ->select('subcategory.id',
                        'subcategory.name',
                        DB::raw("(SELECT COUNT(*) FROM product  inner join price on product.id = price.id where  product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png'  AND product.name like '%$urlid%'  AND $consultaPrecios) as total"))
                    ->where(DB::raw("(SELECT COUNT(*) FROM product inner join price on product.id = price.id where  product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png'  AND product.name like '%$urlid%'  AND $consultaPrecios)"), '>', 0)
                    ->orderBy('name', 'asc')->get();
                foreach ($subcategorias as $subcategoria)
                    $subcategoria->id = base64_encode($subcategoria->id);
                array_push($filtroCategorias, ['id' => base64_encode($categoria->id), 'name' => $categoria->name, 'total' => $categoria->total, 'subcategorias' => $subcategorias]);

            }
        }
        else{
            /* Parte de los filtros */
            $filtromarcas = DB::table('brand')
                ->select('id', 'name',
                    DB::raw("(select COUNT(*) from product  where brand.id = product.brandid and product.name like '%$urlid%' and product.photo not like 'minilogo.png') as total"))
                ->where(DB::raw("(select COUNT(*) from product where brand.id = product.brandid and product.photo not like 'minilogo.png' and product.name like '%$urlid%' )"), '>', 0)
                ->orderBy('name', 'asc')
                ->get();
            foreach ($filtromarcas as $item)
                $item->id = base64_encode($item->id);
            $todasCategorias = DB::table('category')
                ->select('id',
                    'name',
                    DB::raw("(SELECT count(*) FROM product where product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.name like '%$urlid%' ) as total"))
                ->where(DB::raw("(SELECT count(*) FROM product where product.categoryid = category.id AND product.photo not like 'minilogo.png'  AND product.name like '%$urlid%' )"), '>', 0)
                ->orderBy('name', 'asc')
                ->get();
            //Filtro  de categorias y subcategorias
            $filtroCategorias = [];
            //dd( ($consulta != ""  )? 'AND '.$consulta :"falso bitch" );
            foreach ($todasCategorias as $categoria) {
                $subcategorias = DB::table('subcategory')
                    ->select('id',
                        'name',
                        DB::raw("(SELECT COUNT(*) FROM product where product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' AND product.name like '%$urlid%'  ) as total"))
                    ->where(DB::raw("(SELECT COUNT(*) FROM product where product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' AND product.name like '%$urlid%'  )"), '>', 0)
                    ->orderBy('name', 'asc')->get();
                foreach ($subcategorias as $subcategoria)
                    $subcategoria->id = base64_encode($subcategoria->id);
                array_push($filtroCategorias, ['id' => base64_encode($categoria->id), 'name' => $categoria->name, 'total' => $categoria->total, 'subcategorias' => $subcategorias]);
            }


        }
        /* Aplicacion Recuperación de los productos con o sin filtros*/

        if( $consultaCategorias != null ){
            $filtrobusqueda->whereRaw($consultaCategorias);
        }
        if( $consultaSubcategorias != null ){
            $filtrobusqueda->whereRaw($consultaSubcategorias);
        }
        if( $consultaMarcas != null ){
            $filtrobusqueda->whereRaw($consultaMarcas);
        }
        $filtrobusqueda = $filtrobusqueda->paginate();



        // Parte del menu
        if ($request->cookie('cliente') != null) {
            //Se tomará en cuenta si hay una session de cliente para el carrito
        } else {
            //no existe una sesion y lo manda a la tienda (se colocará una liga al panel)
            //Menu de marcas
            $marcas = DB::table('brand')->select('id', 'name')
                ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->take(40)->orderBy('name', 'asc')->get();
            //Encriptamos los id
            foreach ($marcas as $marca)
                $marca->id = base64_encode($marca->id);
            //Menu de categorias
            $categorias = DB::table('category')->select('id', 'name')->take(40)
                ->where('name', 'not like', 'Nota de credito')
                ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->orderBy('name', 'asc')->get();
            //Encriptar id de categorias
            foreach ($categorias as $categoria)
                $categoria->id = base64_encode($categoria->id);
            //menu de servicios
            $servicios = DB::table('services')->select('id', 'title','img','shortdescription','longdescription','show')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);



            return view('tienda.buscaProductos', ['marcas' => $marcas,
                                                        'categorias' => $categorias,
                                                        'servicios' => $servicios,
                                                        'filtro' => $filtrobusqueda,
                                                        'f' => $urlid,
                                                        'filtroCategorias' => $filtroCategorias,
                                                        'filtroMarcas' => $filtromarcas]);
        }
    }
    //Vistas con filtros
    public function getMarcaSearch(Request $request, $id){
        try{
            $url = base64_decode($id);
            $precioUsuario = $this->precioUsuario($request);
            $consultaPrecios= null;
            $consultaCategorias = null;
            $consultaSubcategorias = null;
            $select=[
                'product.code',
                'product.name',
                'product.stock',
                'product.currency',
                'product.photo',
                'product.photo2',
                'product.photo3',
                'product.shortdescription',
                'product.longdescription',
                'product.quotation'
            ];
            if($precioUsuario!=""){
                array_push($select,$precioUsuario);
            }
            if(strpos($url, "/")){ //Existe al menos un parametro a parte del id
                $partes = explode("/",$url); //Separamos el id y los parametros
                $urlid = $partes[0]; //ID en cuestion
                $parametros = explode('&',$partes[1]); //Separamos los parametros con &
                foreach ( $parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                   $filtro = explode('=',$parametro); //Separamos filtros[0] y valor[1]
                   switch ($filtro[0]){
                       case 'precio': //Revisamos los valores que podria tener
                            $valores = explode(',',$filtro[1]); //Valores a filtrar
                            $consultaPrecios= "(";
                            if(sizeof($valores)>1){
                                $i = 0;
                                foreach ($valores as $valor){ //Recorremos los valores del filtro
                                    if(strpos($valor,'-')){ //Existe un rango
                                        $precios =explode('-', $valor);
                                        if ( $i == 0){
                                            $consultaPrecios .= "(".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        } else {
                                            $consultaPrecios .= " OR (".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        }
                                    } else { //no existe el rango
                                        if( $i == 0 ){
                                            $consultaPrecios.= "( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                        } else {
                                            $consultaPrecios.= " OR ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                        }
                                    }
                                    $i++;
                                }
                            } else { //Solo hay un filtro
                                if(strpos($valores[0],'-')){ //Existe un rango
                                    $rango = explode('-',$valores[0]);
                                    $consultaPrecios .= "(". (( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $rango[0] . " AND " . $rango[1] . ") ";
                                } else {
                                    $consultaPrecios.= " ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valores[0] )";
                                }
                            }
                           $consultaPrecios .= ")";
                       break;
                       case 'categoria':
                           $valores = explode(',',$filtro[1]); //Valores a filtrar
                           $consultaCategorias="(";
                           if(sizeof($valores)>1) {

                               $consultaCategorias .= " product.categoryid IN( ";
                               $i=0;
                               foreach ($valores as $valor){ //Recorremos los valores del filtro
                                   if($i == 0){
                                       $consultaCategorias .= $valor ;
                                       $i++;
                                   }else{
                                       $consultaCategorias .= ", $valor";
                                   }
                               }
                               $consultaCategorias .= " ) ";

                           } else { //Solo hay un filtro
                                 $consultaCategorias .= "( product.categoryid = $valores[0] ) ";
                           }
                           $consultaCategorias .= ' )';
                       break;
                       case 'subcategoria':
                           $valores = explode(',',$filtro[1]); //Valores a filtrar
                           $consultaSubcategorias="(";
                           if(sizeof($valores)>1) {
                               $consultaSubcategorias .= " product.subcategoryid IN( ";
                               $i=0;
                               foreach ($valores as $valor){ //Recorremos los valores del filtro
                                   if($i == 0){
                                       $consultaSubcategorias .= $valor ;
                                       $i++;
                                   }else{
                                       $consultaSubcategorias .= ", $valor";
                                   }
                               }
                               $consultaSubcategorias .= " ) ";

                           } else { //Solo hay un filtro
                               $consultaSubcategorias .= "( product.subcategoryid = $valores[0] ) ";
                           }
                           $consultaSubcategorias .= ' )';
                       break;
                   }
                }
            }else{
                $urlid= $url;
            }
            $productos = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('brandid', '=', $urlid);
            /*Aplicacion de los filtros con o sin precios ... */
            if( $consultaPrecios != null ){
                $productos->whereRaw($consultaPrecios);
                /* Parte de los filtros con precios */
                $todasCategorias = DB::table('category')
                    ->select('category.id',
                        'category.name',
                        DB::raw("(SELECT count(*) FROM product  inner join price on product.id = price.id where  product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.brandid = $urlid AND $consultaPrecios) as total"))
                    ->where(DB::raw("(SELECT count(*) FROM product inner join price on product.id = price.id where  product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.brandid = $urlid AND $consultaPrecios)"), '>', 0)
                    ->orderBy('name', 'asc')
                    ->get();
                //Filtro  de categorias y subcategorias
                $filtroCategorias = [];
                foreach ($todasCategorias as $categoria) {
                    $subcategorias = DB::table('subcategory')
                        ->select('subcategory.id',
                            'subcategory.name',
                            DB::raw("(SELECT COUNT(*) FROM product  inner join price on product.id = price.id where  product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' and product.brandid = $urlid AND $consultaPrecios) as total"))
                        ->where(DB::raw("(SELECT COUNT(*) FROM product inner join price on product.id = price.id where  product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' and product.brandid = $urlid AND $consultaPrecios)"), '>', 0)
                        ->orderBy('name', 'asc')->get();
                    foreach ($subcategorias as $subcategoria)
                        $subcategoria->id = base64_encode($subcategoria->id);
                    array_push($filtroCategorias, ['id' => base64_encode($categoria->id), 'name' => $categoria->name, 'total' => $categoria->total, 'subcategorias' => $subcategorias]);

                }
            }else{
                /* Parte de los filtros */
                $todasCategorias = DB::table('category')
                    ->select('id',
                        'name',
                        DB::raw("(SELECT count(*) FROM product where product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.brandid = $urlid) as total"))
                    ->where(DB::raw("(SELECT count(*) FROM product where product.categoryid = category.id AND product.photo not like 'minilogo.png' AND product.brandid = $urlid)"), '>', 0)
                    ->orderBy('name', 'asc')
                   ->get();
                //Filtro  de categorias y subcategorias
                $filtroCategorias = [];
                //dd( ($consulta != ""  )? 'AND '.$consulta :"falso bitch" );
                foreach ($todasCategorias as $categoria) {
                    $subcategorias = DB::table('subcategory')
                        ->select('id',
                            'name',
                            DB::raw("(SELECT COUNT(*) FROM product where product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' and product.brandid = $urlid ) as total"))
                        ->where(DB::raw("(SELECT COUNT(*) FROM product where product.subcategoryid = subcategory.id AND product.categoryid = $categoria->id
                                           AND product.photo not like 'minilogo.png' and product.brandid = $urlid )"), '>', 0)
                        ->orderBy('name', 'asc')->get();
                    foreach ($subcategorias as $subcategoria)
                        $subcategoria->id = base64_encode($subcategoria->id);
                    array_push($filtroCategorias, ['id' => base64_encode($categoria->id), 'name' => $categoria->name, 'total' => $categoria->total, 'subcategorias' => $subcategorias]);
                }
            }
            /* Aplicacion Recuperación de los productos con o sin filtros*/
            if( $consultaCategorias != null ){
                $productos->whereRaw($consultaCategorias);
            }
            if( $consultaSubcategorias != null ){
                $productos->whereRaw($consultaSubcategorias);
            }
            $productos = $productos->paginate(12);

            /*----------------------  Parte del Menu --------------------------*/
            //Menu de marcas
            $marcas = DB::table('brand')->select('id', 'name')
                ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->take(40)->orderBy('name', 'asc')->get();
            //Encriptamos los id
            foreach ($marcas as $marca)
                $marca->id = base64_encode($marca->id);
            //Menu de categorias
            $categorias = DB::table('category')->select('id', 'name')->take(40)
                ->where('name', 'not like', 'Nota de credito')
                ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->orderBy('name', 'asc')->get();
            //Encriptar id de categorias
            foreach ($categorias as $categoria)
                $categoria->id = base64_encode($categoria->id);
            //menu de servicios
            $servicios = DB::table('services')->select('id', 'title','shortdescription','longdescription','img')->take(10)->orderBy('title', 'asc')->get();
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            //Marca actual (Migaja)
            $actual = Marca::find($urlid);
            return view('tienda.marcas', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroCategorias' => $filtroCategorias]);
        }catch(Exception $e){
           //return redirect()->route('tienda.index')->with(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
            return Response::json(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
        }
    }
    public function getCategoriaSearch(Request $request, $id)
    {
        try{
            $url = base64_decode($id);
            $precioUsuario = $this->precioUsuario($request);
            $consultaPrecios= null;
            $consultaMarcas=null;
            $consultaSubcategorias = null;
            $select=[
                'product.code',
                'product.name',
                'product.stock',
                'product.currency',
                'product.photo',
                'product.photo2',
                'product.photo3',
                'product.shortdescription',
                'product.longdescription',
                'product.quotation'
            ];
            if($precioUsuario!=""){
                array_push($select,$precioUsuario);
            }
            if(strpos($url, "/")){ //Existe al menos un parametro a parte del id
                $partes = explode("/",$url); //Separamos el id y los parametros
                $urlid = $partes[0]; //ID en cuestion
                $parametros = explode('&',$partes[1]); //Separamos los parametros con &
                foreach ( $parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                    $filtro = explode('=',$parametro); //Separamos filtros[0] y valor[1]
                    switch ($filtro[0]){
                        case 'precio': //Revisamos los valores que podria tener
                            $valores = explode(',',$filtro[1]); //Valores a filtrar
                            $consultaPrecios= "(";
                            if(sizeof($valores)>1){
                                $i = 0;
                                foreach ($valores as $valor){ //Recorremos los valores del filtro
                                    if(strpos($valor,'-')){ //Existe un rango
                                        $precios =explode('-', $valor);
                                        if ( $i == 0){
                                            $consultaPrecios .= "(".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        } else {
                                            $consultaPrecios .= " OR (".(( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        }
                                    } else { //no existe el rango
                                        if( $i == 0 ){
                                            $consultaPrecios.= "( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                        } else {
                                            $consultaPrecios.= " OR ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valor )";
                                        }
                                    }
                                    $i++;
                                }
                            } else { //Solo hay un filtro
                                if(strpos($valores[0],'-')){ //Existe un rango
                                    $rango = explode('-',$valores[0]);
                                    $consultaPrecios .= "(". (( $precioUsuario == "" )? "price.price1": $precioUsuario )." between " . $rango[0] . " AND " . $rango[1] . ") ";
                                } else {
                                    $consultaPrecios.= " OR ( ".(( $precioUsuario == "" )? "price.price1": $precioUsuario )."> $valores[0] )";
                                }
                            }
                            $consultaPrecios .= ")";
                            break;
                        case 'marca':
                            $valores = explode(',',$filtro[1]); //Valores a filtrar
                            $consultaMarcas="(";
                            if(sizeof($valores)>1) {
                                $consultaMarcas .= " product.brandid IN( ";
                                $i=0;
                                foreach ($valores as $valor){ //Recorremos los valores del filtro
                                    if($i == 0){
                                        $consultaMarcas .= $valor ;
                                        $i++;
                                    }else{
                                        $consultaMarcas .= ", $valor";
                                    }
                                }
                                $consultaMarcas .= " ) ";
                            } else { //Solo hay un filtro
                                $consultaMarcas .= "( product.brandid = $valores[0] ) ";
                            }
                            $consultaMarcas .= ' )';
                            break;
                        case 'subcategoria':
                            $valores = explode(',',$filtro[1]); //Valores a filtrar
                            $consultaSubcategorias="(";
                            if(sizeof($valores)>1) {
                                $consultaSubcategorias .= " product.subcategoryid IN( ";
                                $i=0;
                                foreach ($valores as $valor){ //Recorremos los valores del filtro
                                    if($i == 0){
                                        $consultaSubcategorias .= $valor ;
                                        $i++;
                                    }else{
                                        $consultaSubcategorias .= ", $valor";
                                    }
                                }
                                $consultaSubcategorias .= " ) ";

                            } else { //Solo hay un filtro
                                $consultaSubcategorias .= "( product.subcategoryid = $valores[0] ) ";
                            }
                            $consultaSubcategorias .= ' )';
                            break;
                    }
                }
            }else{
                $urlid= $url;
            }
            $productos = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('categoryid', '=', $urlid);
            /*Aplicacion de los filtros con o sin precios ... */
            if( $consultaPrecios != null ){
                $productos->whereRaw($consultaPrecios);
                /* Parte de los filtros con precios */
                $filtrosubcategorias = DB::table('subcategory')
                        ->select('subcategory.id',
                            'subcategory.name',
                            DB::raw("(SELECT COUNT(*) FROM product inner join price on product.id = price.id WHERE subcategoryid = subcategory.id AND photo not like 'minilogo.png' AND categoryid = $urlid AND $consultaPrecios) as total"))
                        ->where(DB::raw("(SELECT COUNT(*) FROM product inner join price on product.id = price.id WHERE subcategoryid = subcategory.id AND photo not like 'minilogo.png' AND categoryid = $urlid AND $consultaPrecios)"), '>', 0)
                        ->orderBy('name', 'asc')->get();
                foreach ($filtrosubcategorias as $item)
                    $item->id = base64_encode($item->id);
                $filtromarcas = DB::table('brand')
                    ->select('id', 'name',
                        DB::raw("(select COUNT(*) from product inner join price on product.id = price.id where brand.id = product.brandid and product.categoryid = $urlid and product.photo not like 'minilogo.png'  AND $consultaPrecios) as total"))
                    ->where(DB::raw("(select COUNT(*) from product inner join price on product.id = price.id where brand.id = product.brandid and product.photo not like 'minilogo.png' and product.categoryid = $urlid  AND $consultaPrecios)"), '>', 0)
                    ->orderBy('name', 'asc')
                    ->get();
                foreach ($filtromarcas as $item)
                    $item->id = base64_encode($item->id);
            }
            else{
                /* Parte de los filtros */
                $filtromarcas = DB::table('brand')
                    ->select('id', 'name',
                        DB::raw("(select COUNT(*) from product  where brand.id = product.brandid and product.categoryid = $urlid and product.photo not like 'minilogo.png') as total"))
                    ->where(DB::raw("(select COUNT(*) from product where brand.id = product.brandid and product.photo not like 'minilogo.png' and product.categoryid = $urlid )"), '>', 0)
                    ->orderBy('name', 'asc')
                    ->get();
                foreach ($filtromarcas as $item)
                    $item->id = base64_encode($item->id);
                //filtro de subcategorias
                $filtrosubcategorias = DB::table('subcategory')->select('id', 'name', DB::raw("(SELECT COUNT(*) FROM product WHERE subcategoryid = subcategory.id AND photo not like 'minilogo.png' AND categoryid = $urlid) as total"))
                    ->where(DB::raw("(SELECT COUNT(*) FROM product WHERE subcategoryid = subcategory.id AND photo not like 'minilogo.png' AND categoryid = $urlid)"), '>', 0)
                    ->orderBy('name', 'asc')
                    ->get();
                foreach ($filtrosubcategorias as $item)
                    $item->id = base64_encode($item->id);


            }
            /* Aplicacion Recuperación de los productos con o sin filtros*/
            if( $consultaMarcas != null ){
                $productos->whereRaw($consultaMarcas);
            }
            if( $consultaSubcategorias != null ){
                $productos->whereRaw($consultaSubcategorias);
            }
            $productos = $productos->paginate(12);
            /*----------------------  Parte del Menu --------------------------*/
            //Menu de marcas
            $marcas = DB::table('brand')->select('id', 'name')
                ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->take(40)->orderBy('name', 'asc')->get();
            //Encriptamos los id
            foreach ($marcas as $marca)
                $marca->id = base64_encode($marca->id);
            //Menu de categorias
            $categorias = DB::table('category')->select('id', 'name')->take(40)
                ->where('name', 'not like', 'Nota de credito')
                ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
                ->orderBy('name', 'asc')->get();
            //Encriptar id de categorias
            foreach ($categorias as $categoria)
                $categoria->id = base64_encode($categoria->id);
            //menu de servicios
            $servicios = DB::table('services')->select('id', 'title','shortdescription','longdescription','img')->take(10)->orderBy('title', 'asc')->get();
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            //Marca actual (Migaja)
            $actual = Categoria::find($urlid);
            return view('tienda.categorias', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroMarcas' => $filtromarcas,'filtroSubcategoria' => $filtrosubcategorias]);
        }catch(Exception $e){
            //return redirect()->route('tienda.index')->with(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
            return Response::json(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
        }
    }
    public function getAllServices(Request $request){
        if($request->cookie('cliente') != null){
            //Se tomará en cuenta si hay una session de cliente para el carrito
        }else{
            //no existe una sesion y lo manda a la tienda (se colocará una liga al panel)
            $productos = DB::table('product')
                ->select('product.code',
                    'product.name',
                    'product.stock',
                    'product.currency',
                    'product.photo',
                    'product.photo2',
                    'product.photo3',
                    'product.shortdescription',
                    'product.longdescription',
                    'product.quotation'
                //         ,'price.price1'
                )
                ->join('price','price.id', '=', 'product.priceid')
                ->where('photo', 'not like','minilogo.png')
                ->take(12)->get();
            $bMarcas = DB::table('brand')
                ->select('logo')
                ->where('logo', 'not like','minilogo.png')
                ->take(12)->get();
            //Menu de marcas
            $marcas = DB::table('brand')->select('id', 'name')
                ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'),'>',0)
                ->take(40)->orderBy('name', 'asc')->get();
            //Menu de categorias
            $categorias = DB::table('category')->select('id', 'name')->take(40)
                ->where('name', 'not like', 'Nota de credito')
                ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'),'>',0)
                ->orderBy('name', 'asc')->get();
            //menu de servicios
            $servicios = DB::table('services')->select('id','title','shortdescription','longdescription','img')->take(10)->orderBy('title','asc')->get();
            foreach ($servicios as $servicio){
                $servicio->id = base64_encode($servicio->id);}

            foreach ($categorias as $c){
                $c->id = base64_encode($c->id);}
            foreach ($marcas as $m){
                $m->id = base64_encode($m->id);}
            foreach ($productos as $p){
                $p->code = base64_encode($p->code);}
            return view('tienda.servicios',['productos'=> $productos,'bMarcas' => $bMarcas,'marcas'=>$marcas,'categorias'=>$categorias,'servicios'=>$servicios]);
        }
    }
    public function getServiceDetail(Request $request, $id){
        try {
            $id = base64_decode($id);

            if($request->cookie('cliente') != null){
                //Se tomará en cuenta si hay una session de cliente para el carrito
            }else {
                //obtenemos todos los productos de la marca
                $productos = DB::table('product')
                    ->select('product.code',
                        'product.name',
                        'product.stock',
                        'product.currency',
                        'product.photo',
                        'product.photo2',
                        'product.photo3',
                        'product.shortdescription',
                        'product.longdescription',
                        'product.quotation'
                    //         ,'price.price1'
                    )
                    ->join('price','price.id', '=', 'product.priceid')
                    ->where('photo', 'not like','minilogo.png')
                    ->where('brandid', '=', $id)
                    ->paginate(12);
                //Menu de marcas
                $marcas = DB::table('brand')->select('id', 'name')
                    ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'),'>',0)
                    ->take(40)->orderBy('name', 'asc')->get();
                //Menu de categorias
                $categorias = DB::table('category')->select('id', 'name')->take(40)
                    ->where('name', 'not like', 'Nota de credito')
                    ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'),'>',0)
                    ->orderBy('name', 'asc')->get();
                //menu de servicios
                $servicios = DB::table('services')->select('id', 'title','shortdescription','longdescription','img')->take(10)->orderBy('title', 'asc')->get();
                //Marca actual (Migaja)
                $actual =  DB::table('services')->select('id', 'title','shortdescription','longdescription','img')
                    ->take(10)
                    ->orderBy('title', 'asc')
                    ->where('id','=',$id)
                    ->get();
            }
            foreach ($servicios as $servicio){
                $servicio->id = base64_encode($servicio->id);}
            foreach ($categorias as $c){
                $c->id = base64_encode($c->id);}
            foreach ($marcas as $m){
                $m->id = base64_encode($m->id);}
            foreach ($productos as $p){
                $p->code = base64_encode($p->code);}
            return view('tienda.detalleServicio',['productos'=> $productos,'marcas'=>$marcas,'categorias'=>$categorias,'servicios'=>$servicios, 'actual'=>$actual]);
        }catch (Exception $e){


        }
    }
    public function getProfile(Request $request){
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.perfil', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
                    'ingreso' => Carbon::createFromDate($fecha[0], $fecha[1], $fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            } elseif ($cookie['rol'] == 3) {
                //vendedor solo que tenga el id 3
                return view('sale.area', ['nombre' => 'Vendedor puñetas']);
            }
        } else {
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    /*Parte de Login y logout (Sirve para ambas partes)*/
    public function doLogin(Request $request){
        try {
            $cookie = null;
            $users = Usuarios::where('email', $request->email)->firstOrFail(); //buscamos con el email si es vacio entonces mensaje de error
            if (Hash::check($request->password, $users->password)) {
                $datos = [
                    'apikey' => $users->apikey,
                    'rol' => $users->roleid,
                ];
                
                if($users->roleid == "2" || $users->roleid == "3") {
                   $cookie = Cookie::make('admin', $datos, 180);
                }else{
                    $cookie = Cookie::make('cliente', $datos, 180);
                }

                $respuesta = [
                    'code' => 200,
                    'msg' => $datos,
                    'detail' => 'OK'
                ];
            } else {
                $respuesta = [
                    'code' => 500,
                    'msg' => "Las credenciales son incorrectas",
                    'detail' => 'Error'
                ];
            }
        }catch(\Exception $exception){
            $respuesta = [
                'code' => 500,
                'msg' => $exception->getMessage(),
                'detail' => 'Error'
            ];

        }
        if ( $cookie != null )
            return Response::json($respuesta)->withCookie($cookie);
        else
            return Response::json($respuesta);
    }
    public function doLogout(Request $request){

        if ($request->cookie('admin') != null) {
            Cookie::forget('admin');
            return redirect()->route('area.index')->withCookie(Cookie::forget('admin'));
        }
    }
    public function precioUsuario ($request){
        if( $request->cookie('cliente') != null ){
            $cliente = $request->cookie('cliente');
            return 'price.price'.$cliente->userprice;
        }else{
            return '';
        }
    }
}

