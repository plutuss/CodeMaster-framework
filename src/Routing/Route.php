<?php


namespace Plutuss\CodeMaster\Routing;

class Route
{
    private static array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct(
        public readonly string $url,
        public readonly string $method,
        public readonly mixed  $action,

//        public readonly array  $middleware = [],
    )
    {
    }


    /**
     * @param string $url
     * @param mixed $action
     * @return RouteConfiguration
     */
    public static function get(string $url, mixed $action): RouteConfiguration
    {
        return self::setRouteData($url, $action, 'GET');
    }

    /**
     * @param string $url
     * @param mixed $action
     * @return RouteConfiguration
     */
    public static function post(string $url, mixed $action): RouteConfiguration
    {
        return self::setRouteData($url, $action, 'POST');
    }

    /**
     * @param string $url
     * @param mixed $action
     * @param string $method
     * @return RouteConfiguration
     */
    private static function setRouteData(string $url, mixed $action, string $method = 'GET'): RouteConfiguration
    {
        if (is_array($action)) {
            [$controller, $action] = $action;
            $routeConfiguration = new RouteConfiguration(
                $url, $controller, $action
            );

            self::$routes[$method][] = $routeConfiguration;

            return $routeConfiguration;
        }
        return self::$routes[$method][] = new RouteConfiguration(
            $url, $action
        );
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getAction(): mixed
    {
        return $this->action;
    }


    /**
     * @param string|null $method
     * @param bool $withKey
     * @return array|array[]
     */
    public static function getRoutes(string $method = null, bool $withKey = true): array
    {
        if (empty($method)) {
            if ($withKey) {
                return self::$routes;
            }
            return array_merge(
                self::$routes['GET'], self::$routes['POST']
            );

        }
        return self::$routes[$method];
    }

}