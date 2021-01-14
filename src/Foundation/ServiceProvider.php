<?php

namespace Wenprise\Mvc\Foundation;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Dynamically handle missing method calls.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {
        if ($method === 'boot') {
            return;
        }

        throw new \RuntimeException("Call to undefined method [{$method}]");
    }
}
