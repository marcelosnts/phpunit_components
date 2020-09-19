<?php

namespace CodeTests\Router;

use PHPUnit\Framework\TestCase;

use Code\Router\Router;

class RouterTest extends TestCase{
    public function testRouterSetOfRoutes(){
        $_SERVER['REQUEST_URI'] = '/users';    

        $router = new Router();

        $router->addRoute('/users', function(){
            return 'Primeira Rota!';
        });

        $result = $router->run();

        $this->assertEquals('Primeira Rota!', $result);
    }

    public function testValidateRouteNotFound(){ 
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Route Not Found');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();
        
        $router->run();
    }

    public function testRouteWithAControllerAssociated(){
        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();
        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductsController@index');

        $result = $router->run();

        $this->assertEquals('Products Controller', $result);
    }

    public function testWrongSecondParameterToAController(){
        $this->expectException('\InvalidArgumentException');
        $this->expectExceptionMessage('Format does not expected');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();
        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductsController');

        $result = $router->run();
    }

    public function testThrowExceptionWhenAMethodDoesNotExists(){
           $this->expectException('\Exception');
        $this->expectExceptionMessage('Method does not exists');

        $_SERVER['REQUEST_URI'] = '/products';

        $router = new Router();
        $router->addRoute('/products', '\\CodeTests\\Controller\\ProductsController@getProduct');

        $result = $router->run();
    }

    public function testRouteWithDynamicParameters(){
        $_SERVER['REQUEST_URI'] = '/users/10';    

        $router = new Router();

        $router->addRoute('/users/{id}', function(){
            return 'User 10 data';
        });

        $result = $router->run();

        $this->assertEquals('User 10 data', $result); 
    }
}