<?php

namespace Wenprise\Mvc\Facades;

/**
 *
 * @method static method
 * @method static root
 * @method static url
 * @method static fullUrl
 * @method static fullUrlWithQuery
 * @method static path
 * @method static decodedPath
 * @method static segments
 * @method static is
 * @method static routeIs
 * @method static fullUrlIs
 * @method static ajax
 * @method static pjax
 * @method static secure
 * @method static ip
 * @method static ips
 * @method static userAgent
 * @method static merge(array $input)
 * @method static replace(array $input)
 * @method static json(array $input)
 * @method static getInputSource
 * @method static session
 * @method static user
 * @method static route
 * @method static fingerprint
 * @method static setJson($json)
 * @method static toArray
 */
class Input extends Facade
{
    /**
     * Get an item from the input data.
     * This method is used for all request verbs (GET, POST, PUT, and DELETE).
     *
     * @param string $key
     * @param mixed  $default A default value if not found.
     *
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        return static::$app['request']->input($key, $default);
    }

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public static function all()
    {
        return array_merge_recursive(static::$app['request']->input(), static::$app['request']->files->all());
    }

    /**
     * Return the service provider key responsible for the request class.
     * The key must be the same as the one used when registering
     * the service provider.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'request';
    }
}
