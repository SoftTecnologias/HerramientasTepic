<?php

namespace App\Http\Controllers;

use App\Subcategoria;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Facades\Datatables;

class SubcategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub = DB::select("select s.id, s.name, c.name as categoria, s.categoryid
from subcategory s, category c
where s.categoryid = c.id");

        foreach ($sub as $item){
            $item->id=base64_encode($item->id);
        }
        return Datatables::of(collect($sub))->make(true);
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
            $subcategoriaid= DB::table("subcategory")->insertGetId([
                "name" => $request->input("name"),
                'categoryid' => $request->input('categoryid')
            ]);
            $respuesta = ["code"=>200, "msg"=>'La Subcategoria fue creada exitosamente', 'detail' => 'success'];
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
            $cat = Subcategoria::find($id);
            $cat -> fill([
                'name'=>$request->input('name'),
                'categoryid' => $request->input('categoryid')
            ]);
            $cat -> save();
            $respuesta = ["code"=>200, "msg"=>"Subcategoria actualizada","detail"=>"success"];
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
            $cate = Subcategoria::findOrFail($id);
            $cate ->delete();
            $respuesta = ["code"=>200, "msg"=>'La subcategoria se ha eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
