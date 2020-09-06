<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Image;

class ImageController extends BaseController
{
	public static function local_pricipal($file)
	{
		$name_destacada = md5_file($file);
		$storage_path = storage_path('app/public/img/locales/principal');
		$path_destacada = $storage_path.'/'.$name_destacada.'.'.$file->getClientOriginalExtension();;

		$img = Image::make($file->getRealPath());
        $img->resize(900, null, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($path_destacada);

		return $path_destacada = explode("app/", str_replace("public" , "storage" , $path_destacada))[1];
    }

    public static function local_banner($file)
	{
		$name_destacada = md5_file($file).'.'.$file->getClientOriginalExtension();;
		$storage_path = storage_path('app/public/img/locales/banner');
		$path_destacada = $storage_path.'/'.$name_destacada;
        $file->move($storage_path, $name_destacada);

		return $path_destacada = explode("app/", str_replace("public" , "storage" , $path_destacada))[1];
	}
}
