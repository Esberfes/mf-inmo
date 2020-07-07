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

Route::get('/', 'UserController@home');
Route::post('/', 'UserController@home_action');
Route::get('/directorio/{pagina}', 'UserController@directorio')->where('pagina', '[0-9]+');
Route::get('/directorio/{url}', 'UserController@directorio_local');
