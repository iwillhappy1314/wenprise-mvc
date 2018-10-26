<?php

namespace Wenprise\Html;

use Wenprise\Foundation\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('html', 'Wenprise\Html\HtmlBuilder');
    }
}
