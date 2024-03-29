<?php namespace Monolith\WebRouting\Methods;

use Monolith\WebRouting\CompiledRoute;
use Monolith\WebRouting\CompiledRoutes;
use Monolith\WebRouting\MethodCompiler;
use Monolith\WebRouting\Middlewares;
use Monolith\WebRouting\Route;
use Monolith\WebRouting\RouteParameters;
use function strtolower;

final class FormMethod implements MethodCompiler
{
    public static function defineRoute(string $uri, string $controllerClass): Route
    {
        return new Route('form', $uri, $controllerClass, new RouteParameters, new Middlewares);
    }

    public function handles(string $method): bool
    {
        return strtolower($method) === 'form';
    }

    public function compile(Route $route): CompiledRoutes
    {
        return new CompiledRoutes(
            [
                new CompiledRoute('get', $route->uri(), $route->controllerClass(), 'form', $route->parameters(), $route->middlewares()),
                new CompiledRoute('post', $route->uri(), $route->controllerClass(), 'submit', $route->parameters(), $route->middlewares()),
            ]
        );
    }
}