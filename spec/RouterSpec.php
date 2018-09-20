<?php namespace spec\Monolith\WebRouting;

use Monolith\Collections\Map;
use Monolith\DependencyInjection\Container;
use Monolith\Http\Request;
use Monolith\WebRouting\Route;
use Monolith\WebRouting\RouteCompiler;
use Monolith\WebRouting\RouteDispatcher;
use Monolith\WebRouting\RouteMatcher;
use Monolith\WebRouting\Router;
use Monolith\WebRouting\Routes;
use PhpSpec\ObjectBehavior;
use spec\Monolith\DependencyInjection\ControllerStub;
use spec\Monolith\DependencyInjection\Methods\StubMethod;

class RouterSpec extends ObjectBehavior {

    /** @var RouteCompiler */
    private $compiler;

    function let() {

        $container = new Container;

        $this->compiler = new RouteCompiler;

        $matcher = new RouteMatcher;
        $dispatcher = new RouteDispatcher($container);

        $this->beConstructedWith($this->compiler, $matcher, $dispatcher);
    }

    function it_is_initializable() {

        $this->shouldHaveType(Router::class);
    }

    function it_can_dispatch_a_request() {

        // configure request
        $serverVariables = new Map([
            'REQUEST_URI'    => '/article/1',
            'REQUEST_METHOD' => 'get',
        ]);

        $request = new Request(new Map, new Map, $serverVariables, new Map, new Map, new Map);

        // register method compiler
        $this->compiler->registerMethodCompiler(new StubMethod);

        // compile routes
        $route = new Route('stub', '/article/{id}', ControllerStub::class);

        $this->registerRoutes(Routes::list($route));

        $response = $this->dispatch($request);

        $response->code()->shouldBe('200');
        $response->body()->shouldBe('controller stub response');
    }
}