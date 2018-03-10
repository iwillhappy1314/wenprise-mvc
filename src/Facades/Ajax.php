<?php

namespace Wenprise\Facades;


/**
 * @method static listen($name, $callback, $logged = 'both')
 */
class Ajax extends Facade
{
    /**
     * Return the service provider key responsible for the ajax class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ajax';
    }
}
