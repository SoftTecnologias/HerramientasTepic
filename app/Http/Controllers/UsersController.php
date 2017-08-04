<?php

namespace App\Http\Controllers;

use App\ActivateMail;
use App\Categoria;
use App\Direccion;
use App\Localidad;
use App\Marca;
use App\Estado;
use App\Municipio;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use \Exception;
use Illuminate\Support\Facades\Storage;
use File;


class UsersController extends Controller
{
    /*
     * la cookies seran llamadas de distintas maneras
     *  cliente: para los usuarios con rol de cliente
     *  admin: para vendedores y administradores
     * */


    /*Parte de la tienda de Herramientas Tepic*/
    public function getIndex(Request $request)
    {
        try {
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
            $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            //no existe una sesion y lo manda a la tienda (se colocará una liga al panel)
            $banner = DB::table('banner_principal')->get();
            $precioUsuario = $this->precioUsuario($request);
            $select = [
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
            if ($precioUsuario != "") {
                array_push($select, $precioUsuario . " as price");
            }
            $productos = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('selected', 1)
                ->take(39)->get();

            $bMarcas = DB::table('brand')
                ->select('logo')
                ->where('logo', 'not like', 'minilogo.png')
                ->where('authorized', '=', 1)
                ->take(12)->get();
            if ($request->cookie('cliente') == null) {
                return view('tienda.index', ['banner' => $banner, 'productos' => $productos, 'bMarcas' => $bMarcas, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false]);
            } else {
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                return view('tienda.index', ['banner' => $banner, 'productos' => $productos, 'bMarcas' => $bMarcas, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user]);
            }
        } catch (Exception $e) {
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    //Vistas con filtros

    public function precioUsuario(Request $request)
    {
        try {
            if ($request->cookie('cliente') != null) {
                $cliente = $request->cookie('cliente');
                return 'price.price' . $cliente['userprice'];
            } else {
                return '';
            }
        } catch (Exception $e) {

        }
    }

    public function getMarcaSearch(Request $request, $id)
    {
        try {
            $url = base64_decode($id);
            $precioUsuario = $this->precioUsuario($request);
            $consultaPrecios = null;
            $consultaCategorias = null;
            $consultaSubcategorias = null;
            $select = [
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
            if ($precioUsuario != "") {
                array_push($select, $precioUsuario . " as price");
            }
            if (strpos($url, "/")) { //Existe al menos un parametro a parte del id
                $partes = explode("/", $url); //Separamos el id y los parametros
                $urlid = $partes[0]; //ID en cuestion
                $parametros = explode('&', $partes[1]); //Separamos los parametros con &
                foreach ($parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                    $filtro = explode('=', $parametro); //Separamos filtros[0] y valor[1]
                    switch ($filtro[0]) {
                        case 'precio': //Revisamos los valores que podria tener
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaPrecios = "(";
                            if (sizeof($valores) > 1) {
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if (strpos($valor, '-')) { //Existe un rango
                                        $precios = explode('-', $valor);
                                        if ($i == 0) {
                                            $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        } else {
                                            $consultaPrecios .= " OR (" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        }
                                    } else { //no existe el rango
                                        if ($i == 0) {
                                            $consultaPrecios .= "( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        } else {
                                            $consultaPrecios .= " OR ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        }
                                    }
                                    $i++;
                                }
                            } else { //Solo hay un filtro
                                if (strpos($valores[0], '-')) { //Existe un rango
                                    $rango = explode('-', $valores[0]);
                                    $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $rango[0] . " AND " . $rango[1] . ") ";
                                } else {
                                    $consultaPrecios .= " ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valores[0] )";
                                }
                            }
                            $consultaPrecios .= ")";
                            break;
                        case 'categoria':
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaCategorias = "(";
                            if (sizeof($valores) > 1) {

                                $consultaCategorias .= " product.categoryid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaCategorias .= $valor;
                                        $i++;
                                    } else {
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
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaSubcategorias = "(";
                            if (sizeof($valores) > 1) {
                                $consultaSubcategorias .= " product.subcategoryid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaSubcategorias .= $valor;
                                        $i++;
                                    } else {
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
            } else {
                $urlid = $url;
            }
            $productos = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('brandid', '=', $urlid);
            /*Aplicacion de los filtros con o sin precios ... */
            if ($consultaPrecios != null) {
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
            } else {
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
            if ($consultaCategorias != null) {
                $productos->whereRaw($consultaCategorias);
            }
            if ($consultaSubcategorias != null) {
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
            $servicios = DB::table('services')->select('id', 'title', 'shortdescription', 'longdescription', 'img', 'selected')->take(10)->orderBy('title', 'asc')->get();
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            //Marca actual (Migaja)
            $actual = Marca::find($urlid);
            if ($request->cookie('cliente') == null) {
                return view('tienda.marcas', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroCategorias' => $filtroCategorias, 'logueado' => false]);
            } else {
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                return view('tienda.marcas', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroCategorias' => $filtroCategorias, 'logueado' => $user]);
            }
        } catch (Exception $e) {
            //return redirect()->route('tienda.index')->with(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    public function getCategoriaSearch(Request $request, $id)
    {
        try {
            $url = base64_decode($id);
            $precioUsuario = $this->precioUsuario($request);
            $consultaPrecios = null;
            $consultaMarcas = null;
            $consultaSubcategorias = null;
            $select = [
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
            if ($precioUsuario != "") {
                array_push($select, $precioUsuario . " as price");
            }
            if (strpos($url, "/")) { //Existe al menos un parametro a parte del id
                $partes = explode("/", $url); //Separamos el id y los parametros
                $urlid = $partes[0]; //ID en cuestion
                $parametros = explode('&', $partes[1]); //Separamos los parametros con &
                foreach ($parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                    $filtro = explode('=', $parametro); //Separamos filtros[0] y valor[1]
                    switch ($filtro[0]) {
                        case 'precio': //Revisamos los valores que podria tener
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaPrecios = "(";
                            if (sizeof($valores) > 1) {
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if (strpos($valor, '-')) { //Existe un rango
                                        $precios = explode('-', $valor);
                                        if ($i == 0) {
                                            $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        } else {
                                            $consultaPrecios .= " OR (" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        }
                                    } else { //no existe el rango
                                        if ($i == 0) {
                                            $consultaPrecios .= "( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        } else {
                                            $consultaPrecios .= " OR ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        }
                                    }
                                    $i++;
                                }
                            } else { //Solo hay un filtro
                                if (strpos($valores[0], '-')) { //Existe un rango
                                    $rango = explode('-', $valores[0]);
                                    $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $rango[0] . " AND " . $rango[1] . ") ";
                                } else {
                                    $consultaPrecios .= " OR ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valores[0] )";
                                }
                            }
                            $consultaPrecios .= ")";
                            break;
                        case 'marca':
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaMarcas = "(";
                            if (sizeof($valores) > 1) {
                                $consultaMarcas .= " product.brandid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaMarcas .= $valor;
                                        $i++;
                                    } else {
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
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaSubcategorias = "(";
                            if (sizeof($valores) > 1) {
                                $consultaSubcategorias .= " product.subcategoryid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaSubcategorias .= $valor;
                                        $i++;
                                    } else {
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
            } else {
                $urlid = $url;
            }
            $productos = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('categoryid', '=', $urlid);
            /*Aplicacion de los filtros con o sin precios ... */
            if ($consultaPrecios != null) {
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
            } else {
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
            if ($consultaMarcas != null) {
                $productos->whereRaw($consultaMarcas);
            }
            if ($consultaSubcategorias != null) {
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
            $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            //Marca actual (Migaja)
            $actual = Categoria::find($urlid);
            if ($request->cookie('cliente') == null) {
                return view('tienda.categorias', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroMarcas' => $filtromarcas, 'filtroSubcategoria' => $filtrosubcategorias, 'logueado' => false]);
            } else {
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                return view('tienda.categorias', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'filtroMarcas' => $filtromarcas, 'filtroSubcategoria' => $filtrosubcategorias, 'logueado' => $user]);
            }
        } catch (Exception $e) {
            //return redirect()->route('tienda.index')->with(['code'=>500,'msg'=>$e->getMessage(),'detail'=> $e->getCode() ]);
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    public function getAllServices(Request $request)
    {
        $marcas = DB::table('brand')->select('id', 'name')
        ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
        ->take(40)->orderBy('name', 'asc')->get();
        //Menu de categorias
        $categorias = DB::table('category')->select('id', 'name')->take(40)
            ->where('name', 'not like', 'Nota de credito')
            ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->orderBy('name', 'asc')->get();
        //Menu de servicios
        $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
        //Encriptar id de servicios
        foreach ($servicios as $servicio)
            $servicio->id = base64_encode($servicio->id);
        foreach ($categorias as $c) {
            $c->id = base64_encode($c->id);
        }
        foreach ($marcas as $m) {
            $m->id = base64_encode($m->id);
        }

        try {

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
                    ->join('price', 'price.id', '=', 'product.priceid')
                    ->where('photo', 'not like', 'minilogo.png')
                    ->take(12)->get();
                $bMarcas = DB::table('brand')
                    ->select('logo')
                    ->where('logo', 'not like', 'minilogo.png')
                    ->take(12)->get();
                //Menu de marcas
                foreach ($productos as $p) {
                    $p->code = base64_encode($p->code);
                }

                if ($request->cookie('cliente') == null) {
                    return view('tienda.servicios', ['bMarcas' => $bMarcas, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios,'logueado' => false]);
                } else {
                    $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                    return view('tienda.servicios', ['bMarcas' => $bMarcas, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios,'logueado' => $user]);
                }
        } catch (Exception $e) {
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    public function getServiceDetail(Request $request, $id)
    {
        $marcas = DB::table('brand')->select('id', 'name')
            ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->take(40)->orderBy('name', 'asc')->get();
        //Menu de categorias
        $categorias = DB::table('category')->select('id', 'name')->take(40)
            ->where('name', 'not like', 'Nota de credito')
            ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->orderBy('name', 'asc')->get();
        //menu de servicios
        $servicios = DB::table('services')->select('id', 'title', 'shortdescription', 'longdescription', 'img', 'selected')->take(10)->orderBy('title', 'asc')->get();
        //Marca actual (Migaja)


        foreach ($servicios as $servicio) {
            $servicio->id = base64_encode($servicio->id);
        }
        foreach ($categorias as $c) {
            $c->id = base64_encode($c->id);
        }
        foreach ($marcas as $m) {
            $m->id = base64_encode($m->id);
        }

        try {
            $id = base64_decode($id);

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
                    ->join('price', 'price.id', '=', 'product.priceid')
                    ->where('photo', 'not like', 'minilogo.png')
                    ->where('brandid', '=', $id)
                    ->paginate(12);
            $actual = DB::table('services')->select('id', 'title', 'shortdescription', 'longdescription', 'img')
                ->take(10)
                ->orderBy('title', 'asc')
                ->where('id', '=', $id)
                ->get();
                //Menu de marcas
            foreach ($productos as $p) {
                $p->code = base64_encode($p->code);
            }
            if ($request->cookie('cliente') == null) {
                return view('tienda.detalleServicio', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'logueado' => false]);
            } else {
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                return view('tienda.detalleServicio', ['productos' => $productos, 'marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'actual' => $actual, 'logueado' => $user]);
            }

        } catch (Exception $e) {


        }
    }

    public function getRegisterForm(Request $request)
    {
        try {
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
            $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            if ($request->cookie('cliente') == null) {
                return view('tienda.registro', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios]);
            } else {
                return view('tienda.index', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios]);
            }
        } catch (Exception $e) {
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    /*Parte administratia de Herramientas Tepic */

    public function searchProductos(Request $request)
    {
        // Parte del menu
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
        $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
        //Encriptar id de servicios
        foreach ($servicios as $servicio)
            $servicio->id = base64_encode($servicio->id);
        try {
            $x = "%" . base64_decode($request->filtro) . "%";
            //parte de los filtros
            $url = base64_decode($request->filtro);
            $request->filtro = base64_decode($request->filtro);
            $precioUsuario = $this->precioUsuario($request);
            $consultaPrecios = null;
            $consultaCategorias = null;
            $consultaSubcategorias = null;
            $consultaMarcas = null;
            $select = [
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
            if ($precioUsuario != "") {
                array_push($select, $precioUsuario . " as price");
            }
            if (strpos($url, "/")) { //Existe al menos un parametro a parte del id
                $partes = explode("/", $url); //Separamos el id y los parametros
                $urlid = $partes[0]; //ID en cuestion
                $parametros = explode('&', $partes[1]); //Separamos los parametros con &
                foreach ($parametros as $parametro) { //Recorremos los parametros enviados ("precio", "categorias", "subcategorias")
                    $filtro = explode('=', $parametro); //Separamos filtros[0] y valor[1]
                    switch ($filtro[0]) {
                        case 'precio': //Revisamos los valores que podria tener
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaPrecios = "(";
                            if (sizeof($valores) > 1) {
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if (strpos($valor, '-')) { //Existe un rango
                                        $precios = explode('-', $valor);
                                        if ($i == 0) {
                                            $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        } else {
                                            $consultaPrecios .= " OR (" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $precios[0] . " AND " . $precios[1] . ") ";
                                        }
                                    } else { //no existe el rango
                                        if ($i == 0) {
                                            $consultaPrecios .= "( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        } else {
                                            $consultaPrecios .= " OR ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valor )";
                                        }
                                    }
                                    $i++;
                                }
                            } else { //Solo hay un filtro
                                if (strpos($valores[0], '-')) { //Existe un rango
                                    $rango = explode('-', $valores[0]);
                                    $consultaPrecios .= "(" . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . " between " . $rango[0] . " AND " . $rango[1] . ") ";
                                } else {
                                    $consultaPrecios .= " ( " . (($precioUsuario == "") ? "price.price1" : $precioUsuario) . "> $valores[0] )";
                                }
                            }
                            $consultaPrecios .= ")";
                            break;
                        case 'categoria':
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaCategorias = "(";
                            if (sizeof($valores) > 1) {
                                $consultaCategorias .= " product.categoryid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaCategorias .= $valor;
                                        $i++;
                                    } else {
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
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaSubcategorias = "(";
                            if (sizeof($valores) > 1) {
                                $consultaSubcategorias .= " product.subcategoryid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaSubcategorias .= $valor;
                                        $i++;
                                    } else {
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
                            $valores = explode(',', $filtro[1]); //Valores a filtrar
                            $consultaMarcas = "(";
                            if (sizeof($valores) > 1) {
                                $consultaMarcas .= " product.brandid IN( ";
                                $i = 0;
                                foreach ($valores as $valor) { //Recorremos los valores del filtro
                                    if ($i == 0) {
                                        $consultaMarcas .= $valor;
                                        $i++;
                                    } else {
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
            } else {
                $urlid = $url;
            }
            $filtromarcas = [];
            $filtrobusqueda = DB::table('product')
                ->select($select)
                ->join('price', 'price.id', '=', 'product.priceid')
                ->where('photo', 'not like', 'minilogo.png')
                ->where('name', 'like', '%' . $urlid . '%');
            /*Aplicacion de los filtros con o sin precios ... */
            if ($consultaPrecios != null) {
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
            } else {
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
            if ($consultaCategorias != null) {
                $filtrobusqueda->whereRaw($consultaCategorias);
            }
            if ($consultaSubcategorias != null) {
                $filtrobusqueda->whereRaw($consultaSubcategorias);
            }
            if ($consultaMarcas != null) {
                $filtrobusqueda->whereRaw($consultaMarcas);
            }
            $filtrobusqueda = $filtrobusqueda->paginate();
            // Parte del menu


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
            $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
            //Encriptar id de servicios
            foreach ($servicios as $servicio)
                $servicio->id = base64_encode($servicio->id);
            if ($request->cookie('cliente') == null) {
                return view('tienda.buscaProductos', ['marcas' => $marcas,
                    'categorias' => $categorias,
                    'servicios' => $servicios,
                    'filtro' => $filtrobusqueda,
                    'f' => $urlid,
                    'filtroCategorias' => $filtroCategorias,
                    'filtroMarcas' => $filtromarcas,
                    'logueado' => false]);
            } else {
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                return view('tienda.buscaProductos', ['marcas' => $marcas,
                    'categorias' => $categorias,
                    'servicios' => $servicios,
                    'filtro' => $filtrobusqueda,
                    'f' => $urlid,
                    'filtroCategorias' => $filtroCategorias,
                    'filtroMarcas' => $filtromarcas,
                    'logueado' => $user]);
            }
        } catch (Exception $e) {
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $e]);
        }
    }

    public function getAreaIndex(Request $request)
    {
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

    public function getProductosForm(Request $request)
    {
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

    public function getMarcasForm(Request $request)
    {
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

    public function getCategoriasForm(Request $request)
    {
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

    public function getSubcategoriasform(Request $request)
    {
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

    public function getUsuariosForm(Request $request)
    {
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

    public function getPedidosForm(Request $request)
    {
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
        }
    }

    public function getServiciosForm(Request $request)
    {
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.servicios', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
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

    public function getBannerForm(Request $request)
    {
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                return view('forms.banner', ['usuario' => $user, 'datos' => ['name' => $user->name . ' ' . $user->lastname,
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

    public function getSubcategorias(Request $request, $id)
    {
        $subcategorias = DB::table('subcategory')->where('categoryid', $id)->get();
        $respuesta = ['code' => 200,
            'msg' => json_encode($subcategorias),
            'detail' => 'OK'
        ];

        return Response::json($respuesta);
    }

    public function getProfile(Request $request)
    {
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

    public function showMovementForm(Request $request)
    {
        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                $productos = DB::table('product')->select('id', 'name')->get();
                $proveedores = DB::table('proveedores')->select('id', 'nombre')->get();
                return view('forms.movement', [
                    'proveedores' => $proveedores,
                    'productos' => $productos,
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

    }

    /*Parte de Login y logout (Sirve para ambas partes)*/

    public function showProviderForm(Request $request)
    {

        if ($request->cookie('admin') != null) {
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if ($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey', $cookie['apikey'])->first();
                $fecha = explode("-", substr($user->signindate, 0, 10));
                $estados = Estado::all();
                return view('forms.provider', [
                    'estados' => $estados,
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

    }

    public function doLogin(Request $request)
    {
        try {
            $cookie = null;
            $users = Usuarios::where('email', $request->email)
                ->orWhere('username',$request->email)
                ->firstOrFail(); //buscamos con el email si es vacio entonces mensaje de error
            if (Hash::check($request->password, $users->password) && $users->status == "A") {
                $datos = [
                    'apikey' => $users->apikey,
                    'rol' => $users->roleid,
                ];

                if ($users->roleid == "2" || $users->roleid == "3") {
                    $cookie = Cookie::make('admin', $datos, 180);
                } else {
                    $datos['userprice'] = $users->userprice;
                    $datos['carrito'] = null;//En caso de no existir el carrito lo cual se agregará mas adelante
                    //revisaremos que exista el carrito y lo agregamos a la cookie
                    $cookie = Cookie::make('cliente', $datos, 360);
                }

                $respuesta = [
                    'code' => 200,
                    'msg' => $datos,
                    'detail' => 'OK'
                ];
            } else {
                $respuesta = [
                    'code' => 500,
                    'msg' => "Las credenciales son incorrectas o el Usuario no está Activo",
                    'detail' => 'error'
                ];
            }
        } catch (\Exception $exception) {
            $respuesta = [
                'code' => 500,
                'msg' => $exception->getMessage(),
                'detail' => 'Error'
            ];

        }
        if ($cookie != null)
            return Response::json($respuesta)->withCookie($cookie);
        else
            return Response::json($respuesta);
    }

    public function doCLogin(Request $request)
    {
        try {
            $cookie = null;
            $users = Usuarios::where('email', $request->email)
                     ->orWhere('username',$request->email)
                     ->firstOrFail(); //buscamos con el email si es vacio entonces mensaje de error
            if (Hash::check($request->password, $users->password) && $users->status == "A") {
                $datos = [
                    'apikey' => $users->apikey,
                    'rol' => $users->roleid,
                ];

                if ($users->roleid == "2" || $users->roleid == "3") {
                    $cookie = Cookie::make('admin', $datos, 180);
                    return Response::json([
                        'code' => 403,
                        'msg' => 'Para usar este usuario valla a la parte administrativa',
                        'detail' => 'warning'
                    ])->withCookie($cookie);
                } else {
                    $datos['userprice'] = $users->userprice;
                    $datos['carrito'] = null; //En caso de no existir el carrito lo cual se agregará mas adelante
                    //revisaremos que exista el carrito y lo agregamos a la cookie
                    $cookie = Cookie::make('cliente', $datos, 360);
                }

                $respuesta = [
                    'code' => 200,
                    'msg' => $datos,
                    'detail' => 'OK'
                ];
            } else {
                $respuesta = [
                    'code' => 500,
                    'msg' => "Las credenciales son incorrectas o el usuario no está activo",
                    'detail' => 'Error'
                ];
            }
        } catch (\Exception $exception) {
            $respuesta = [
                'code' => 500,
                'msg' => $exception->getMessage(),
                'detail' => 'error'
            ];

        }
        if ($cookie != null)
            return Response::json($respuesta)->withCookie($cookie);
        else
            return Response::json($respuesta);
    }

    public function doLogout(Request $request)
    {
        if ($request->query('id') != null) {
            if ($request->cookie('cliente') != null) {
                Cookie::forget('cliente');
                return redirect()->route('tienda.index')->withCookie(Cookie::forget('cliente'));
            }
        } else {
            if ($request->cookie('admin') != null) {
                Cookie::forget('admin');
                return redirect()->route('area.index')->withCookie(Cookie::forget('admin'));
            }
        }
    }

    //Seguirle

    public function confirmEmail(Request $request, $id)
    {
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
        $servicios = DB::table('services')->select('id', 'title', 'img', 'shortdescription', 'longdescription', 'selected')->take(10)->orderBy('title', 'asc')->get();
        //Encriptar id de servicios
        foreach ($servicios as $servicio)
            $servicio->id = base64_encode($servicio->id);
        try {
            //Aqui eliminamos el registro por el id de ambos
            $params = base64_decode($id);
            $solicitud = ActivateMail::findOrFail($params);
            //Eliminamos la solicitud y se activa por trigger o manualmente!!
            $solicitud->delete();
            //regresamos la vista de confirmación por eso es GET
            return view('tienda.confirmation', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios]);
        } catch (Exception $exception) {
            //Hubo un error y se manda la pantalla de alerta
            return view('tienda.problema', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'exception' => $exception]);
        }
        //Menu de marcas


    }

    //Error de Mail
    public function errorMail(Request $request, $id)
    {
        //Se envia un correo electronico con el id del usuario para su subida manual
        try {
            $id = base64_decode($id);
            $user = Usuarios::findOrFail($id);
            Mail::send('emails.problema', ['user' => $user], function ($msg) {
                $msg->subject('Confirmación de cuenta');
                $msg->to('soporte.herramientas.tepic@gmail.com');
            });
            return redirect()->route('tienda.index');
        } catch (Exception $e) {
            return view("Hubo un problema contactenos en soporte.herramientas.tepic@gmail.com");
        }
    }

    public function getUserProfile(Request $request)
    {
        try {
            if ($request->cookie('cliente') != null) {
                $cookie = Cookie::get('cliente');
                $users = Usuarios::where('apikey',$cookie[apikey])->firstOrFail();
                $user = DB::table('users')
                    ->select('users.name as uname',
                        'users.id',
                        'users.lastname as lastname',
                        'users.photo as photo',
                        'users.signindate',
                        'users.username as user',
                        'estados.nombre as esnombre',
                        'address.city',
                        'address.state',
                        'address.country',
                        'address.street',
                        'address.street2',
                        'address.street3',
                        'address.zipcode',
                        'address.reference',
                        'address.streetnumber',
                        'users.phone',
                        'users.email',
                        'municipios.nombre as muname',
                        'localidades.nombre as loname')
                    ->join('address', 'address.userid', '=', 'users.id')
                    ->join('estados', 'estados.id_estado', '=', 'address.state')
                    ->join('municipios', 'id_municipio', '=', 'address.country')
                    ->join('localidades', 'id_localidad', '=', 'address.city')
                    ->where('apikey', $cookie['apikey'])->first();


                $estados = Estado::all();
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
                $servicios = DB::table('services')->select('id', 'title', 'shortdescription', 'longdescription', 'img', 'selected')->take(10)->orderBy('title', 'asc')->get();
                foreach ($servicios as $servicio)
                    $servicio->id = base64_encode($servicio->id);
                //Marca actual (Migaja
                if ($user == '') {
                    return view('tienda.direccion', ['servicios' => $servicios,
                        'marcas' => $marcas, 'categorias' => $categorias, 'estados' => $estados,'logueado' => $users]);
                }
                $municipios = DB::table('municipios')->select('*')
                    ->where('estado_id', '=', $user->state)->get();
                $localidades = DB::table('localidades')->select('nombre', 'municipio_id', 'id_localidad')
                    ->where('municipio_id', '=', $user->country)->get();
                $user->id = base64_encode($user->id);
                return view('tienda.profile', ['user' => $user, 'servicios' => $servicios,
                    'marcas' => $marcas, 'categorias' => $categorias, 'estados' => $estados,
                    'localidades' => $localidades, 'municipios' => $municipios,'logueado' => $user]);
            } else {
                return redirect()->route('tienda.index');
            }


        } catch (Exception $e) {
            return redirect()->route('tienda.problema',['error'=>$e]);
        }
    }

    //obtencion de datos
    public function getMunicipios(Request $request, $estado)
    {
        $municipios = DB::table('municipios')->select('*')
            ->where('estado_id', '=', $estado)->get();
        return Response::json([
            'code' => 200,
            'msg' => json_encode($municipios),
            'detail' => 'OK'
        ]);
    }

    public function getLocalidades(Request $request, $municipio)
    {
        $localidades = DB::table('localidades')->select('*')
            ->where('municipio_id', '=', $municipio)->get();
        return Response::json([
            'code' => 200,
            'msg' => json_encode($localidades),
            'detail' => 'OK'
        ]);
    }
    //Actualizacion de datos personales del cliente
}


