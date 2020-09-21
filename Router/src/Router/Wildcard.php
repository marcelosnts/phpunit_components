<?php

namespace Code\Router;

class Wildcard {
    private $parameters = [];

    public function resolveRoute($uri, &$routeCollection){
        $keysRouteCollection = array_keys($routeCollection);
        $routeWithParameters = [];
        foreach($keysRouteCollection as $route){
            if(preg_match('/{(\w+?)\}/', $route)){
                $routeWithParameters[] = $route;
            }
        }

        foreach($routeWithParameters as $route){
            $routeWithoutParameter = preg_replace('/\/{(\w+?)\}/', '', $route);
            $uriWithoutParameter = preg_replace('/\/[0-9]+$/', '', $uri);

            if($routeWithoutParameter === $uriWithoutParameter){
                $routeCollection[$uri] = $routeCollection[$route];
                $this->parameters = $this->resolveParameter($uri);
            }
        }
        // if($uri == '/users/10'){
        //     $routeCollection[$uri] = function(){
        //         return 'User 10 data';
        //     };
        // }
    }

    public function getParameters(){
        return $this->parameters;
    }

    private function resolveParameter($uri){
        $matches = [];
        preg_match('/[0-9]+$/', $uri, $matches);

        return $matches;
    }
}