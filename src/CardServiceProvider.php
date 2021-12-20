<?php

namespace Ontherocksoftware\NovaRedAmberGreenCard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('nova-red-amber-green-card.php'),
            ], 'config');
        }

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-red-amber-green-card', __DIR__ . '/../dist/js/card.js');
            Nova::style('nova-red-amber-green-card', __DIR__ . '/../dist/css/card.css');
        });
    }

    /**
     * Register the card's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-red-amber-green-card')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'nova-red-amber-green-card');
    }
}
