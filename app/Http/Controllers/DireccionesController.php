<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Usuarios;
use App\Direccion;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class DireccionesController extends Controller
{
    public function udateContactUser(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $user = Usuarios::findOrFail($id);
            Direccion::where('userid', $id)->update(['street' => $request->calle1,
                'street2' => $request->calle2,
                'street3' => $request->calle3,
                'state' => $request->estado,
                'zipcode' => $request->cp,
                'reference' => $request->ref,
                'country' => $request->municipio,
                'city' => $request->localidad]);
            $user->email = $request->email;
            $user->phone = $request->tel;
            $user->save();
            $respuesta = ["code" => 200, "msg" => "Usuario actualizado", "detail" => "success"];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), "detail" => "error"];
        }
        return Response::json($respuesta);
    }

    public function add(Request $request){
        try{
            $cookie = $request->cookie('cliente');
            $user = Usuarios::where('apikey',$cookie['apikey'])->firstOrFail();
            $direccion = DB::table('address')->insertGetId([
                "street"        => $request->calle1  ,
                "streetnumber"        => $request->num   ,
                "street2"   => $request->calle2   ,
                "street3"      => $request->calle3   ,
                "neigborhood"     => "",
                "zipcode"     => $request->cp,
                "city"     => $request->localidad,
                "country"     => $request->municipio,
                "state"     => $request->estado,
                "region"     => "Mexico",
                "reference"     => $request->ref,
                "userid"     => $user->id
            ]);
            $respuesta = ["code"=>200, "msg"=>'El servicio fue creado exitosamente', 'detail' => 'success'];
        }catch (Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
