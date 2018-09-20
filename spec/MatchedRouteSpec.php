<?php namespace spec\Monolith\WebRouting;

use Monolith\WebRouting\CompiledRoute;
use Monolith\WebRouting\MatchedRoute;
use PhpSpec\ObjectBehavior;

class MatchedRouteSpec extends ObjectBehavior {

    function let() {
        $compiledRoute = new CompiledRoute('httpmethod', 'uri', 'controllerclass', 'controllermethod');

        $this->beConstructedWith($compiledRoute);
    }

    function it_is_initializable() {

        $this->shouldHaveType(MatchedRoute::class);

        $this->httpMethod()->shouldBe('httpmethod');
        $this->uri()->shouldBe('uri');
        $this->controllerClass()->shouldBe('controllerclass');
        $this->controllerMethod()->shouldBe('controllermethod');
    }
}