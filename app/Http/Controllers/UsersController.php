<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Marca;
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
        return view('forms.usuarios');
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

    public function getProductos(Request $request){
        $productos = DB::table('product')
            ->select('product.productid',
                'product.code',
                'product.name',
                'product.shortdescription',
                'product.longdescription',
                'product.brandid',
                'brand.name as marca',
                'product.categoryid',
                'category.name as categoria',
                'product.subcategoryid',
                'subcategory.name as subcategoria',
                'product.stock',
                'product.currency',
                'product.reorderpoint',
                'product.photo',
                'product.photo2',
                'product.photo3',
                'price.price1',
                'price.price2',
                'price.price3',
                'price.price4',
                'price.price5'
            )
            ->join('brand',"product.brandid",'=','brand.id')
            ->join('category',"product.categoryid",'=','category.id')
            ->join('subcategory',"subcategory.subcategoryid",'=','category.categoryid')
            ->join('price',"price.price_id",'=','product.priceid')
            ->get();
        return Response::json($productos);
    }




}
