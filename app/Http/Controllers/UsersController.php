<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Marca;
use App\Producto;
use App\Roles;
use App\Subcategoria;
use App\Usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;



class UsersController extends Controller
{
    /*
     * la cookies seran llamadas de distintas maneras
     *  cliente: para los usuarios con rol de cliente
     *  admin: para vendedores y administradores
     * */


    public function getLoginForm(){

    }
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
    public function getIndex(Request $request){
        if($request->cookie('cliente') != null){
            //Se tomará en cuenta si hay una session de cliente para el carrito
        }else{
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
            $marcas = DB::table('brand')->select('id','name')->where('logo', 'not like','minilogo.png')
                ->take(40)->orderBy('name','asc')->get();
            $categorias = DB::table('category')->select('id','name')->take(40)->where('name','not like', 'Nota de credito')->orderBy('name','asc')->get();
            $servicios = DB::table('services')->select('id','title')->take(10)->orderBy('title','asc')->get();
            return view('tienda.index',['banner'=>$banner,'productos'=> $productos,'bMarcas' => $bMarcas,'marcas'=>$marcas,'categorias'=>$categorias,'servicios'=>$servicios]);
        }
    }
    public function getAreaIndex(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('admin.area',['usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
                    'ingreso' =>  Carbon::createFromDate($fecha[0],$fecha[1],$fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Administrador']
                ]);
            }elseif ($cookie['rol'] == 3){
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('sale.area',['usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
                    'ingreso' =>  Carbon::createFromDate($fecha[0],$fecha[1],$fecha[2])->formatLocalized('%B %d'),
                    'photo' => $user->photo,
                    'username' => $user->username,
                    'permiso' => 'Vendedor']
                ]);
            }
        }else{
            //no existe una session de administrador y lo manda al login
            return view('login');
        }
    }
    public function getProductosForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                $categorias = Categoria::all();
                $marcas = Marca::all();
                return view('forms.productos',[
                    'marcas' => $marcas,
                    'categorias' => $categorias,
                    'usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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
        $categorias = Categoria::all();
        $marcas = Marca::all();
        return view('forms.productos',[
            'marcas' => $marcas,
            'categorias' => $categorias
        ]);
    }
    public function getMarcasForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.marcas',['usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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
    public function getCategoriasForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.categorias',['usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
                    'ingreso' => Carbon::createFromDate($fecha[0],$fecha[1],$fecha[2])->formatLocalized('%B %d'),
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
    public function getSubcategoriasform(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $categorias = Categoria::all();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.subcategorias',['categorias' => $categorias ,'usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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
    public function getUsuariosForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $roles = Roles::all();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.usuarios',['roles' => $roles ,'usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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
    public function getPedidosForm(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.pedidos',['usuario' => $user,'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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
        $subcategorias = DB::table('subcategory')->where('categoryid',$id)->get();
        $respuesta = ['code' => 200,
                      'msg' => json_encode($subcategorias),
                      'detail' => 'OK'
        ];

        return Response::json($respuesta);
    }
    public function doLogout(Request $request){
        if($request->cookie('admin') != null){
            Cookie::forget('admin');
            return redirect()->route('area.index')->withCookie(Cookie::forget('admin'));
        }
    }

    public function getProfile(Request $request){
        if($request->cookie('admin') != null){
            //Existe la cookie, solo falta averiguar que rol es
            $cookie = Cookie::get('admin');
            if($cookie['rol'] == 2) { //es un administrador
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                $fecha = explode("-",substr($user->signindate,0,10));
                return view('forms.perfil',['usuario' => $user, 'datos'=>['name'=>$user->name. ' '. $user->lastname ,
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

}
