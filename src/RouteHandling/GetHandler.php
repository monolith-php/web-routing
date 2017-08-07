<?php namespace Monolith\WebRouting\RouteHandling;

use Monolith\WebRouting\CompiledRoute;
use Monolith\WebRouting\Route;
use function strtolower;

class GetHandler implements RouteHandler {
    public function handles(string $identifier): bool {
        return strtolower($identifier) === 'get';
    }

    public function compile(Route $route): CompiledRoute {
        return CompiledRoute::GET($route->uri(), $route->controller(), 'get');
    }
}