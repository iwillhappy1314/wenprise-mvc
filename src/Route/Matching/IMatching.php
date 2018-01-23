<?php

namespace Wenprise\Route\Matching;

use Wenprise\Foundation\Request;
use Wenprise\Route\Route;

interface IMatching
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param \Wenprise\Route\Route        $route
     * @param \Wenprise\Foundation\Request $request
     *
     * @return bool
     */
    public function matches(Route $route, Request $request);
}
