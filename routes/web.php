<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/{pagina}', 'UserController@home')->where('pagina', '[0-9]+');
Route::get('/', 'UserController@home')->name('home');
Route::get('/buscar', 'UserController@home_search');

Route::get('/directorio/{url}', 'UserController@directorio_local');

Route::post('/solicitud', 'UserController@solicitud');

// Admin locales
Route::get('/admin', 'AdminController@locales');
Route::get('/admin/locales', 'AdminController@locales')->name('locales');
Route::post('/admin/locales', 'AdminController@locales_search');
Route::get('/admin/locales/{pagina}', 'AdminController@locales')->where('pagina', '[0-9]+');
Route::get('/admin/locales/editar/{id}', 'AdminController@editar_local')->where('id', '[0-9]+')->name('locales.editar');
Route::post('/admin/locales/editar/{id}', 'AdminController@editar_local_editar');
Route::post('/admin/locales/relevante/{id}', 'AdminController@editar_local_relevante');
Route::post('/admin/locales/banner/{id}', 'AdminController@editar_local_banner');
Route::post('/admin/locales/activo/{id}', 'AdminController@editar_local_activo');

Route::get('/admin/locales/crear', 'AdminController@locales_crear');
Route::post('/admin/locales/crear', 'AdminController@locales_crear_nuevo');

Route::post('/admin/locales/editar/{id_local}/caracteristica', 'AdminController@editar_local_crear_caracteristica');
Route::post('/admin/locales/editar/{id_local}/caracteristica/{id_caracteristica}', 'AdminController@editar_local_editar_caracteristica');
Route::post('/admin/locales/editar/{id_local}/edificio', 'AdminController@editar_local_crear_edificio');
Route::post('/admin/locales/editar/{id_local}/edificio/{id_edificio}', 'AdminController@editar_local_editar_edificio');
Route::post('/admin/locales/editar/{id_local}/equipamiento', 'AdminController@editar_local_crear_equipamiento');
Route::post('/admin/locales/editar/{id_local}/equipamiento/{id_equipamiento}', 'AdminController@editar_local_editar_equipamiento');
Route::post('/admin/locales/editar/{id_local}/media/principal', 'AdminController@editar_local_imagen_principal');
Route::post('/admin/locales/editar/{id_local}/media/banner', 'AdminController@editar_local_imagen_banner');
Route::post('/admin/locales/eliminar/{id}', 'AdminController@eliminar_local');

// Admin sectores
Route::get('/admin/sectores', 'AdminController@sectores');
Route::post('/admin/sectores', 'AdminController@sectores_search');
Route::get('/admin/sectores/{pagina}', 'AdminController@sectores')->where('pagina', '[0-9]+');
Route::get('/admin/sectores/editar/{id}', 'AdminController@editar_sector')->where('id', '[0-9]+')->name('sectores.editar');
Route::post('/admin/sectores/editar/{id}', 'AdminController@editar_sector_editar');
Route::get('/admin/sectores/crear', 'AdminController@sectores_crear');
Route::post('/admin/sectores/crear', 'AdminController@sectores_crear_nuevo');
Route::post('/admin/sectores/eliminar/{id}', 'AdminController@eliminar_sector');

// Admin pobalciones
Route::get('/admin/poblaciones', 'AdminController@poblaciones');
Route::post('/admin/poblaciones', 'AdminController@poblaciones_search');
Route::get('/admin/poblaciones/{pagina}', 'AdminController@poblaciones')->where('pagina', '[0-9]+');
Route::get('/admin/poblaciones/editar/{id}', 'AdminController@editar_poblacion')->where('id', '[0-9]+')->name('poblaciones.editar');
Route::post('/admin/poblaciones/editar/{id}', 'AdminController@editar_poblacion_editar');
Route::get('/admin/poblaciones/crear', 'AdminController@poblaciones_crear');
Route::post('/admin/poblaciones/crear', 'AdminController@poblaciones_crear_nuevo');
Route::post('/admin/poblaciones/eliminar/{id}', 'AdminController@eliminar_poblacion');

// Admin solicitudes
Route::get('/admin/solicitudes', 'AdminController@solicitudes');
Route::post('/admin/solicitudes', 'AdminController@solicitudes_search');
Route::get('/admin/solicitudes/{pagina}', 'AdminController@solicitudes')->where('pagina', '[0-9]+');
Route::post('/admin/solicitudes/atender/{id}', 'AdminController@solicitudes_atender');

// Admin usuarios
Route::get('/admin/usuarios', 'AdminController@usuarios');
Route::post('/admin/usuarios', 'AdminController@usuarios_search');
Route::get('/admin/usuarios/{pagina}', 'AdminController@usuarios')->where('pagina', '[0-9]+');
Route::get('/admin/usuarios/editar/{id}', 'AdminController@editar_usuario')->where('id', '[0-9]+')->name('usuarios.editar');
Route::post('/admin/usuarios/editar/{id}', 'AdminController@editar_usuario_editar');
Route::get('/admin/usuarios/crear', 'AdminController@usuarios_crear');
Route::post('/admin/usuarios/crear', 'AdminController@usuarios_crear_nuevo');
Route::post('/admin/usuarios/eliminar/{id}', 'AdminController@eliminar_usuario');


// Admin configuration
Route::get('/admin/configuracion', 'AdminController@configuracion');

Route::get('/admin/login', 'LoginController@login_view')->name('login');
Route::post('/admin/logout', 'LoginController@logout')->name('logout');
Route::post('/admin/login', 'LoginController@login');

// Service worker
Route::post('/push','PushController@store');
Route::delete('/push','PushController@delete');
