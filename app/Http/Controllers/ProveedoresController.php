<?php

namespace App\Http\Controllers;

use App\Municipio;
use App\Proveedor;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //*
    public function index()
    {
        $proveedores = DB::table('proveedores')->get();
        return Datatables::of(collect($proveedores)) ->make(true);
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
    //*
    public function store(Request $request)
    {
        try {
            DB::table('proveedores')->insertGetId([
                "nombre" => $request->input("nombre"),
                "calle" => $request->input("calle"),
                "colonia" => $request->input("colonia"),
                "numero_int" => $request->input("numero_int"),
                "numero_ext" => $request->input("numero_ext"),
                "cp" => $request->input("cp"),
                "estado" => $request->input("estados"),
                "municipio" => $request->input("municipio"),
                "telefono1" => $request->input("telefono1"),
                "telefono2" => $request->input("telefono2"),
                "email" => $request->input("email"),
                "contacto" => $request->input("contacto"),
            ]);
            $respuesta = ["code"=>200, "msg"=>'El proveedor fue registrado exitosamente', 'detail' => 'success'];
        }catch(\Exception $e){
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
    //*
    public function show($id)
    {
        /*Mostrar usuario en concreto*/
        try{
            $proveedorid = Proveedor::findOrFail($id);
            $respuesta=["code"=>200,"msg"=>$proveedorid,"detail"=>"ok"];
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
    //**
    public function update(Request $request, $id)
    {
        try{
            $proveedor = Proveedor::findOrFail($id);
            $up=([
                "nombre" => $request->input("nombre"),
                "calle" => $request->input("calle"),
                "colonia" => $request->input("colonia"),
                "numero_int" => $request->input("numero_int"),
                "numero_ext" => $request->input("numero_ext"),
                "cp" => $request->input("cp"),
                "estado" => $request->input("estados"),
                "municipio" => $request->input("municipio"),
                "telefono1" => $request->input("telefono1"),
                "telefono2" => $request->input("telefono2"),
                "email" => $request->input("email"),
                "contacto" => $request->input("contacto")
            ]);
            $proveedor->fill($up);
            $proveedor->save();
            $respuesta = ["code"=>200, "msg"=>"Proveedor actualizado","detail"=>"success"];
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
    //*
    public function destroy($id)
    {
        try{
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();
            $respuesta = ["code"=>200, "msg"=>'El proveedor ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    //*
    public function municipios($id){

        $mpo = DB::table('municipios')
            ->select('id_municipio','nombre')
            ->where('estado_id', '=', $id)->get();
        if(sizeof($mpo) > 0){
            $respuesta = ['code' => 200, 'msg' => $mpo, 'detail' => 'Ok'];
        }else{
            $respuesta = ['code' => 404, 'msg' => ['id'=>"",'nombre'=>"No se pudo recuperar los datos"], 'detail' => 'error'];
        }
        return Response::json($respuesta);
    }
}
