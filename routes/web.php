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


Route::get('/admin/locales', 'AdminController@locales');
Route::get('/admin/locales/{pagina}', 'AdminController@locales')->where('pagina', '[0-9]+');

Route::get('/admin/sectores', 'AdminController@sectores');
Route::get('/admin/sectores/{pagina}', 'AdminController@sectores')->where('pagina', '[0-9]+');

Route::get('/admin/poblaciones', 'AdminController@poblaciones');
Route::get('/admin/poblaciones/{pagina}', 'AdminController@poblaciones')->where('pagina', '[0-9]+');

Route::get('/admin/solicitudes', 'AdminController@solicitudes');
Route::get('/admin/solicitudes/{pagina}', 'AdminController@solicitudes')->where('pagina', '[0-9]+');

Route::get('/admin/usuarios', 'AdminController@usuarios');
Route::get('/admin/usuarios/{pagina}', 'AdminController@usuarios')->where('pagina', '[0-9]+');
