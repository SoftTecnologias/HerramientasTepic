<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $baners = DB::select("select id, [titulo],[contenido],[img]
                                FROM [banner_principal] order by titulo");
        foreach ($baners as $baner){
            $baner->id = base64_encode($baner->id);
        }
        return Datatables::of(collect($baners))->make(true);
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
            $bannerid = DB::table('banner_principal')->insertGetId([
                "titulo"        => $request->input('title')   ,
                "contenido"   => $request->input('contenido')   ,
                "img"      => "banner.png"
            ]);

            if($imgu1==null){
                if($img1!=null){
                    $product = Banner::findOrFail($bannerid);
                    $product->fill([
                        "img" => "B".$bannerid.".".$img1->getClientOriginalExtension()
                    ]);
                    $product->save();
                    $nombre="/banner/"."B".$bannerid.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre="B"+$bannerid.".".$ext[sizeof($ext)-1];
                $product = Banner::findOrFail($bannerid);
                $product->fill([
                    "img" => $nombre
                ]);
                $product->save();
                Storage::disk('local')->put("/banner/".$nombre,  fopen($imgu1,'r'));
            }

            $respuesta = ["code"=>200, "msg"=>'El banner fue creado exitosamente', 'detail' => 'success'];
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
            $banner = Banner::findOrFail($id);
            $up=([
                "titulo"        => $request->input('title')   ,
                "contenido"        => $request->input('contenido')   ,
            ]);


            if($imgu1==null){
                if($img1!=null){
                    $up["img"]="S".$id.".". $request->file("img1")->getClientOriginalExtension();
                    if($banner->img != "banner.png") {
                        Storage::delete("/banner/". $banner->img);
                    }
                    $nombre="/banner/"."S".$id.".".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre="S".$id.".".$ext[sizeof($ext)-1];
                $up['img'] = $nombre;
                if($banner->img != "banner.png"){
                    Storage::delete("/banner/". $banner->img);
                }
                Storage::disk('local')->put("/banner/".$nombre,  fopen($imgu1,'r'));
            }



            $banner->fill($up);
            $banner->save();

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
            $banner = Banner::findOrFail($id);
            if($banner->img != "banner.png") {
                Storage::delete("/banner/". $banner->img);
            }
            $banner->delete();

            $respuesta = ["code"=>200, "msg"=>'El producto ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
