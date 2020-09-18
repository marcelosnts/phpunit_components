<?php

namespace Code\QueryBuilder\Query;

class Select implements QueryInterface{
    private $query;
    private $where;
    private $orderBy;
    private $limit;
    private $join;

    public function __construct($table){
        $this->query = 'SELECT * FROM ' . $table;
    }

    public function select(...$fields){
        $fields = implode(', ', $fields);

        $this->query = str_replace('*', $fields, $this->query);

        return $this;
    }

    public function where($field, $operator, $bind = null, $concat = 'AND'){
        $bind = is_null($bind) ? ':' . $field : $bind;

        if(empty($this->where)){
            $this->where .= ' WHERE ' . $field . ' ' . $operator . ' ' . $bind;
        } else {
            $this->where .= ' ' . $concat . ' ' . $field . ' ' . $operator . ' ' . $bind;
        }
       
        return $this;
    }

    public function orderBy($field, $order = 'ASC'){
        $this->orderBy .= ' ORDER BY ' . $field . ' ' . $order;

        return $this;
    }

    public function limit($skip, $take){
        $this->limit .= ' LIMIT ' . $skip . ', ' . $take;

        return $this;
    }

    public function join($type, $table, $foreignKey, $operator, $refColumn, $concat = false){

        if(!$concat){
            $this->join .= ' ' . $type . ' ' . $table . ' ON ' . $foreignKey . ' ' . $operator . ' ' . $refColumn;
        } else {
            $this->join .= ' ' . $concat . ' ' . $foreignKey . ' ' . $operator . ' ' . $refColumn;
        }

        return $this;
    }

    public function getSql(){
        return $this->query . $this->join . $this->where . $this->orderBy . $this->limit; 
    }
}