<?php

namespace Wenprise\Facades;

class Route extends Facade
{
    /**
     * Return the service provider key responsible for the route class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'router';
    }
}
