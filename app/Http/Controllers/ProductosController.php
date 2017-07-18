<?php

namespace App\Http\Controllers;

use App\Precio;
use App\Producto;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;


class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = DB::select("select p.[id], [code], p.[name], [stock], [currency], p.[brandid] ,b.[name] as marca,
                                [photo], [photo2], [photo3], p.[subcategoryid], s.name as subcategoria, p.[categoryid],
                                c.name as categoria, [priceid], [shortdescription], [longdescription], [reorderpoint],
                                pr.price1, pr.price2, pr.price3, pr.price4, pr.price5, p.quotation,p.show
                                FROM [product] p , [price] pr, [category] c ,
                                     [subcategory] s,[brand] b 
                                WHERE p.brandid = b.id AND p.categoryid = c.id AND p.subcategoryid = s.id AND p.priceid = pr.id
                                order by name");
            foreach ($products as $p){
                $p->id = base64_encode($p->id);
            }
        return Datatables::of(collect($products)) ->make(true);
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

            //Insercion del precion del producto
            $precioid= DB::table('price')->insertGetId([
                "price1"       => $request->input('price1')   ,
                "price2"       => $request->input('price2')   ,
                "price3"       => $request->input('price3')   ,
                "price4"       => $request->input('price4')   ,
                "price5"       => $request->input('price5')
            ]);
            //Insercion del producto
            $img1 = $request->file("img1");
            $img2 = $request->file("img2");
            $img3 = $request->file("img3");
            $imgu1  = $request->input("imgu1");
            $imgu2  = $request->input("imgu2");
            $imgu3  = $request->input("imgu3");
            $productid = DB::table('product')->insertGetId([
                "categoryid"        => $request->input('categoryid')   ,
                "code"        => $request->input('code')   ,
                "currency"   => $request->input('currency')   ,
                "longdescription"      => $request->input('longdescription')   ,
                "brandid"  => $request->input('brandid')   ,
                "name"  => $request->input('name')   ,
                "photo"          => "minilogo.png"   ,
                "photo2"          => "minilogo.png"   ,
                "photo3"          => "minilogo.png",
                "reorderpoint" => $request->input('reorderpoint')   ,
                "shortdescription" => $request->input('shortdescription')   ,
                "stock" => 0   ,
                "subcategoryid" => $request->input('subcategoryid')   ,
                "priceid" => $precioid
            ]);

            if($imgu1==null){
                if($img1!=null){
                    $product = Producto::findOrFail($productid);
                    $product->fill([
                        "photo" => $productid."_A.".$img1->getClientOriginalExtension()
                    ]);
                    $product->save();
                    $nombre="/productos/".$productid."_A.".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$productid."_A.".$ext[sizeof($ext)-1];
                $product = Producto::findOrFail($productid);
                $product->fill([
                    "photo" => $nombre
                ]);
                $product->save();
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu1,'r'));
            }
            if($imgu2 == null){
                if($img2!=null){
                    $product = Producto::findOrFail($productid);
                    $product->fill([
                        "photo2" => $productid."_B.".$img2->getClientOriginalExtension()
                    ]);
                    $product->save();
                    $nombre="/productos/".$productid."_B.".$img2->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img2));
                }
            }else{
                $f = explode("/",$imgu2);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$productid."_2.".$ext[sizeof($ext)-1];
                $product = Producto::findOrFail($productid);
                $product->fill([
                    "photo2" => $nombre
                ]);
                $product->save();
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu2,'r'));
            }
            if($imgu3 == null){
                if($img3!=null){
                    $product = Producto::findOrFail($productid);
                    $product->fill([
                        "photo3" => $productid."_C.".$img3->getClientOriginalExtension()
                    ]);
                    $product->save();
                    $nombre="/productos/".$productid."_C.".$img3->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img3));
                }
            }else{
                $f = explode("/",$imgu3);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$productid."_3.".$ext[sizeof($ext)-1];
                $product = Producto::findOrFail($productid);
                $product->fill([
                    "photo3" => $nombre
                ]);
                $product->save();
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu3,'r'));
            }
            $respuesta = ["code"=>200, "msg"=>'El usuario fue creado exitosamente', 'detail' => 'success'];
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
        try{
            $id=base64_decode($id);
            $producto = Producto::findOrFail($id);
            $respuesta=["code"=>200,"msg"=>$producto,"detail"=>"ok"];
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
            $id=base64_decode($id);
            $img1 = $request->file("img1");
            $img2 = $request->file("img2");
            $img3 = $request->file("img3");
            $imgu1  = $request->input("imgu1");
            $imgu2  = $request->input("imgu2");
            $imgu3  = $request->input("imgu3");
            $producto = Producto::findOrFail($id);
            $up=([
                "categoryid"        => $request->input('categoryid')   ,
                "code"        => $request->input('code')   ,
                "currency"   => $request->input('currency')   ,
                "longdescription"      => $request->input('longdescription')   ,
                "brandid"  => $request->input('brandid')   ,
                "name"  => $request->input('name')   ,
               "reorderpoint" => $request->input('reorderpoint')   ,
                "shortdescription" => $request->input('shortdescription')   ,
                "subcategoryid" => $request->input('subcategoryid')   ,
            ]);


            if($imgu1==null){
                if($img1!=null){
                    $up["img1"]=$id."_1.". $request->file("img1")->getClientOriginalExtension();
                    if($producto->photo != "minilogo.png") {
                        Storage::delete("/productos/". $producto->photo);
                    }
                    $nombre="/productos/".$id."_1.".$img1->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img1));
                }
            }else{
                $f = explode("/",$imgu1);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$id."_1.".$ext[sizeof($ext)-1];
                $up['photo'] = $nombre;
                if($producto->photo != "minilogo.png"){
                    Storage::delete("/productos/". $producto->photo);
                }
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu1,'r'));
            }
            if($imgu2 == null){
                if($img2!=null){
                    $up["photo2"]=$id."_2.". $request->file("img2")->getClientOriginalExtension();
                    if($producto->photo2 != "minilogo.png") {
                        Storage::delete("/productos/". $producto->photo2);
                    }
                    $nombre="/productos/".$id."_2.".$img2->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img2));
                }
            }else{
                $f = explode("/",$imgu2);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$id."_2.".$ext[sizeof($ext)-1];
                $up['photo2'] = $nombre;
                if($producto->img2 != "minilogo.png"){
                    Storage::delete("/productos/". $producto->photo2);
                }
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu2,'r'));
            }
            if($imgu3 == null){
                if($img3!=null){
                    $up["photo3"]=$id."_3.". $request->file("img3")->getClientOriginalExtension();
                    if($producto->photo3 != "minilogo.png") {
                        Storage::delete("/productos/". $producto->photo3);
                    }
                    $nombre="/productos/".$id."_3.".$img3->getClientOriginalExtension();
                    Storage::disk('local')->put($nombre,  \File::get($img3));
                }
            }else{
                $f = explode("/",$imgu3);
                $ext = explode(".",$f[sizeof($f)-1]);
                $nombre=$id."_3.".$ext[sizeof($ext)-1];
                $up['photo3'] = $nombre;
                if($producto->photo3 != "minilogo.png"){
                    Storage::delete("/productos/". $producto->photo3);
                }
                Storage::disk('local')->put("/productos/".$nombre,  fopen($imgu3,'r'));
            }


            $producto->fill($up);
            $producto->save();

            /*No hay manera de revisar (hasta el momento) para revisar que cambiaron todos asi que los actualizaré a ambos*/
            $price = Precio::findOrFail($producto->priceid);
            $upp=[
                "price1"       => $request->input('price1')   ,
                "price2"       => $request->input('price2')   ,
                "price3"       => $request->input('price3')   ,
                "price4"       => $request->input('price4')   ,
                "price5"       => $request->input('price5')
            ];

            $price->fill($upp);
            $price->save();
            $respuesta = ["code"=>200, "msg"=>"Usuario actualizado","detail"=>"success"];
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
            $id=base64_decode($id);
            $producto = Producto::findOrFail($id);
            $priceid = $producto->priceid;
            if($producto->photo != "minilogo.png") {
                Storage::delete("/productos/". $producto->photo);
            }
            if($producto->photo2 != "minilogo.png") {
                Storage::delete("/productos/". $producto->photo2);
            }
            if($producto->photo3 != "minilogo.png") {
                Storage::delete("/productos/". $producto->photo3);
            }

            $producto->delete();
            $price = Precio::findOrFail($priceid);
            $price->delete();

            $respuesta = ["code"=>200, "msg"=>'El producto ha sido eliminado', 'detail' => 'success'];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(), 'detail' => 'warning'];
        }
        return Response::json($respuesta);
    }

    public function verMiniatura(Request $request, $id){
        try {
            $id = base64_decode($id);
            $producto = Producto::findOrFail($id);
            $dat = $request->input('no');
            $up=([
                "show" => $dat
            ]);
            $producto->fill($up);
            $producto->save();

            $respuesta = ["code"=>200, "msg"=>"Producto actualizado","detail"=>"success"];
        }catch(Exception $e){
            $respuesta = ["code"=>500, "msg"=>$e->getMessage(),"detail"=>"error"];
        }
        return Response::json($respuesta);
    }
}
