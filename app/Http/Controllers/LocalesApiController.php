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
        $raw_query = null;

        $locales = Cache::remember('cachelocales',15/60, function() use (&$raw_query)
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

            $query = Local::select("locales.*", "sectores.titulo as sectores.titulo", "poblaciones.nombre as poblaciones.nombre")
                ->leftJoin('sectores', 'sectores.id', '=', 'id_sector')
                ->leftJoin('poblaciones', 'poblaciones.id', '=', 'id_poblacion');

            $data = request()->all();
            if(array_key_exists("filter", $data)) {
                $filters = explode(";", $data['filter']);
                foreach($filters as $filter)
                {
                    if($filter && count(explode(":",  $filter)) == 2) {
                        $column = explode(":",  $filter)[0];
                        $value = explode(":",  $filter)[1];
                        $query->where($column, 'like', '%'.$value.'%');
                    }
                }
            }

            if(array_key_exists("order", $data)) {
                $orders = explode(";", $data['order']);
                foreach($orders as $order)
                {
                    if($order && count(explode(":",  $order)) == 2) {
                        $column = explode(":",  $order)[0];
                        $value = explode(":",  $order)[1];
                        $query->orderBy($column, $value);
                    }
                }
            }
            $raw_query = $query->toSql();
			return $query->paginate(10);  // Paginamos cada 10 elementos.

        });


		// Para devolver un JSON con código de respuesta HTTP sin caché.
		// return response()->json(['status'=>'ok', 'data'=>Fabricante::all()],200);

		// Devolvemos el JSON usando caché.
		// return response()->json(['status'=>'ok', 'data'=>$locales],200);

		// Con la paginación lo haremos de la siguiente forma:
		// Devolviendo también la URL a l
		return response()->json([
            'query' => $raw_query,
            'status'=>'ok',
            'count' => $locales->count(),
            'total' => $locales->total(),
            'next' => $locales->nextPageUrl(),
            'previous' => $locales->previousPageUrl(),
            'data' => $locales->items()
            ], 200);
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
