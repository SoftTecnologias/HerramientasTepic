<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = DB::select("select u.id, u.photo, u.name, u.lastname, u.email, u.phone, r.name as rol,u.status,u.roleid,u.username
        from users u 
        inner join  roles r on r.id = u.roleid");

        foreach ($usuarios as $usuario){
            $usuario->id=base64_encode($usuario->id);
        }
        return Datatables::of(collect($usuarios))->make(true);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Nota actualizar las excepciones*/
        try {
            /*Creamos aun nuevo usuario aqui insertamos y luego obtenemos el id*/
            $user = new Usuarios;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->roleid = $request->roleid;
            if ($request->roleid == 1)
                $user->userprice = 1;
            $user->status = $request->status;
            $user->username = $request->username;
            $user->photo = 'user.png';
            $user->save();
            /*Creamos el apikey*/
            $user->apikey = bcrypt($user->id);
            /*Guardamos el registro, de aquí renombramos la imagen*/
            if ($request->file('photo') != null) {
                $user->photo = "U" . $user->id . '.' . $request->file('photo')->getClientOriginalExtension();
                $nombre = "/usuarios/U" . $user->id . "." . $request->file("photo")->getClientOriginalExtension();
                Storage::disk('local')->put($nombre, File::get($request->file("photo")));
            }
            $user->save();
            $respuesta = ["code" => 200, "msg" => 'El usuario fue creado exitosamente', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'success'];
        }

        return Response::json($respuesta);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $id = base64_decode($id);
            $user = Usuarios::findOrFail($id);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->roleid = $request->roleid;
            if ($request->roleid == 1)
                $user->userprice = 1;
            $user->status = $request->status;
            $user->username = $request->username;
            /*Revisaremos si hay una contraseña*/
            if ($request->input("password") != null) {
                /*Hay una contraseña por tanto se puede actualizar*/
                if (Hash::check($request->input("password"), $user->password)) {
                    $user->password = bcrypt($request->input("npassword"));
                } else {
                    throw  new Exception("La contraseña ingresada no coincide");
                }
            }
            /*Revisamos si hay una imagen cargada*/

            if ($request->file("photo") != null) {
                $user->photo = 'U'.$user->id . "." . $request->file("photo")->getClientOriginalExtension();
                if ($user->photo != "user.png") {
                    Storage::delete("/usuarios/" . $user->photo);
                }
                $file = $request->file("photo");
                $nombre = "/usuarios/U" . $id . "." . $file->getClientOriginalExtension();
                Storage::disk('local')->put($nombre, File::get($file));
            }
            $user->save();
            $respuesta = ["code" => 200, "msg" => "Usuario actualizado", "detail" => "success"];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), "detail" => "error"];
        }
        return Response::json($respuesta);
    }

    public function areaProfileEdit(Request $request){
        try{
            if($request->cookie('admin') != null){
                $cookie = Cookie::get('admin');
                $user = Usuarios::where('apikey',$cookie['apikey'])->first();
                //recuperamos informacion del usuario
                //$user = Usuarios::findOrFail($id);
                $user -> phone = $request->tel;
                $user -> email = $request->mail;
                //revisamos si hay contraseña
                if($request -> input("conactual") != null){
                    //si se encontro contraseña se puede actualizar
                    if(Hash::check($request->input("conactual"),$user -> password)) {
                        $user -> password = bcrypt($request -> input("nuevacon"));
                    }else{
                        throw  new Exception("La contraseña no Coincide");
                    }
                }

                /*Revisamos si hay imagen cargada*/
                if($request -> file("photo") != null){
                    $user -> photo = 'U'.$user->id . "." . $request->file("photo")->getClientOriginalExtension();
                    if($user->photo != "user.png"){
                        Storage::delete("/usuarios/" . $user->photo);
                    }
                    $file = $request->file("photo");
                    $nombre= "/usuarios/U" . $user->id . "." . $file -> getClientOriginalExtension();
                    Storage::disk('local')->put($nombre, File::get($file));
                }
                $user->save();
                $respuesta = ["code" => 200, "msg" => "Usuario actualizado", "detail" => "success"];

            }

        }catch (Exception $e){
            $respuesta = ["code" => 500, "msg" => $e -> getMessage(), "detail" => "error"];
        }
        return Response::json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = base64_decode($id);
            $user = Usuarios::findOrFail($id);
            if ($user->photo != "user.png") {
                Storage::delete("/usuarios/" . $user->photo);
            }
            $user->delete();
            $respuesta = ["code" => 200, "msg" => 'El usuario ha sido eliminado', 'detail' => 'success'];
        } catch (Exception $e) {
            $respuesta = ["code" => 500, "msg" => $e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }
}
