<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/mesa/edit/{idMesa}', [App\Http\Controllers\MesaController::class, 'updateAjax'])
    ->name('edit-mesa-ajax');
Route::post('/mesa/save-all/{idEvento}', [App\Http\Controllers\MesaController::class, 'updateAll'])
    ->name('post-mesas');

Route::post('/convidado/edit/{idConvidado}', [App\Http\Controllers\ConvidadoController::class, 'updateAjaxConvidado'])
    ->name('edit-convidado-ajax');
Route::post('/convidado/save-all/{idMesa}', [App\Http\Controllers\ConvidadoController::class, 'updateAllConvidado'])
    ->name('post-convidados');
