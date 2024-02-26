<?php

declare(strict_types=1);


namespace  Plutuss\CodeMaster\Routing;

use Plutuss\CodeMaster\Auth\AuthInterface;
use Plutuss\CodeMaster\Config\ConfigInterface;
use Plutuss\CodeMaster\Http\Middleware\AbstractMiddleware;
use Plutuss\CodeMaster\Http\Redirect\RedirectInterface;
use Plutuss\CodeMaster\Http\Request\RequestInterface;
use Plutuss\CodeMaster\Session\SessionInterface;
use Plutuss\CodeMaster\View\ViewInterface;

class Router implements RouterInterface
{

    private string $baseUrl = '/';
    private string $requestUrl = '/';
    private array $paramMap = [];
    private array $paramRequestMap = [];
    private RouteConfigurationInterface $routeConfiguration;

    public function __construct(
        private readonly ViewInterface     $view,
        private readonly RequestInterface  $request,
        private readonly RedirectInterface $redirect,
        private readonly SessionInterface  $session,
        private readonly ConfigInterface   $config,
        private readonly AuthInterface     $auth,

    )
    {
    }

    public function dispatch(): void
    {
        $this->initRoutes();
    }

    private function notFount()
    {
        echo '404| Not Fount';
        exit();
    }

    private function initRoutes(): void
    {

        $routes = Route::getRoutes($this->request->method());

        foreach ($routes as $routeConfiguration) {
            $this->routeConfiguration = $routeConfiguration;
            $this->process();
        }
        $this->notFount();

    }

    private function process(): void
    {
//        $this->issetPrefixRouter();
        $this->saveRequestUrl();
        $this->runMiddleware();
        $this->setParamMap();
        $this->makeRegexRequest();
        $this->run();
    }


    private function saveRequestUrl()
    {
        if ($this->request->url() !== $this->baseUrl) {
            $this->requestUrl = $this->request->url();

            $this->routeConfiguration->route = $this->clean(
                $this->routeConfiguration->route
            );
            $this->requestUrl = $this->clean(
                $this->requestUrl
            );
        }

    }


    private function clean(string $str)
    {
        $url = preg_replace('/(^\/)|(\/$)/', '', $str);
        $url = explode('?', $url);
        return $url[0];
    }


    private function setParamMap(): void
    {
        $routeArray = explode('/', $this->routeConfiguration->route);

        foreach ($routeArray as $key => $param) {
            if (preg_match('/{.*}/', $param)) {

                $this->paramMap[$key] = preg_replace('/(^{)|(}$)/', '', $param);
            }
        }
    }


    private function makeRegexRequest(): void
    {
        $requestUrlArray = explode('/', $this->requestUrl);

        foreach ($this->paramMap as $key => $param) {
            if (!isset($requestUrlArray[$key])) {
                return;
            }

            $this->paramRequestMap[$param] = (int)$requestUrlArray[$key];
            $requestUrlArray[$key] = '{.*}';
        }
        $this->requestUrl = implode('/', $requestUrlArray);


        $this->prepareRegex();
    }

    private function prepareRegex(): void
    {
        $this->requestUrl = str_replace('/', '\/', $this->requestUrl);
    }

    private function run(): void
    {

        if (preg_match(
            "/$this->requestUrl/", $this->routeConfiguration->route
        )
        ) {
            $this->render();
        }

    }

    private function render()
    {
        $controller = $this->routeConfiguration->controller;
        $action = $this->routeConfiguration->action;

        if (!empty($action)) {

            /**
             *  $var Controller $controller
             */
            $controller = new $controller();

            call_user_func([$controller, 'setView'], $this->view);
            call_user_func([$controller, 'setRequest'], $this->request);
            call_user_func([$controller, 'setRedirect'], $this->redirect);
            call_user_func([$controller, 'setSession'], $this->session);
            call_user_func([$controller, 'setConfig'], $this->config);
//            call_user_func([$controller, 'setDb'], $this->database);
            call_user_func([$controller, 'setAuth'], $this->auth);
            call_user_func([$controller, $action], $this->paramRequestMap);
        } else {
            call_user_func($controller);
        }

        die();
    }

    private function runMiddleware(): void
    {

        if ($this->routeConfiguration->hasMiddleware()) {

            if (is_array($this->routeConfiguration->middleware)) {
                foreach ($this->routeConfiguration->middleware as $middleware) {
                    $this->middlewareHandle($middleware);
                }
            } else {
                $this->middlewareHandle($this->routeConfiguration->middleware);
            }
        }
    }

    private function middlewareHandle($middleware): void
    {
        /**
         * @var AbstractMiddleware $middleware
         */

        $middleware = new  $middleware(
            $this->request,
            $this->redirect,
            $this->auth
        );
        $middleware->handle();
    }


    public static function getRouteFromName($route_name): \Exception|string
    {
        self::checkVariable($route_name, 'Route not found');

        $routesArray = Route::getRoutes(null, false);

        foreach ($routesArray as $object) {
            if ($object->name === $route_name) {
                return $object->route;
            }
        }
        return new \Exception('Route not found');
    }

    public static function addPrefixToRoute(): \Exception|string
    {
        $routesArray = Route::getRoutes(null, false);

        foreach ($routesArray as $object) {
            if (!empty($object->prefix)) {
                $prefix = $object->prefix;

                if ($_SERVER['REQUEST_URI'] === $object->route) {
                    throw new \Exception("Route  $object->route not found");
                }
                return "/{$prefix}{$object->route}";
            }
        }
        return new \Exception('Prefix not found');
    }


    private static function checkVariable($var, $msg): ?\Exception
    {
        if (!isset($var) && $var == '') {
            return new \Exception($msg);
        }
        return null;
    }



}