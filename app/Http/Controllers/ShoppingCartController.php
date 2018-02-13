<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\OrderDetail;
use App\Producto;
use App\Usuarios;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Exception;

class ShoppingCartController extends Controller
{
    //
    public function addProduct(Request $request)
    {
        try {
            if ($cookie = Cookie::get("cliente")) {
                $producto = Producto::findOrFail(base64_decode($request->id));
                $item = [
                    "id" => base64_encode($producto->id),
                    "name" => $producto->name,
                    "code" => $producto->code,
                    "precio" => $producto->price->scopePrice($cookie['userprice']),
                    "photo" => $producto->photo,
                    "currency" => $producto->currency
                ];
                $cantidad = $request->cantidad;
                $oldCarrito = $cookie['carrito'];
                $carrito = $oldCarrito;
                $carrito->add($item, $producto->id, $cantidad);
                $cookie['carrito'] = $carrito;
                return Response::json([
                    'code' => '200',
                    'msg' => $cookie['carrito'],
                    'detail' => 'success'
                ])->withCookie('cliente', $cookie);
            } else {
                return Response::json([
                        'code' => '404',
                        'msg' => "Necesitas estar logueado para agregar al carrito",
                        'detail' => 'warning']
                );
            }
        } catch (Exception $e) {
            return Response::json([
                'code' => '500',
                'msg' => "Tuvimos un error :" . $e->getCode(),
                'detail' => 'error'
            ])->withCookie('cliente', Cookie::get("cliente"));
        }
    }

    public function removeCart(Request $request)
    {
        try {
            if ($cookie = Cookie::get("cliente")) {
                $producto = Producto::findOrFail(base64_decode($request->id));
                $item = [
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
                    'detail' => 'success'
                ])->withCookie('cliente', $cookie);
            } else {
                return Response::json([
                        'code' => '404',
                        'msg' => "Necesitas estar logueado para agregar al carrito",
                        'detail' => 'warning']
                );
            }
        } catch (Exception $e) {
            return Response::json([
                'code' => '500',
                'msg' => "Tuvimos un error :" . $e->getCode(),
                'detail' => 'error'
            ])->withCookie('cliente', Cookie::get("cliente"));
        }
    }

    public function removePartial(Request $request)
    {
        try {
            if ($cookie = Cookie::get("cliente")) {
                $cantidad = $request->cantidad;
                $oldCarrito = $cookie['carrito'];
                $carrito = $oldCarrito;
                $carrito->removePartial(base64_decode($request->id), $cantidad);
                $cookie['carrito'] = $carrito;
                return Response::json([
                    'code' => '200',
                    'msg' => $cookie['carrito'],
                    'detail' => 'success'
                ])->withCookie('cliente', $cookie);
            } else {
                return Response::json([
                        'code' => '404',
                        'msg' => "Necesitas estar logueado para agregar al carrito",
                        'detail' => 'warning']
                );
            }
        } catch (Exception $e) {
            return Response::json([
                'code' => '500',
                'msg' => "Tuvimos un error :" . $e->getCode(),
                'detail' => 'error'
            ])->withCookie('cliente', Cookie::get("cliente"));
        }
    }

    public function updateCart(Request $request)
    {
        try {
            if ($cookie = Cookie::get("cliente")) {
                $datos = json_decode($request->productos, true);
                foreach ($datos as $dato) {
                    $producto = Producto::findOrFail(base64_decode($dato['id']));
                    $item = [
                        "id" => base64_encode($producto->id),
                        "name" => $producto->name,
                        "code" => $producto->code,
                        "precio" => $producto->price->scopePrice($cookie['userprice']),
                        "photo" => $producto->photo,
                        "currency" => $producto->currency
                    ];
                    $cantidad = $dato['cantidad'];
                    $oldCarrito = $cookie['carrito'];
                    $carrito = $oldCarrito;
                    $carrito->update($item, $producto->id, $cantidad);
                    $cookie['carrito'] = $carrito;
                }
                return Response::json([
                    'code' => '200',
                    'msg' => $cookie['carrito'],
                    'detail' => 'success'
                ])->withCookie('cliente', $cookie);
            } else {
                return Response::json([
                        'code' => '404',
                        'msg' => "Necesitas estar logueado para agregar al carrito",
                        'detail' => 'warning']
                );
            }
        } catch (Exception $e) {
            return Response::json([
                'code' => '500',
                'msg' => "Tuvimos un error :" . $e->getCode(),
                'detail' => 'error'
            ])->withCookie('cliente', Cookie::get("cliente"));
        }
    }

    public function makeCheckout(Request $request){ //Crear la orden
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
        /* ------------------------------------------------------------------------------------- */
        $checkpoint="Checkpoint 0";
        try {
            if (Cookie::get('cliente') == null) {
                return view('errors.403', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false]);
            } else {
                $cookie = Cookie::get("cliente");
                $user = Usuarios::where('apikey', $cookie['apikey'])->firstOrFail();
                $checkpoint="Checkpoint 1";
                $order = new Order;
                $order->status = "A"; //creada
                $order->subtotal = $cookie['carrito']->total;
                $order->delivery_cost = -1;
                $order->delivery_type = 0;
                $order->userid = $user->id;
                $order->step = 1;
                $order->finished = 0;
                $order->taxes = -1;
                $order->total = $cookie['carrito']->total;
                $order->save();
                $checkpoint="Checkpoint 2";
                $cookie['orderid'] = $order->id;
                $cookie['actual']=1;
                $cookie['anterior']=0;
                /*Agregamos los detalles */
                foreach ($cookie['carrito']->productos as $item) {
                    $checkpoint="Checkpoint 3";
                    $orderDetail = new OrderDetail;
                    $orderDetail->price = $item['total'];
                    $orderDetail->productid = base64_decode($item['item']['id']);
                    $orderDetail->orderid = $order->id;
                    $orderDetail->qty = $item['cantidad'];
                    $orderDetail->save();
                }
                $checkpoint="Checkpoint 4";
                /*Eliminamos del carrito los ya ingresados */
                $cookie['carrito']= new Cart();
                return redirect()->route('carrito.delivery')->withCookie('cliente', $cookie);

            }
        } catch (Exception $e) {
            dd($checkpoint);
            abort(500);
        }
    }

    public function setDeliveryType(Request $request){
        //Menu de marcas
        $marcas = DB::table('brand')->select('id', 'name')
            ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->take(40)->orderBy('name', 'asc')->get();
         $bMarcas = DB::table('brand')
                ->select('logo')
                ->where('logo', 'not like', 'minilogo.png')
                ->where('authorized', '=', 1)
                ->take(12)->get();
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

            if ($request->cookie('cliente') == null) {
                return view('errors.403', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false,"bMarcas"=>$bMarcas]);
            } else {
                $cookie = Cookie::get("cliente");
                $order = Order::find($cookie['orderid']);
                $order->delivery_type = $request->delivery;
                if ($request->delivery > 2) { //envio
                    $order->step = 2;
                } else { //recoger
                    $order->step = 3;
                }
                $order->save();
                if ($order->step > 2) {
                    #dd($cookie);
                    $cookie['anterior'] = $cookie['actual'];
                    #dd($cookie);
                    $cookie['actual'] = 3;
                    return redirect()->route('carrito.summary')->withCookie('cliente', $cookie);
                } else {
                    $cookie['anterior'] = $cookie['actual'];
                    $cookie['actual'] = 2;
                    return redirect()->route('carrito.addresses')->withCookie('cliente', $cookie);
                }
            }
        } catch (Exception $e) {
            dd($e);
            abort(500);
        }
    }

    public function setAddress(Request $request){
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
            if ($request->cookie('cliente') == null) {
                return view('errors.403', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false]);
            } else {
                $cookie = Cookie::get("cliente");
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                if ($cookie['actual'] == 2) {
                    #dd($cookie);
                    $cookie['anterior']=$cookie['actual'];
                    $cookie['actual']= 3;
                    #return view('tienda.resumen', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user,'details'=>$orderdetails]);
                    return redirect()->route('carrito.summary')->withCookie('cliente',$cookie);
                } else {
                    return view('errors.403', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user]);
                }
            }
        } catch (Exception $e) {
            dd($e);
            abort(500);
        }
    }

    public function finishOrder(Request $request){
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
            if ($request->cookie('cliente') == null) {
                return view('errors.403', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false]);
            } else {
                $cookie = Cookie::get("cliente");
                $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                $orden = Order::find($cookie['orderid']);
                $orden->status = 'N';
                $orden->finished=1;
                $orden->save();
                #$summary= $cookie['orderid'];
                #return view('tienda.finish', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user,'summary'=>$summary]);
                $cookie['actual']=0;
                $cookie['anterior']=0;
                #$cookie['orderid']=0;
                return redirect()->route('carrito.finish.Order')->withCookie('cliente', $cookie);
            }
        } catch (Exception $e) {
            dd($e);
            abort(500);
        }
    }

    public function  backStep(Request $request){

    }

    public function destroyOrder(Request $request)
    {
        try {
            if (Cookie::get('cliente') == null) {
                return redirect()->route("tienda.index");
            } else {
                $cookie = Cookie::get("cliente");
                $order = Order::findOrFail($cookie['orderid']);
                $detalles = DB::table('order_detail')
                            ->where('orderid',$cookie['orderid'])
                            ->get();
                foreach($detalles as $detalle){
                    $producto = Producto::findOrFail($detalle->productid);
                    $item = [
                        "id" => base64_encode($producto->id),
                        "name" => $producto->name,
                        "code" => $producto->code,
                        "precio" => $producto->price->scopePrice($cookie['userprice']),
                        "photo" => $producto->photo,
                        "currency" => $producto->currency
                    ];
                    $cantidad = $detalle->qty;
                    $oldCarrito = $cookie['carrito'];
                    $carrito = $oldCarrito;
                    $carrito->add($item, $producto->id, $cantidad);
                    $cookie['carrito'] = $carrito;
                }

                DB::table('order_detail')->where('orderid',$cookie['orderid'])->delete();
                $order->delete();
                $cookie['actual'] = 0;
                $cookie['orderid'] = 0;
                return redirect()->route('carrito.getCheckout')->withCookie('cliente', $cookie);
            }
        } catch (Exception $e) {
            abort(500);
        }
    }

}
