<?php

namespace Wenprise\Mvc\Facades;


/**
 * @method static share($key, $value = null)
 * @method static file($path, $data = [], $mergeData = [])
 * @method static make($view, $data = [], $mergeData = [])
 * @method static first(array $views, $data = [], $mergeData = [])
 * @method static exists($view)
 * @method static shared($key, $default = null)
 */
class View extends Facade
{
    /**
     * Return the service provider key responsible for the view class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
