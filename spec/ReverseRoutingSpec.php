<?php namespace spec\Monolith\WebRouting;

use Monolith\WebRouting\CompiledRoute;
use Monolith\WebRouting\CompiledRoutes;
use Monolith\WebRouting\Middlewares;
use Monolith\WebRouting\ReverseRouting;
use Monolith\WebRouting\CanNotMatchReverseRoutingRequiredMatchers;
use Monolith\WebRouting\RouteParameters;
use PhpSpec\ObjectBehavior;

class ReverseRoutingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ReverseRouting::class);
    }

    function it_can_reverse_route_based_on_controller()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/article', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );

        $url = $this->route($routes, ControllerStub::class, [123]);

        $url->shouldBe('/article');
    }

    function it_can_produce_routes_with_an_argument()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/article/{id}', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );

        $url = $this->route($routes, ControllerStub::class, [123]);

        $url->shouldBe('/article/123');
    }

    function it_can_produce_routes_with_many_arguments()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/article/{id}/{bid}/{cid}', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );

        $url = $this->route($routes, ControllerStub::class, [123, 234, 345]);

        $url->shouldBe('/article/123/234/345');
    }

    function it_throws_an_exception_when_argument_count_doesnt_match()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/article/{id}/{bid}/{cid}', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );

        $this->shouldThrow(CanNotMatchReverseRoutingRequiredMatchers::class)->during('route', [$routes, ControllerStub::class, [123, 234]]);
    }

    function it_can_produce_routes_with_undefined_values_for_optional_segments()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/article/{id?}/{bob?}', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );
        
        $url = $this->route($routes, ControllerStub::class, []);

        $url->shouldBe('/article');
    }

    function it_can_ensure_that_a_valid_returned_route_is_never_an_empty_string()
    {
        $routes = CompiledRoutes::list(
            new CompiledRoute('get', '/', ControllerStub::class, 'index', new RouteParameters, new Middlewares)
        );

        $url = $this->route($routes, ControllerStub::class, []);

        $url->shouldBe('/');
    }
}