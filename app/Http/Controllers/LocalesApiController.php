<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Local;

class LocalesApiController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locales=Cache::remember('cachelocales',15/60,function()
		{
			// Para la paginación en Laravel se usa "Paginator"
			// En lugar de devolver
			// return Fabricante::all();
			// devolveremos return Fabricante::paginate();
			//
			// Este método paginate() está orientado a interfaces gráficas.
			// Paginator tiene un método llamado render() que permite construir
			// los enlaces a página siguiente, anterior, etc..
			// Para la API RESTFUL usaremos un método más sencillo llamado simplePaginate() que
			// aporta la misma funcionalidad
			return Local::simplePaginate(10);  // Paginamos cada 10 elementos.

		});

		// Para devolver un JSON con código de respuesta HTTP sin caché.
		// return response()->json(['status'=>'ok', 'data'=>Fabricante::all()],200);

		// Devolvemos el JSON usando caché.
		// return response()->json(['status'=>'ok', 'data'=>$locales],200);

		// Con la paginación lo haremos de la siguiente forma:
		// Devolviendo también la URL a l
		return response()->json(['status'=>'ok', 'next' => $locales->nextPageUrl(),'previous' => $locales->previousPageUrl(),'data' => $locales->items()], 200);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
