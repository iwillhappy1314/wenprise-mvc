<?php

namespace Wenprise\Ajax;

use Wenprise\Foundation\ServiceProvider;

class AjaxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ajax', function($container)
        {
            return new AjaxBuilder($container['action']);
        });
    }
}
