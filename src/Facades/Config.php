<?php

namespace Wenprise\Facades;

/**
 * @method static get($name)
 */
class Config extends Facade
{
    /**
     * Return the service provider key responsible for the config class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'config.factory';
    }
}
