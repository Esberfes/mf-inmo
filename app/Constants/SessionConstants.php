<?php

namespace App\Constants;

class SessionConstants
{
    const USER_LOCALES_FILTER = 'user-locales-filter';
    const ADMIN_LOCALES_FILTER = 'admin-locales-filter';
    const ADMIN_SECTORES_FILTER = 'admin-sectores-filter';
    const ADMIN_POBLACIONES_FILTER = 'admin-poblaciones-filter';
    const ADMIN_USUARIOS_FILTER = 'admin-usuarios-filter';
    const ADMIN_LOCALES_SOLICITUDES_FILTER = 'admin-locales-solicitudes-filter';
    const ADMIN_SOLICITUDES_FILTER = 'admin-solicitudes-filter';
    const ADMIN_USER = 'admin-user';


    const FILTRO_PRECIOS_COMPRA = [
        "1.000" => 1000,
        "10.000" => 10000,
        "20.000" => 20000,
        "40.000" => 40000,
        "80.000" => 80000,
        "160.000" => 160000,
        "500.000" => 500000,
        "1.000.000" => 1000000
    ];

    const FILTRO_PRECIOS_ALQUILER = [
        "500" => 500,
        "1.000" => 1000,
        "2.000" => 2000,
        "3.000" => 3000,
        "4.000" => 4000,
        "5.000" => 5000,
        "10.000" => 10000
    ];
}
