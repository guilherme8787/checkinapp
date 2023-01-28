<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/new-event', [App\Http\Controllers\Eventos::class, 'store'])->name('new-event')->middleware('csrf');
Route::get('/event/{id}', [App\Http\Controllers\Eventos::class, 'get'])->name('get-event');
Route::get('/events', [App\Http\Controllers\Eventos::class, 'get'])->name('get-events');
Route::post('/delete-event/{id}', [App\Http\Controllers\Eventos::class, 'destroy'])->name('delete-event');

Route::post('/new-sharp-key', [App\Http\Controllers\SharpSpring::class, 'store'])->name('new-sharp-key')->middleware('csrf');

Route::prefix('sharpspring')->group(function () {
    Route::post('/get-guest/{guestList}', [App\Http\Controllers\SharpSpring::class, 'getGuest'])->name('get-guest')->middleware('csrf');
    Route::post('/get-guest-mail/{listId}/{guestList}', [App\Http\Controllers\SharpSpring::class, 'getGuestByEmail'])->name('get-guest-mail')->middleware('csrf');
    Route::get('/set-visitor/{sharpId}', [App\Http\Controllers\SharpSpring::class, 'setVisitor'])->name('set-visitor')->middleware('csrf');
});

Route::get('/get-sharp-key', [App\Http\Controllers\SharpSpring::class, 'getCredentials'])->name('get-sharp-key');

Route::get('/evento/{listId}/{guestEventId}', [App\Http\Controllers\Eventos::class, 'event'])->name('credenciamento');

Route::get('/qrcode-generate/{content}', [App\Http\Controllers\QrCodeManager::class, 'generetor'])->name('qrcode-generate');


Route::get('/imprimir/{listId}/{hash}', [App\Http\Controllers\CredencialController::class, 'printCredential'])->name('get-credential');

Route::prefix('list')->group(function () {
    Route::get('/{id}', [App\Http\Controllers\ListManagerController::class, 'get'])->name('get-list');
    Route::post('/edit/{id}', [App\Http\Controllers\ListManagerController::class, 'update'])->name('edit-list')->middleware('csrf');
});

Route::get('/dashboard/count/{eventId}', [App\Http\Controllers\DashboardController::class, 'visitorRealTime'])->name('dashboard-count');
Route::get('/dashboard/count-local/{listId}/{eventId}', [App\Http\Controllers\DashboardController::class, 'visitorRealTimeLocal'])->name('dashboard-count-real');

Route::get('/dashboard/{eventId}/{listId}', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/vcard/{listId}/{hash}', [App\Http\Controllers\CredencialController::class, 'cardIndex'])->name('v-card-route');

Route::get('/get-charts/{eventId}', [App\Http\Controllers\DashboardController::class, 'getCharts'])->name('get-charts');

Route::post('/dashboard/new/{eventId}/{listId}', [App\Http\Controllers\DashboardController::class, 'newChart'])->name('new-chart');

Route::get('/get-chart-data/{listId}/{type}/{index}', [App\Http\Controllers\DashboardController::class, 'getChartData'])->name('get-chart-data');

Route::get('/v-card-generate', App\Http\Controllers\VcardController::class)->name('generate-vcard');

Route::get('/mesas/{idEvento}', [App\Http\Controllers\MesaController::class, 'list'])->name('get-mesas');
Route::get('/mesas/listar/{idEvento}', [App\Http\Controllers\MesaController::class, 'listAll'])->name('list-mesas');
Route::get('mesa/{idMesa}', [App\Http\Controllers\MesaController::class, 'getMesa'])->name('get-mesa');
Route::post('/new-mesa/{idEvento}', [App\Http\Controllers\MesaController::class, 'create'])->name('new-mesa')->middleware('csrf');
Route::post('/mesa/edit/{idMesa}', [App\Http\Controllers\MesaController::class, 'update'])->name('edit-mesa');
Route::post('/delete-mesa/{idMesa}', [App\Http\Controllers\MesaController::class, 'delete'])->name('delete-mesa');

Route::post('/newconvidado', [App\Http\Controllers\ConvidadoController::class, 'create'])->name('new-convidado')->middleware('csrf');
Route::post('/convidado/{idConvidado}', [App\Http\Controllers\ConvidadoController::class, 'update'])->name('edit-convidado');
Route::get('/convidados/{idMesa}', [App\Http\Controllers\ConvidadoController::class, 'list'])->name('get-convidados');
Route::post('/delete-convidado/{idConvidado}', [App\Http\Controllers\ConvidadoController::class, 'delete'])->name('delete-convidado');
Route::get('/convidados/listar/{idMesa}', [App\Http\Controllers\ConvidadoController::class, 'listGuests'])->name('list-guests');

Route::post('/muda-mesa-convidado/{idEvento}', [App\Http\Controllers\ConvidadoController::class, 'mudarConvidadoMesa'])->name('muda-convidado-mesa');
Route::post('/muda-cadeira-convidado/{idEvento}', [App\Http\Controllers\ConvidadoController::class, 'mudarConvidadoCadeira'])->name('muda-convidado-cadeira');

Route::get('/info-mesa/{id}', [App\Http\Controllers\InformacaoController::class, 'index'])->name('get-info-mesa');

Route::get('/relatorio-mesa/{id}', [App\Http\Controllers\RelatoriosController::class, 'index'])->name('get-relatorio-mesa');

Route::post('/gerar-mesas/{idEvento}', [App\Http\Controllers\MesaController::class, 'gerarMesas'])->name('gerar-mesas');
Route::get('/quantidade-de-convidados/{idEvento}', [App\Http\Controllers\MesaController::class, 'quantidadeDeConvidados'])->name('quantidade-de-convidados');

Route::post('/adicionar-convidado-acompanhante', [App\Http\Controllers\ConvidadoController::class, 'adicionarAcompanhante'])->name('adicionar-convidado-acompanhante');

Route::prefix('table')
    ->namespace('App\Http\Controllers\Mesa')
    ->group(
        function () {
            Route::post('/gen/{eventId}', GerarMesasController::class)->name('table.gen');
        }
    );


Route::get('/editar-campos/{listId}', [App\Http\Controllers\CredencialController::class, 'editarCredencial'])->name('edit-credential');
