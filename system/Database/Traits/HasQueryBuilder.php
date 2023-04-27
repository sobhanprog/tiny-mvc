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
        array_push($this->orderby, $this->getAttributeName($name) . ' ' . $expraition);
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

    protected function addValue($attributes, $value)
    {
        $this->values[$attributes] = $value;
        array_push($this->bindValues, $value);
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

    protected function execueQuery()
    {
        $query = "";
        $query .= $this->sql;
        if (!empty($this->where)) {
            $wherestring = "";
            foreach ($this->where as $where) {
                $wherestring == "" ? $wherestring .= $where['conditon'] : $wherestring .= ' ' . $where['opration'] . '' . $where['conditon'];
            }
            $query .= " WHERE " . $wherestring;
        }
        if (!empty($this->orderby)) {
            $query .= ' ORDER BY' . implode(", ", $this->orderby);

        }
        if (!empty($this->limit)) {
            $query .= " LIMIT " . $this->limit['from'] . "," . $this->limit['number'] . ' ';
        }


        echo $query . "<hr />";

        $pdoInstance = DBConnction::getDBConctionInstance();
        $statmant = $pdoInstance->prepare($query);
        if (empty($this->bindValues) and empty($this->values)) {
            $statmant->execute();
        } else {
            sizeof($this->bindValues) > sizeof($this->values) ?
                $statmant->execute($this->bindValues) : $statmant->execute(array_values($this->values));
        }


        return $statmant;
    }

    protected function getCount()
    {
        $query = "";
        $query .= "SELECT COUNT(".$this->getTabelName()."*) FROM  " . $this->getTabelName();
        if (!empty($this->where)) {
            $wherestring = "";
            foreach ($this->where as $where) {
                $wherestring == "" ? $wherestring .= $where['conditon'] : $wherestring .= ' ' . $where['opration'] . '' . $where['conditon'];
            }
            $query .= " WHERE " . $wherestring;
        }
        $pdoInstance = DBConnction::getDBConctionInstance();
        $statmant = $pdoInstance->prepare($query);

        if (empty($this->bindValues) and empty($this->values)) {
            $statmant->execute();
        } else {
            sizeof($this->bindValues) > sizeof($this->values) ?
                $statmant->execute($this->bindValues) : $statmant->execute(array_values($this->values));
        }


        return $statmant->fetchColumn();
    }

    protected function getTabelName()
    {
        return '`' . $this->table . '`';
    }

    protected function getAttributeName($attribute)
    {
        return '`' . $this->table . '`.`' . $attribute . '`';
    }

}