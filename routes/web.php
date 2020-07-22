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
Route::get('/{pagina}', 'UserController@directorio')->where('pagina', '[0-9]+');
Route::get('/', 'UserController@home');
Route::post('/', 'UserController@home_action');

Route::get('/directorio/{url}', 'UserController@directorio_local');

Route::post('/solicitud', 'UserController@solicitud');

// Admin locales
Route::get('/admin/locales', 'AdminController@locales');
Route::get('/admin/locales/{pagina}', 'AdminController@locales')->where('pagina', '[0-9]+');
Route::get('/admin/locales/editar/{id}', 'AdminController@editar_local')->where('id', '[0-9]+');
Route::post('/admin/locales/editar/{id}', 'AdminController@editar_local_editar');

Route::post('/admin/locales/editar/{id_local}/caracteristica', 'AdminController@editar_local_crear_caracteristica');
Route::post('/admin/locales/editar/{id_local}/caracteristica/{id_caracteristica}', 'AdminController@editar_local_editar_caracteristica');
Route::post('/admin/locales/editar/{id_local}/edificio', 'AdminController@editar_local_crear_edificio');
Route::post('/admin/locales/editar/{id_local}/edificio/{id_edificio}', 'AdminController@editar_local_editar_edificio');
Route::post('/admin/locales/editar/{id_local}/equipamiento', 'AdminController@editar_local_crear_equipamiento');
Route::post('/admin/locales/editar/{id_local}/equipamiento/{id_equipamiento}', 'AdminController@editar_local_editar_equipamiento');
Route::post('/admin/locales/editar/{id_local}/media/principal', 'AdminController@editar_local_imagen_principal');


Route::get('/admin/sectores', 'AdminController@sectores');
Route::get('/admin/sectores/{pagina}', 'AdminController@sectores')->where('pagina', '[0-9]+');

Route::get('/admin/poblaciones', 'AdminController@poblaciones');
Route::get('/admin/poblaciones/{pagina}', 'AdminController@poblaciones')->where('pagina', '[0-9]+');

Route::get('/admin/solicitudes', 'AdminController@solicitudes');
Route::get('/admin/solicitudes/{pagina}', 'AdminController@solicitudes')->where('pagina', '[0-9]+');

Route::get('/admin/usuarios', 'AdminController@usuarios');
Route::get('/admin/usuarios/{pagina}', 'AdminController@usuarios')->where('pagina', '[0-9]+');

