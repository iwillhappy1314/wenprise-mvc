<?php

namespace Wenprise\Mvc\Route\Matching;

use Wenprise\Mvc\Foundation\Request;
use Wenprise\Mvc\Route\Route;

interface IMatching
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param \Wenprise\Mvc\Route\Route        $route
     * @param \Wenprise\Mvc\Foundation\Request $request
     *
     * @return bool
     */
    public function matches(Route $route, Request $request);
}
