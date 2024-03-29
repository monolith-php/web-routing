<?php namespace Monolith\WebRouting;

final class CompiledRoute
{
    private string $httpMethod;
    private string $regex;
    private string $controllerClass;
    private string $controllerMethod;
    private Middlewares $middlewares;
    private RouteParameters $parameters;

    public function __construct(
        string $httpMethod,
        string $uri,
        string $controllerClass,
        string $controllerMethod,
        RouteParameters $parameters,
        Middlewares $middlewares
    ) {
        $this->httpMethod = strtolower($httpMethod);
        $this->uri = $uri;
        $this->regex = $this->routeRegexFromUriString($uri);
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->middlewares = $middlewares;
        $this->parameters = $parameters;
    }

    public function httpMethod(): string
    {
        return $this->httpMethod;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function regex(): string
    {
        return $this->regex;
    }

    public function controllerClass(): string
    {
        return $this->controllerClass;
    }

    public function controllerMethod(): string
    {
        return $this->controllerMethod;
    }

    public function parameters(): RouteParameters
    {
        return $this->parameters;
    }

    public function middlewares(): Middlewares
    {
        return $this->middlewares;
    }

    // this has to go to the matcher
    private function routeRegexFromUriString(
        string $uri
    ): string {
        $regex = str_replace('/', '\/', $uri);

        $matches = [];
        preg_match_all('#(\{([\w\?]+)\})#', $regex, $matches, PREG_SET_ORDER);

        foreach ($matches as [$_, $var, $name]) {
            if (stristr($name, '?', -1)) {
                $name = substr($name, 0, strlen($name) - 1);

                $regex = str_replace('/' . $var, "/?(?P<{$name}>[\^\$#\!\*%\s\w@.-]+)?", $regex);
            } else {
                $regex = str_replace($var, "(?P<{$name}>[\^\$#\!\*%\s\w@.-]+)", $regex);
            }
        }

        return "/^{$regex}\/?$/";
    }
}