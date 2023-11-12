<?php

declare(strict_types=1);

namespace  Plutuss\SauceCore\Routing;

class RouteConfiguration implements RouteConfigurationInterface
{
    public function __construct(
        public string       $route,
        public              $controller,
        public string|null  $action = null,
        public string|array $middleware = '',
        public string       $name = '',
        public ?string      $prefix = null
    )
    {
    }

    public function hasMiddleware(): bool
    {
        return !empty($this->middleware);
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function name($name): static
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param string|array $middleware
     *
     * @return $this
     */
    public function middleware(string|array $middleware): static
    {
        $this->middleware = $middleware;
        return $this;
    }


    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

}