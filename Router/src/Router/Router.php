<?php

namespace Code\Router;

use Code\Router\Wildcard;

class Router {
    private $uriServer;
    private $routeCollection = [];

    private function controllerResolver($route){
        if(!strpos($route, '@')){
            throw new \InvalidArgumentException('Format does not expected');
        }

        list($controller, $method) = explode('@', $route);

        if(!method_exists(new $controller, $method)){
            throw new \Exception('Method does not exists');
        }

        return call_user_func_array([new $controller, $method], []);
    }

    public function __construct(){
        $this->uriServer = $_SERVER['REQUEST_URI'];
    }

    public function addRoute($uri, $callable){
        $this->routeCollection[$uri] = $callable;
    }

    public function run(){
        (new Wildcard())->resolveRoute($this->uriServer, $this->routeCollection);

        if(!isset($this->routeCollection[$this->uriServer])){
            throw new \Exception('Route Not Found');
        };

        $route = $this->routeCollection[$this->uriServer];
        if(is_callable($route)){
            return $route();
        }

        if(is_string($route)){
            return $this->controllerResolver($route);
        }
    }
}