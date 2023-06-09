<?php

use App\Http\Controllers\MarcaClaseController;
use App\Http\Controllers\NumerosAleatoriosController;
use App\Http\Controllers\SimulacionController;
use App\Http\Controllers\TestAleatoriedadController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => ['auth']], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'App\Http\Controllers\PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'App\Http\Controllers\PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'App\Http\Controllers\PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'App\Http\Controllers\PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'App\Http\Controllers\PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'App\Http\Controllers\PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'App\Http\Controllers\PageController@upgrade']);
        Route::get('/indexF',[NumerosAleatoriosController::class, 'indexF'])->name('NA.indexF');
        Route::get('/indexC',[NumerosAleatoriosController::class, 'indexC'])->name('NA.indexC');
        Route::post('/fibonacci',[NumerosAleatoriosController::class, 'fibonacci'])->name('NA.fibonacci');
        Route::post('/congruencias',[NumerosAleatoriosController::class, 'congruencias'])->name('NA.congruencias');
        Route::post('/fibonacciS',[NumerosAleatoriosController::class, 'storeF'])->name('NA.storeF');
        Route::post('/congruenciasS',[NumerosAleatoriosController::class, 'storeC'])->name('NA.storeC');
        Route::get('/indexChi',[TestAleatoriedadController::class, 'indexChi'])->name('TA.indexChi');
        Route::get('/indexChiA',[TestAleatoriedadController::class, 'chiCuadrado'])->name('TA.Chi');
        Route::get('/indexPoker',[TestAleatoriedadController::class, 'indexPoker'])->name('TA.indexPoker');
        Route::get('/indexPokerA',[TestAleatoriedadController::class, 'poker'])->name('TA.Poker');
        Route::post('/indexChiG',[TestAleatoriedadController::class, 'storeChi'])->name('TA.storeChi');
        Route::post('/indexPokerG',[TestAleatoriedadController::class, 'storePoker'])->name('TA.storePoker');
        Route::get('/indexClases',[MarcaClaseController::class, 'indexClases'])->name('MC.indexClases');
        Route::get('/indexClasesA',[MarcaClaseController::class, 'marcaclases'])->name('MC.MarcaClases');
        Route::get('/indexSimulacion',[SimulacionController::class, 'indexSimulacion'])->name('S.indexSimulacion');
        Route::get('/indexSimulacionA',[SimulacionController::class, 'simulacion'])->name('S.Simulacion');
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Httphttps://www.youtube.com/watch?v=xd8dKY6Ozrg&ab_channel=HacksmithIndustriesrController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

