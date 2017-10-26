<?php

namespace App\Http\Controllers;

use App\canceldetail;
use App\PrecioEnvio;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;
use Yajra\Datatables\Facades\Datatables;

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pedidos = DB::select("select users.name,users.phone as phone, orders.id as orderid,userid,orders.created_at as orderdate,orders.[status] as ostatus,userA,total,subtotal,taxes,
                        (select [name] as nombre from users where userA = id) as nombre from orders
                    inner join users on users.id = userid where orders.status != 'A' order by orders.created_at,ostatus",[1]);
        foreach ($pedidos as $pedido) {
            $pedido->orderid = base64_encode($pedido->orderid);
            switch ($pedido->ostatus){
                case 'N': $pedido->ostatus = 'No Asignado';
                    break;
                case 'T': $pedido->ostatus = 'Tomado';
                    break;
                case 'D': $pedido->ostatus = 'Despachado';
                    break;
                case 'E': $pedido->ostatus = 'Enviado';
                    break;
                case 'R': $pedido->ostatus = 'Recibido';
                    break;
                case 'C': $pedido->ostatus = 'Cancelado';
                    break;
            }
        }
        return Datatables::of(collect($pedidos))->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function asignar(Request $request, $id){
        try{
            $id = base64_decode($id);
            DB::table('orders')
                ->where('id', $id)
                ->update(['userA' => base64_decode($request->trabajador),'status' => 'T']);

            $respuesta = ["code"=>200, "msg"=>"Usuario Asignado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public  function accion(Request $request,$id){
        try{
            $id = base64_decode($id);
            DB::table('orders')
                ->where('id', $id)
                ->update(['status' => $request->estado]);
            if($request->estado == 'C'){
                $canceldetail = new canceldetail();

                $canceldetail->orderid = $id;
                $canceldetail->detalle = $request->motivo;

                $canceldetail->save();
            }

            $respuesta = ["code"=>200, "msg"=>"El Pedido Cambio a Despachado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    public function pedidoDetail($id){
        $id = base64_decode($id);
        $detalle = DB::table('order_detail as od')
            ->select('product.name as producto','users.userprice as up','price.price1 as up1','price.price5 as up5',
                'price.price2 as up2','price.price3 as up3','price.price4 as up4','subtotal')
            ->join('orders','od.orderid','=','orders.id')
            ->join('product', 'product.id','=','productid')
            ->join('price','price.id','=','priceid')
            ->join('users','users.id','=','userid')
            ->where('od.orderid','=',$id)
            ->get();
        return Response::json([
            'code' => 200,
            'msg' => json_encode($detalle),
            'detail' => 'OK'
        ]);
    }
    public function guardarPrecioEnvio(Request $request){
        try{
            $precioenvio = new PrecioEnvio;
            $precioenvio->codigo_postal = $request->cp;
            $precioenvio->Nombre_Colonia = $request->col;
            $precioenvio->costo_envio = $request->precio;
            $precioenvio->save();
            $respuesta = ["code" => 200, "msg" => 'El usuario fue creado exitosamente', 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'error'];
        }
        return $respuesta;
    }
}
