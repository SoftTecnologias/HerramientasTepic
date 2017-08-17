<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Producto;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Exception;

class ShoppingCartController extends Controller
{
    //
    public function addProduct(Request $request){
        try{
            if($cookie = Cookie::get("cliente")){
                $producto = Producto::findOrFail(base64_decode($request->id));
                $item=[
                    "id" => base64_encode($producto->id),
                    "name" => $producto->name,
                    "precio" => $producto->price->scopePrice($cookie['userprice']),
                ];
                $cantidad = $request->cantidad;
                $oldCarrito = $cookie['carrito'];
                $carrito = $oldCarrito;
                $carrito->add($item, $producto->id, $cantidad);
                $cookie['carrito'] = $carrito;
                return Response::json([
                    'code' => '200',
                    'msg' => $cookie['carrito'],
                    'detail'=>'success'
                ])->withCookie('cliente',$cookie);
            }else{
                return Response::json([
                    'code' => '404',
                    'msg' => "Necesitas estar logueado para agregar al carrito",
                    'detail'=>'warning']
                );
            }
        }catch(Exception $e) {
            return Response::json([
                'code' => '500',
                'msg' => "Tuvimos un error :" . $e->getCode(),
                'detail' => 'error'
            ])->withCookie('cliente', Cookie::get("cliente"));
        }
    }
    public function removeCart(Request $request){
        if (!Session::has('carrito')){
            return Response::json([
                'code' => 404,
                'msg' => 'No tienes un carrito de compras disponible.',
                'detail' => 'Error'
            ]);
        }
        $producto = Producto::where('codigo','=',$request->input('codigo'))->first();
        $oldCart=Session::has('carrito') ? Session::get('carrito') : null;
        $cart = new Carrito($oldCart);
        $cart->remove($producto->id);
        $request->session()->put('carrito',$cart);
        return Response::json([
            'code' => 200,
            'msg' => $cart,
            'detail' => 'OK'
        ]);
    }
}
