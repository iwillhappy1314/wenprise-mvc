<?php

namespace Wenprise\Mvc\Finder;

use Illuminate\Filesystem\Filesystem;
use Wenprise\Mvc\Foundation\ServiceProvider;

class FinderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('filesystem', function () {
            return new Filesystem();
        });
    }
}
