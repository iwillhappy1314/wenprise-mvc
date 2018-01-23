<?php

namespace Wenprise\Route;

use Illuminate\Events\Dispatcher;
use Wenprise\Foundation\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        /*
         * Register the Events Dispatcher into the container.
         */
        $this->app->bind('events', function ($container) {
            return new Dispatcher($container);
        });
        $this->app->singleton('router', function ($container) {
            return new Router($container['events'], $container);
        });
    }
}
