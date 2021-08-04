<?php

namespace App\Providers;

use App\Domain\Interfaces\INotificador;
use App\Domain\Interfaces\Services\IItemService;
use App\Domain\Interfaces\Services\IUsuarioService;

use App\Domain\Notificacao\Notificador;
use App\Domain\Services\ItemService;
use App\Domain\Services\UsuarioService;

use App\Repository\ItemRepository;
use App\Repository\UsuarioRepository;

use App\Domain\Interfaces\Repository\IItemRepository;
use App\Domain\Interfaces\Repository\IUsuarioRepository;

use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(IItemService::class, ItemService::class);

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
