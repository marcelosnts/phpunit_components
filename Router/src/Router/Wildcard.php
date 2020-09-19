<?php

namespace Code\Router;

class Wildcard {
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
            }
        }
        // if($uri == '/users/10'){
        //     $routeCollection[$uri] = function(){
        //         return 'User 10 data';
        //     };
        // }
    }
}