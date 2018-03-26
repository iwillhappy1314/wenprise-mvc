<?php

namespace Wenprise\Facades;

/**
 * @method static add($hook, $callback, $priority = 10, $accepted_args = 3)
 * @method static remove($hook, $priority = 10, $callback = null)
 * @method static run($hook, $args = null)
 * @method static exists($hook)
 * @method static getCallback($hook)
 */
class Action extends Facade
{
    /**
     * Return the service provider key responsible for the action class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'action';
    }
}
