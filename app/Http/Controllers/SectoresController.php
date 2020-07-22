<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;
use App\Http\Popos\SectorFilter;

use App\Http\Controllers\ImageController;

use App\Models\Usuario;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Poblacion;
use App\Models\Solicitud;
use App\Models\LocalCaracteristica;
use App\Models\LocalEdificio;
use App\Models\LocalEquipamiento;
use App\Models\LocalMedia;

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SectoresController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new SectorFilter(Session::getId(), null);
            Session::put($key, $filter);
            Session::save();
        }
        else
        {
            $filter = Session::get($key);
        }

        return $filter;
    }

    public static function get_filtered($filter, $page, $max_per_page)
    {
        $query_sectores = Sector::take($max_per_page);

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_sectores->where(function($query)  use ($search){
				$query->where('titulo','LIKE',"%{$search}%")
				    ->orWhere('descripcion','LIKE',"%{$search}%");
			});
        }

        $paginacion = Paginacion::get($query_sectores->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $sectores = $query_sectores->skip($paginacion['offset'])->take($max_per_page)->get();

        return [
            'sectores' => $sectores,
            'paginacion' => $paginacion
        ];
    }

    public static function manage_filter($session_key, $data)
    {
        $filter = self::manage_filter_session($session_key);

        if(array_key_exists('busqueda', $data))
        {
            if(trim($data['busqueda']) && trim($data['busqueda']) != '')
            {
                $filter->busqueda = trim($data['busqueda']);
            }
            else
            {
                $filter->busqueda = null;
            }
        }

        Session::put($session_key, $filter);
        Session::save();
    }

    public static function create($request)
    {
        $data = $request->validate([
            'titulo' => 'required|unique:sectores,titulo',
            'descripcion' => ''
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'titulo.unique' => 'El valor titulo ya existe en la base de datos.'
        ]);

        $sector = Sector::create([
            'titulo' => $data['titulo'],
            'orden' => 0,
            'descripcion' => $data['descripcion'],
        ]);

        return $sector;
    }

    public static function update($id_sector, $request)
    {
        $sector = Sector::find($id_sector);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($sector))
		{
			return view('404');
        }

        $data = $request->validate([
            'titulo' => 'required|unique:sectores,titulo,'.$id_sector,
            'descripcion' => ''
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'titulo.unique' => 'El valor titulo ya existe en la base de datos.',
        ]);

        $sector->titulo = $data['titulo'];
        $sector->descripcion = $data['descripcion'];
        $sector->actualizado_en = $now;

        $sector->save();

        return $sector;
    }

    public static function delete($id_sector)
    {
        $sector = Sector::find($id_sector);

        if(empty($sector))
		{
			return view('404');
        }

        if(!empty($sector->locales) && $sector->locales->count() > 0)
        {
            return redirect()->back()->withErrors("No se puede eliminar el sector, tiene locales asociados.");
        }

        $sector->delete();

        return null;
    }
}
