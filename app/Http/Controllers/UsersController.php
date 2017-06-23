<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Marca;
use App\Roles;
use App\Subcategoria;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{
    //


    public function getLoginForm(){

    }

    public function doLogin(){

    }

    public function getIndex(){
        return view('area');
    }

    public function getProductosForm(){
        $categorias = Categoria::all();
        $marcas = Marca::all();
        return view('forms.productos',[
            'marcas' => $marcas,
            'categorias' => $categorias
        ]);
    }

    public function getMarcasForm(){
        return view('forms.marcas');
    }

    public function getCategoriasForm(){
        return view('forms.categorias');
    }

    public function getSubcategoriasform(){
        $categorias = Categoria::all();
        return view('forms.subcategorias',['categorias' => $categorias]);
    }

    public function getUsuariosForm(){
        $roles = Roles::all();
        return view('forms.usuarios',['roles'=>$roles]);
    }

    public function getPedidosForm(){
        return view('forms.pedidos');
    }

    public function getSubcategorias(Request $request, $id){
        $subcategorias = DB::table('subcategory')->where('categoryid',$id)->get();
        $respuesta = ['code' => 200,
                      'msg' => json_encode($subcategorias),
                      'detail' => 'OK'
        ];

        return Response::json($respuesta);
    }



}
