<?php

namespace App\Http\Controllers;

use App\Servicio;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;
class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Datatables::of(collect(DB::select("select id, [title],[shortdescription],[longdescription],[img]
                                FROM [services] order by title")))->make(true);
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
        try{


            //Insercion del producto
            $img1 = $request->file("img1");
            $imgu1  = $request->input("imgu");
            $serviceid = DB::table('services')->insertGetId([
                "title"        => $request->input('title')   ,
                "shortdescription"        => $request->input('shortdesc')   ,
                "longdescription"   => $request->input('longdesc')   ,
                "img"      => "servicios.png"
            ]);

            if($imgu1==null){
                if($img1!=null){
                    $product = Servicio::findOrFail($serviceid);
                    $product->fill([
                        "img" => "S_".$serviceid.".".$img1->getClientOriginalExtension()
                    ]);
                    $product->save();
                    $nombre="/servicios/"."S_".$serviceid.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre="S_"+$serviceid.".".$ext[sizeof($ext)-1];
                $product = Servicio::findOrFail($serviceid);
                $product->fill([
                    "img" => $nombre
                ]);
                $product->save();
                Storage::disk('local')->put("/servicios/".$nombre,  fopen($imgu1,'r'));
            }

            $respuesta = ["code"=>200, "msg"=>'El servicio fue creado exitosamente', 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
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
        try{
            $id = base64_decode($id);
            $img1 = $request->file("img1");
            $imgu1  = $request->input("imgu");
            $servicio = Servicio::findOrFail($id);
            $up=([
                "title"        => $request->input('title')   ,
                "shortdescription"        => $request->input('shortdesc')   ,
                "longdescription"   => $request->input('longdesc')   ,
            ]);


            if($imgu1==null){
                if($img1!=null){
                    $up["img"]="S_".$id.".". $request->file("img1")->getClientOriginalExtension();
                    if($servicio->img != "servicio.png") {
                        Storage::delete("/servicios/". $servicio->img);
                    }
                    $nombre="/servicios/"."S_".$id.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre="S_".$id.".".$ext[sizeof($ext)-1];
                $up['img'] = $nombre;
                if($servicio->img != "servicio.png"){
                    Storage::delete("/servicios/". $servicio->img);
                }
                Storage::disk('local')->put("/servicios/".$nombre,  fopen($imgu1,'r'));
            }



            $servicio->fill($up);
            $servicio->save();

            /*No hay manera de revisar (hasta el momento) para revisar que cambiaron todos asi que los actualizaré a ambos*/

            $respuesta = ["code"=>200, "msg"=>"Servicio Actualizado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $id = base64_decode($id);
            $servicio = Servicio::findOrFail($id);
            if($servicio->img != "minilogo.png") {
                Storage::delete("/servicios/". $servicio->img);
            }
            $servicio->delete();

            $respuesta = ["code"=>200, "msg"=>'El producto ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

}
