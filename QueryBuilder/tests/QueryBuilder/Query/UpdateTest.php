<?php

namespace CodeTests\QueryBuilder\Query;

use PHPUnit\Framework\TestCase;

use Code\QueryBuilder\Query\Update;

class UpdateTest extends TestCase {
    private $update;

    protected function assertPreConditions() : void {
        $this->assertTrue(class_exists(Update::class));
    }

    protected function setUp() : void {
        $this->update = new Update('products', ['name', 'price'], ['id' => 1]);
    }

    public function testIfUpdateQueryHasGeneratedSuccessfully(){
        $sql = "UPDATE products SET name=:name, price=:price WHERE id = 1";

        $this->assertEquals($sql, $this->update->getSql());
    }
}