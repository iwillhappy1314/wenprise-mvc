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
    public function __call(string $method, array $parameters): mixed
    {
        if ($method === 'boot') {
            return null;
        }

        throw new \RuntimeException("Call to undefined method [{$method}]");
    }
}
