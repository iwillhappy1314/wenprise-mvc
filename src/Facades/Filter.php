<?php

namespace Wenprise\Facades;


/**
 * @method add($hook, $callback, $priority = 10, $accepted_args = 3)
 * @method remove($hook, $priority = 10, $callback = null)
 * @method run($hook, $args = null)
 * @method exists($hook)
 * @method getCallback($hook)
 */
class Filter extends Facade
{
    /**
     * Return the service provider key responsible for the filter class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'filter';
    }
}
