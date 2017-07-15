<?php

namespace App\Http\Controllers;

use App\Marca;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return Datatables::of(collect(DB::select("select id, name, logo, authorized, total_sales from brand")))->make(true);
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
            $img1 = $request->file('img1');
            $imgu1  = $request->input("imgu1");

            $brandid= DB::table("brand")->insertGetId([
                "name" => $request->input("name"),
                "logo" => "minilogo.png"
            ]);

            if($imgu1==null){
                if($img1!=null){
                    $marca = Marca::findOrFail($brandid);
                    $marca->fill([
                        "logo" => "B".$brandid.".".$img1->getClientOriginalExtension()
                    ]);
                    $marca->save();
                    $nombre="/marcas/B".$brandid.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre='B'.$brandid.".".$ext[sizeof($ext)-1];
                $marca = Marca::findOrFail($brandid);
                $marca->fill([
                    "logo" => $nombre
                ]);
                $marca->save();
                Storage::disk('local')->put("/marcas/".$nombre,  fopen($imgu1,'r'));
            }

            $respuesta = ["code"=>200, "msg"=>'La marca fue creada exitosamente', 'detail' => 'success'];
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
        /*Mostrar una marca en concreto*/
        try{
            $marca = Marca::findOrFail($id);
            $respuesta=["code"=>200,"msg"=>$marca,"detail"=>"ok"];
        }catch(Exception $e){
            $respuesta=["code"=>404,"msg"=>$e->getMessage(),"detail"=>"error"];
        }
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
            $img1 = $request->file("img1");
            $imgu1  = $request->input("imgu1");
            $marca = Marca::findOrFail($id);
            $up = ([
                "name" => $request->input('name')
            ]);

            if($imgu1==null){
                if($img1!=null){
                    $up["logo"]="B".$id.".". $request->file("img1")->getClientOriginalExtension();
                    if($marca->logo != "minilogo.png") {
                        Storage::delete("/marcas/". $marca->logo);
                    }
                    $nombre="/marcas/B".$id.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre='B'.$id.".".$ext[sizeof($ext)-1];
                $up['logo'] = $nombre;
                if($marca->logo != "minilogo.png"){
                    Storage::delete("/marcas/". $marca->logo);
                }
                Storage::disk('local')->put("/marcas/".$nombre,  fopen($imgu1,'r'));
            }
            $marca->fill($up);
            $marca->save();
            $respuesta = ["code"=>200, "msg"=>"Marca actualizado","detail"=>"success"];
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
            $marca = Marca::findOrFail($id);
            if($marca->logo != "brand.png") {
                Storage::delete("/marcas/".$marca->logo);
            }
            $marca->delete();
            $respuesta = ["code"=>200, "msg"=>'La marca ha sido eliminada', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function verMiniatura(Request $request, $id){
        try {
            $id = base64_decode($id);
            $marca = Marca::findOrFail($id);
            $dat = $request->input('no');
            $up=([
                    "authorized" => $dat
                ]);
            $marca->fill($up);
            $marca->save();

           $respuesta = ["code"=>200, "msg"=>"Marca actualizado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    } 
}
