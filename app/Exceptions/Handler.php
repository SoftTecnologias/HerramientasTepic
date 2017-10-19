<?php

namespace App\Exceptions;

use App\Usuarios;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $marcas = DB::table('brand')->select('id', 'name')
            ->where(DB::raw('(select COUNT(*) from product  where brand.id = product.brandid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->take(40)->orderBy('name', 'asc')->get();
        //Menu de categorias
        $categorias = DB::table('category')->select('id', 'name')->take(40)
            ->where('name', 'not like', 'Nota de credito')
            ->where(DB::raw('(select COUNT(*) from product  where category.id = product.categoryid AND product.photo not like \'minilogo.png\')'), '>', 0)
            ->orderBy('name', 'asc')->get();
        //menu de servicios
        $servicios = DB::table('services')->select('id', 'title', 'shortdescription', 'longdescription', 'img', 'selected')->take(10)->orderBy('title', 'asc')->get();
        //Marca actual (Migaja)
        foreach ($servicios as $servicio) {
            $servicio->id = base64_encode($servicio->id);
        }
        foreach ($categorias as $c) {
            $c->id = base64_encode($c->id);
        }
        foreach ($marcas as $m) {
            $m->id = base64_encode($m->id);
        }

       if(method_exists('getStatusCode',$e)) {
           if ($e->getStatusCode() >= 404) {
                dd($e);
               if ($request->cookie('cliente') == null) {
                   return response()->view('errors.404', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false], 404);
               } else {
                   dd($e);
                   $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                   return response()->view('errors.404', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user], 404);
               }
           } else {
               if ($e->getStatusCode() >= 500) {
                   dd($e);
                   if ($request->cookie('cliente') == null) {
                       return response()->view('errors.500', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => false], 500);
                   } else {
                       $user = Usuarios::where('apikey', $request->cookie('cliente')['apikey'])->firstOrFail();
                       return response()->view('errors.500', ['marcas' => $marcas, 'categorias' => $categorias, 'servicios' => $servicios, 'logueado' => $user], 500);
                   }
               }
           }
       }
        return parent::render($request, $e);
    }
}
