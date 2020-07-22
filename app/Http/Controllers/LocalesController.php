<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;
use App\Http\Popos\LocalFilter;

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

class LocalesController extends BaseController
{
    public static function manage_locales_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new LocalFilter(Session::getId(), null, null, 'relevante', 'desc', null);
            Session::put($key, $filter);
            Session::save();
        }
        else
        {
            $filter = Session::get($key);
        }

        return $filter;
    }

    public static function get_filtered_locales($filter, $page, $max_per_page)
    {
        $query_locales = Local::take($max_per_page);

        if($filter->poblacion)
        {
            $query_locales->where("id_poblacion", $filter->poblacion);
        }

        if($filter->sector)
        {
            $query_locales->where("id_sector", $filter->sector);
        }

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_locales->where(function($query)  use ($search){
				$query->where('titulo','LIKE',"%{$search}%")
				    ->orWhere('extracto','LIKE',"%{$search}%")
				    ->orWhere('descripcion','LIKE',"%{$search}%");
			});
        }

        $order_direction = $filter->order_direction && ($filter->order_direction == 'asc' || $filter->order_direction == 'desc') ? $filter->order_direction : 'desc';

        if(!$filter->order || $filter->order == 'relevancia')
        {
            $query_locales->orderBy("relevante", $order_direction);
        }

        if($filter->order == 'precio')
        {
            $query_locales->orderBy("precio", $order_direction);
        }

        if($filter->order == 'superficie')
        {
            $query_locales->orderBy("metros", $order_direction);
        }

        $paginacion = Paginacion::get($query_locales->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $locales = $query_locales->skip($paginacion['offset'])->take($max_per_page)->get();

        foreach($locales as $local)
        {
            foreach($local->medias as $media)
            {
                if($media->tipo == 'principal')
                {
                    $local->imagen_principal = $media;
                }
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return [
            'locales' => $locales,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ];
    }

    public static function manage_locales_filter($session_key, $data)
    {
        $filter = self::manage_locales_filter_session($session_key);

        if($data && array_key_exists('action', $data) && $data['action'] == 'order')
        {
            if(array_key_exists('relevancia', $data))
            {
                $filter->order = 'relevancia';
                $filter->order_direction = $data['relevancia'];
            }

            if(array_key_exists('precio', $data))
            {
                $filter->order = 'precio';
                $filter->order_direction = $data['precio'];
            }

            if(array_key_exists('superficie', $data))
            {
                $filter->order = 'superficie';
                $filter->order_direction = $data['superficie'];
            }
        }

        if($data && array_key_exists('action', $data) && $data['action'] == 'search')
        {
            if(array_key_exists('sector', $data))
            {
                if($data['sector'] != 'none')
                {
                    $filter->sector = $data['sector'];
                }
                else
                {
                    $filter->sector = null;
                }
            }

            if(array_key_exists('poblacion', $data))
            {
                if($data['poblacion'] != 'none')
                {
                    $filter->poblacion = $data['poblacion'];
                }
                else
                {
                    $filter->poblacion = null;
                }
            }

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
        }

        Session::put($session_key, $filter);
        Session::save();
    }
}
