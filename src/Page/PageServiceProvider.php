<?php

namespace Wenprise\Page;

use Wenprise\Foundation\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('page', function ($container) {

            $data = new PageData();

            return new PageBuilder($data,  $container['action'], null);
        });
    }
}
