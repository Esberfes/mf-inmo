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
}
