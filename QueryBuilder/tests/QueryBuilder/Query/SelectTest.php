<?php

namespace CodeTests\QueryBuilder\Query;

use PHPUnit\Framework\TestCase;

use Code\QueryBuilder\Query\Select;

class SelectTest extends TestCase {
    protected $select;

    protected function assertPreConditions() : void {
        $this->assertTrue(class_exists(Select::class));
    }

    protected function setUp() : void {
        $this->select = new Select('products');
    }

    public function testIfQueryBaseIsGeneratedWithSuccess(){
        $query = $this->select->getSql();

        $this->assertEquals('SELECT * FROM products', $query);
    }

    public function testIfQueryIsGeneratedWithWhereConditions(){
        $query = $this->select->where('name', '=', ':name');

        $this->assertEquals('SELECT * FROM products WHERE name = :name', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddMoreConditionsIntoWhere(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->where('price', '>=', ':price');

        $this->assertEquals('SELECT * FROM products WHERE name = :name AND price >= :price', $query->getSql());
    }

    public function testIfQueryIsGeneratedWithOrderByCondition(){
        $query = $this->select->orderBy('name', 'DESC');

        $this->assertEquals('SELECT * FROM products ORDER BY name DESC', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddOrderByInWhereConditions(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->orderBy('name');

        $this->assertEquals('SELECT * FROM products WHERE name = :name ORDER BY name ASC', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddOrderByInMultipleWhereConditions(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->where('price', '=', ':price', 'OR')
                                ->orderBy('name');

        $this->assertEquals('SELECT * FROM products WHERE name = :name OR price = :price ORDER BY name ASC', $query->getSql());
    }

    public function testIfQueryIsGeneratedWithLimitCondition(){
        $query = $this->select->limit(0, 15);

        $this->assertEquals('SELECT * FROM products LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddLimitInWhereConditions(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->limit(0, 15);

        $this->assertEquals('SELECT * FROM products WHERE name = :name LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddLimitInMultipleWhereConditions(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->where('price', '=', ':price', 'OR')
                                ->limit(0, 15);

        $this->assertEquals('SELECT * FROM products WHERE name = :name OR price = :price LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddLimitInSelectWithOrderBy(){
        $query = $this->select  ->orderBy('name', 'DESC')
                                ->limit(0, 15);

        $this->assertEquals('SELECT * FROM products ORDER BY name DESC LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddLimitInSelectWithOrderByWithWhereCondition(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->orderBy('name', 'DESC')
                                ->limit(0, 15);

        $this->assertEquals('SELECT * FROM products WHERE name = :name ORDER BY name DESC LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryBuilderAllowAddLimitInSelectWithOrderByWithMultipleWhereConditions(){
        $query = $this->select  ->where('name', '=', ':name')
                                ->where('price', '=', ':price', 'OR')
                                ->orderBy('name', 'DESC')
                                ->limit(0, 15);

        $this->assertEquals('SELECT * FROM products WHERE name = :name OR price = :price ORDER BY name DESC LIMIT 0, 15', $query->getSql());
    }

    public function testIfQueryIsGeneratedWithJoinsConditions(){
        $query = $this->select->join('INNER JOIN', 'colors', 'colors.product_id', '=', 'products.id');

        $this->assertEquals('SELECT * FROM products INNER JOIN colors ON colors.product_id = products.id', $query->getSql());
    }
    
    public function testIfQueryBuilderAllowAddMultipleJoinsConditions(){
        $query = $this->select  ->join('INNER JOIN', 'colors', 'colors.product_id', '=', 'products.id')
                                ->join('INNER JOIN', 'colors', 'colors.product_id', '=', 'products.id', 'AND')
                                ->join('LEFT JOIN', 'categories', 'categories.id', '=', 'products.category_id')
                                ->where('id', '=', ':id')
                                ->select('name', 'price', 'created_at');

        $this->assertEquals(
            'SELECT name, price, created_at FROM products'
            . ' INNER JOIN colors ON colors.product_id = products.id'
            . ' AND colors.product_id = products.id'
            . ' LEFT JOIN categories ON categories.id = products.category_id'
            . ' WHERE id = :id', 
            $query->getSql()
        );
    }

    public function testQueryWithSelectedFieldsIsSuccessfullyGenerated(){
        $query = $this->select->select('name', 'price');

        $this->assertEquals('SELECT name, price FROM products', $query->getSql());
    }
}
