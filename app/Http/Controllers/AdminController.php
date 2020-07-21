<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;

use App\Models\Usuario;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Poblacion;
use App\Models\Solicitud;

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 4;
	private $max_paginacion = 5;

}
