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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['prefix' => 'recibos'], function () {
    Route::get('/', [App\Http\Controllers\RecibosController::class, 'index'])->name('recibos.index');
    Route::get('/novo', [App\Http\Controllers\RecibosController::class, 'novo'])->name('recibos.novo');
    Route::post('/salvar', [App\Http\Controllers\RecibosController::class, 'update'])->name('recibos.salvar');
    Route::get('/editar/{id}', [App\Http\Controllers\RecibosController::class, 'editar'])->name('recibos.editar');
    Route::post('/search', [App\Http\Controllers\RecibosController::class, 'search'])->name('recibos.search');
    Route::post('/search/servicos', [App\Http\Controllers\RecibosController::class, 'searchServicos'])->name('recibos.search.servico');
    Route::post('/search/date', [App\Http\Controllers\RecibosController::class, 'searchRecibosDate'])->name('recibos.search.date');
    Route::post('/search/all', [App\Http\Controllers\RecibosController::class, 'searchRecibosAll'])->name('recibos.search.all');
    Route::post('/imprimir', [App\Http\Controllers\RecibosController::class, 'imprimirRecibosSelecionados'])->name('recibos.imprimir.selecionados');
    Route::post('/deletar', [App\Http\Controllers\RecibosController::class, 'deletarRecibo'])->name('recibos.deletar');
});
