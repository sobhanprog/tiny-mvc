<?php

namespace System\Database\Traits;

use System\Database\DBConnction\DBConnction;

trait HasQueryBuilder
{
    private $sql = "";
    protected $where = [];
    private $orderby = [];
    private $limit = [];
    private $values = [];
    private $bindValues = [];

    protected function setSql($query)
    {
        $this->sql = $query;
    }

    protected function getSql()
    {
        return $this->sql;
    }

    protected function resetSql()
    {
        $this->sql = "";
    }

    protected function setWhere($opration, $condition)
    {
        $array = ['opration' => $opration, 'conditon' => $condition];
        array_push($this->where, $array);
    }

    protected function resetWhere()
    {
        $this->where = [];
    }

    protected function setOrderBy($name, $expraition)
    {
        array_push($this->orderby, $name . ' ' . $expraition);
    }

    protected function resetOrderBy()
    {
        $this->orderby = [];
    }

    protected function setLimit($from, $number)
    {
        $this->limit['from'] = (int)$from;
        $this->limit['number'] = (int)$number;
    }

    protected function resetLimit()
    {
        unset($this->limit['from']);
        unset($this->limit['number']);
    }
    protected function addValue($attributes,$value)
    {
        $this->values[$attributes] = $value;
        array_push($this->bindValues,$value);
    }

    protected function removeValues()
    {
        $this->values = [];
        $this->bindValues = [];
    }
    protected function restQuery()
    {
        $this->resetSql();
        $this->resetWhere();
        $this->resetOrderBy();
        $this->resetLimit();
        $this->removeValues();
    }


}