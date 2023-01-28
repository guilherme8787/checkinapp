<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Models\Convidado;
use App\Models\Eventos;
use App\Models\Mesa;
use App\Repository\Evento\{EventoRepositoryInterface, EventoRepositoryEloquent};
use App\Repository\SharpSpring\{SharpSpringRepositoryInterface, SharpSpringRepositoryRest};
use App\Repository\Mesa\{MesaRepository, Contracts\MesaRepositoryInterface};
use App\Services\Mesa\{MesaService, Contracts\MesaServiceInterface};
use App\Repository\Convidado\{ConvidadoRepository, Contracts\ConvidadoRepositoryInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Eventos
        $this->app->bind(EventoRepositoryInterface::class, EventoRepositoryEloquent::class);
        $this->app->bind(EventoRepositoryInterface::class, function () {
            return new EventoRepositoryEloquent(new Eventos);
        });

        // SharpSpring
        $this->app->bind(SharpSpringRepositoryInterface::class, SharpSpringRepositoryRest::class);
        $this->app->bind(SharpSpringRepositoryInterface::class, function () {
            return new SharpSpringRepositoryRest(new Storage);
        });

        // Mesa
        $this->app->bind(MesaServiceInterface::class, MesaService::class);
        $this->app->bind(MesaRepositoryInterface::class, function () {
            return new MesaRepository(new Mesa);
        });

        // Convidado
        $this->app->bind(ConvidadoServiceInterface::class, ConvidadoService::class);
        $this->app->bind(ConvidadoRepositoryInterface::class, function () {
            return new ConvidadoRepository(new Convidado);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
