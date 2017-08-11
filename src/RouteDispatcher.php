<?php namespace Monolith\WebRouting;

use Monolith\HTTP\{Request, Response};

abstract class RouteDispatcher {

    abstract protected function makeController(string $controller);

    final public function dispatch(MatchedRoute $route, Request $request): Response {
        try {
            $controller = $this->makeController($route->controllerName());
        } catch (\Exception $e) {
            throw new CouldNotResolveRouteController($route->controllerName());
        }
        $response = $controller->{$route->controllerMethod()}($request, $route->parameters());
        if ( ! $response instanceof Response) {
            throw new \UnexpectedValueException("Controller [{$route->controllerName()}@{$route->controllerMethod()}] needs to return an implementation of Monolith\HTTP\Response.");
        }
        return $response;
    }
}