<?php

namespace Wenprise\Facades;


/**
 * @method static void get(string $uri, \Closure|array|string $action)
 * @method static void post(string $uri, \Closure|array|string $action)
 * @method static void put(string $uri, \Closure|array|string $action)
 * @method static void delete(string $uri, \Closure|array|string $action)
 * @method static void patch(string $uri, \Closure|array|string $action)
 * @method static void options(string $uri, \Closure|array|string $action)
 * @method static \Illuminate\Routing\Route match(array|string $methods, string $uri, \Closure|array|string $action)
 * @method static void resource(string $name, string $controller, array $options = [])
 * @method static mixed prefix(string $name)
 * @method static void group(array $attributes, \Closure $callback)
 * @method static \Illuminate\Routing\Route substituteBindings(\Illuminate\Routing\Route $route)
 * @method static void substituteImplicitBindings(\Illuminate\Routing\Route $route)
 *
 * @see \Illuminate\Routing\Router
 */
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
