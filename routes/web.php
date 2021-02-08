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

// Route::get('/', 'CidadeController@index');
// Route::resource('cidade','CidadeController');

Route::get('/', 'ClienteController@index');


Route::resource('cliente','ClienteController');

Route::resource('especialidade','EspecialidadeController');

Route::resource('veterinario','VeterinarioController');