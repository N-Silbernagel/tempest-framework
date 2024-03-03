<?php

declare(strict_types=1);

namespace Tempest\Http;

use ReflectionMethod;

final class RouteConfig
{
    public function __construct(
        /** @var array<string, array<string, \Tempest\Http\Route>> */
        public array  $routes = [],
        public string $regex = '#^(?)$#x'
    ) {
    }

    public function addRoute(ReflectionMethod $handler, Route $route): self
    {
        $route->setHandler($handler);

        $this->routes[$route->method->value][$route->uri] = $route;

        if ($route->isDynamic) {
            $index = count($this->routes[$route->method->value]) - 1;
            $this->regex = substr($this->regex, 0, -4);
            $this->regex .= "|" . $route->matchingRegex . " (*" . GenericRouter::MARK_TOKEN . ":$index)";
            $this->regex .= ')$#x';
        }


        return $this;
    }
}
