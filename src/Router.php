<?php namespace Monolith\WebRouting;

use Monolith\Collections\Collection;
use Monolith\Http\{Request, Response};

final class Router
{
    private RouteDefinitions|Collection $routes;
    private RouteCompiler $compiler;
    private RouteMatcher $matcher;
    private RouteDispatcher $dispatcher;
    private ReverseRouting $reverseRouting;
    private CompiledRoutes $compiled;

    public function __construct(
        RouteCompiler $compiler,
        RouteMatcher $matcher,
        RouteDispatcher $dispatcher,
        ReverseRouting $reverseRouting
    ) {
        $this->routes = new RouteDefinitions();
        $this->compiler = $compiler;
        $this->matcher = $matcher;
        $this->dispatcher = $dispatcher;
        $this->reverseRouting = $reverseRouting;
    }

    public function registerRoutes(
        RouteDefinitions $routes
    ): void {
        $this->routes = $this->routes->merge($routes);
    }

    public function dispatch(
        Request $request
    ): Response {
        // compile routes
        $this->compiled = $this->compiler->compile($this->routes);

        // match the route
        $matchedRoute = $this->matcher->match($request, $this->compiled);

        // dispatch request and send response
        return $this->dispatcher->dispatch($matchedRoute, $request);
    }

    public function url(
        string $controllerClass,
        array $arguments = []
    ): string {
        return $this->reverseRouting->route(
            $this->compiled ?? $this->compiler->compile($this->routes),
            $controllerClass,
            $arguments
        );
    }
}