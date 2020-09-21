<?php

namespace Code\Router;

use Code\Router\Wildcard;

class Router {
    private $uriServer;
    private $routeCollection = [];
    private $prefix = '';

    private function controllerResolver($route, $parameters = []){
        if(!strpos($route, '@')){
            throw new \InvalidArgumentException('Format does not expected');
        }

        list($controller, $method) = explode('@', $route);

        if(!method_exists(new $controller, $method)){
            throw new \Exception('Method does not exists');
        }

        return call_user_func_array([new $controller, $method], $parameters);
    }

    public function __construct(){
        $this->uriServer = $_SERVER['REQUEST_URI'];
    }

    public function addRoute($uri, $callable){
        $uri = ltrim($uri, '/');
        $prefix = $this->prefix ? '/' . ltrim($this->prefix, '/') : '';

        $this->routeCollection[$prefix . '/' . $uri] = $callable;
    }

    public function prefix($prefix, $routeGroup){
        $this->prefix = $prefix;

        $routeGroup($this);
    }

    public function run(){
        $wildcard = new Wildcard();
        $wildcard->resolveRoute($this->uriServer, $this->routeCollection);

        if(!isset($this->routeCollection[$this->uriServer])){
            throw new \Exception('Route Not Found');
        }

        $route = $this->routeCollection[$this->uriServer];
        if(is_callable($route)){
            $parameters = $wildcard->getParameters();
            if(count($parameters)){
                return $route($parameters[0]);
            }

            return $route();
        }

        if(is_string($route)){
            return $this->controllerResolver($route);
        }
    }
}