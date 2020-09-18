<?php

namespace Code\QueryBuilder;

class Executor {
    private $connection;
    private $query;
    private $params = [];

    public function __construct(\PDO $connection, QueryInterface $query = null){
        $this->connection = $connection;
        $this->query = $query;
    }

    public function setQuery($query){
        $this->query = $query;
    }

    public function setParam($bind, $value){
        $this->params[] = ['bind' => $bind, 'value' => $value];
        return $this;
    }

    public function execute(){
        $sql = $this->query->getSql();
        try{
            $proccess = $this->connection->prepare($sql);
        } catch(\PDOException $e){
            die($e);
        }
        
        foreach($this->params as $param){
            $type = gettype($param['value']) == 'string' ? \PDO::PARAM_STR : \PDO::PARAM_INT;
    
            $proccess->bindValue($param['bind'], $param['value'], $type);
        }

        $proccess->execute();
        return $this->connection->lastInsertId();
    }
}