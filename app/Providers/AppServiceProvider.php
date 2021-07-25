<?php

namespace App\Providers;

use App\Domain\Interfaces\INotificador;
use App\Domain\Interfaces\Services\IItemService;
use App\Domain\Interfaces\Services\IUsuarioService;
use App\Domain\Notificador;
use App\Domain\Services\UsuarioService;
use App\Repository\ItemRepository;
use App\Repository\UsuarioRepository;
use Illuminate\Support\ServiceProvider;
use App\Domain\Interfaces\Repository\IItemRepository;
use App\Domain\Interfaces\Repository\IUsuarioRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(INotificador::class, Notificador::class);

        $this->app->bind(IUsuarioService::class, UsuarioService::class);

        $this->app->bind(IItemRepository::class, ItemRepository::class);
        $this->app->bind(IUsuarioRepository::class, UsuarioRepository::class);
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
