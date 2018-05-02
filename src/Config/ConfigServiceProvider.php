<?php

namespace Wenprise\Config;

use Wenprise\Foundation\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('config.finder', function () {
            return new ConfigFinder();
        });

        $this->app->singleton('config.factory', function ($container) {
            return new ConfigFactory($container['config.finder']);
        });
    }
}
