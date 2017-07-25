<?php

namespace App\Http\Controllers;

use App\Detalle_movimiento;
use App\Movimiento;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;


class MovimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movimientos = DB::table('movimientos')
            ->select(
                'movimientos.id',
                'proveedores.nombre',
                'movimientos.folio_factura',
                'movimientos.fecha',
                'movimientos.observaciones',
                'movimientos.total',
                'movimientos.status',
                'movimientos.tipo'
            )
            ->join('proveedores', 'movimientos.proveedor_id', '=', 'proveedores.id')
            ->get();
        return Datatables::of(collect($movimientos)) ->make(true);
    }

    public function detailIndex($id){
        try {
            $detalles = DB::table('detalle_movimientos')
                ->select('detalle_movimientos.fecha',
                    'product.name',
                    'cantidad',
                    'importe',
                    'detalle_movimientos.total',
                    'movimientos.status',
                    'detalle_movimientos.id'
                )
                ->join('movimientos','detalle_movimientos.movimiento_id','=','movimientos.id')
                ->join('product','detalle_movimientos.producto_id','=','product.id')
                ->where('movimiento_id', '=', $id)->get();
            if(sizeof($detalles)>0)
                $respuesta = ['code' => 200, 'msg'=> $detalles,'detail' => 'success'];
            else
                $respuesta = ['code' => 404, 'msg'=> "No hay detalles de este movimiento",'detail' => 'success'];
        }catch(\Exception $e){
            $respuesta = ['code' => 500, 'msg'=> $e->getMessage(),'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            //aqui creamos los movimientos
            DB::table('movimientos')->insert([
                'proveedor_id' => $request->input('proveedor_id'),
                'observaciones' => $request->input('observaciones'),
                'tipo' => $request->input('tipo'),
                'folio_factura' => $request->input('factura')
            ]);
            $respuesta = ['code' => 200, 'msg'=>'El movimiento se ha registrado exitosamente', 'detail'=>'success'];
        }catch (\Exception $e){
            $respuesta = ['code' => 200, 'msg'=>$e->getMessage(), 'detail'=>'warning'];
        }

        return Response::json($respuesta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        //Aqui crearemos los detalles de los movimientos

        try{
            //Revisaremos que no esté ya en los detalles
            $registros= DB::table('detalle_movimientos')
                ->select(DB::raw('count(*) as registro'))
                ->where([
                    ['movimiento_id','=',$id],
                    ['producto_id','=',$request->input('producto_id')]
                ])->get();
            if($registros[0]->registro == 0){
                DB::table('detalle_movimientos')->insert([
                    'movimiento_id' => $id,
                    'producto_id' =>$request->input('producto_id'),
                    'importe' => $request->input('precio'),
                    'cantidad' => $request->input('cantidad'),
                    'total' => $request->input('precio') * $request->input('cantidad')
                ]);
                $respuesta = ['code' => 200, 'msg'=>'El detalle se ha agregado', 'detail'=>'success'];
            }else{
                $respuesta = ['code' => 500, 'msg'=>'Este producto ya está dentro de los detalles', 'detail'=>'warning'];
            }
        }catch (\Exception $e){
            $respuesta = ['code' => 500, 'msg'=>$e->getMessage(), 'detail'=>'warning'];
        }
        //Regresar los datos de la insercion como el id y eso
        return Response::json($respuesta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //mostramos un detalle
        return $id;
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
        //no se usará
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //eliminamos el movimiento
        try {
            $movimiento = Movimiento::findOrFail($id);
            $movimiento ->update(['status' => 0]);
            $respuesta = ["code"=>200, "msg"=>'El movimiento se ha cancelado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function removeDetail($id){
        //eliminamos el movimiento
        try {
            $detalle = Detalle_movimiento::findOrFail($id);
            $detalle ->delete();
            $respuesta = ["code"=>200, "msg"=>'El detalle ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
