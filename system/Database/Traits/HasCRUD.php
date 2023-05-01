<?php

namespace System\Database\Traits;

use System\Database\DBConnction\DBConnction;

trait HasCRUD
{
    protected function createMethod($value)
    {
        $values = $this->arrayToCastEncodeValue($value);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function updateMethod($value)
    {
        $values = $this->arrayToCastEncodeValue($value);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function deleteMethod($id = null)
    {
        $object = $this;
        $this->restQuery();
        if ($id) {
            $object = $this->findMethod($id);
            $this->restQuery();
        }
        $object->setSql("DELETE FROM " . $object->getTabelName());
        $object->setWhere("AND", $this->getAttributName($this->primaryKey . "= ?"));
        $object->addValue($object->{$object->primaryKey});
        return $object->execueQuery();
    }

    protected function allMethod()
    {
        $this->setSql("SELECT * FROM " . $this->getTabelName());
        $statement = $this->execueQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObject($data);
            return $this->collection;
        } else {
            return [];
        }
    }

    protected function findMethod($id)
    {

        $this->setSql("SELECT * FROM " . $this->getTabelName() . " WHERE " . $this->getAttributName($this->primary));
        $this->setWhere("AND", $this->getAttributName($this->primaryKey) . " = ?");
        $this->addValue($this->primaryKey, $id);
        $statement = $this->execueQuery();
        $data = $statement->fetch();
        $this->setAllowedMethod(['update', 'delete', 'save']);
        if ($data) {
            return $this->arrayToAttributes($data);
        } else {
            return null;
        }

    }

    protected function whereMethod($atribute, $firstValue, $secondValue = null)
    {
        if ($secondValue === null) {
            $conditon = $this->getAttributName($atribute) . " = ?";
            $this->addValue($atribute, $firstValue);
        } else {
            $conditon = $this->getAttributName($atribute) . ' ' . $firstValue . '?';
            $this->addValue($atribute, $secondValue);
        }
        $oprator = "AND";
        $this->setWhere($oprator, $conditon);
        $this->setAllowedMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function whereOrMethod($atribute, $firstValue, $secondValue = null)
    {
        if ($secondValue === null) {
            $conditon = $this->getAttributName($atribute) . " = ?";
            $this->addValue($atribute, $firstValue);
        } else {
            $conditon = $this->getAttributName($atribute) . ' ' . $firstValue . '?';
            $this->addValue($atribute, $secondValue);
        }
        $oprator = "OR";
        $this->setWhere($oprator, $conditon);
        $this->setAllowedMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function whereNullMethod($atribute)
    {

        $conditon = $this->getAttributName($atribute) . " = IS NULL ";
        $oprator = "AND";
        $this->setWhere($oprator, $conditon);
        $this->setAllowedMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function whereNotNullMethod($atribute)
    {

        $conditon = $this->getAttributName($atribute) . " = IS NOT NULL ";
        $oprator = "AND";
        $this->setWhere($oprator, $conditon);
        $this->setAllowedMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function whereInMethod($atribute, array $values)
    {
        if (is_array($values)) {
            $valuesArray = [];
            foreach ($values as $value) {
                $this->addValue($atribute, $value);
                array_push($valuesArray, "?");
            }
            $conditions = $this->getAttributName($atribute) . ' IN (' . implode(',', $valuesArray) . ')';
            $oprator = "AND";
            $this->setWhere($oprator, $conditon);
            $this->setAllowedMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
            return $this;
        }

    }

    protected function orderByMethod($attribute, $expression)
    {
        $this->setOrderBy($this->getAttributName($attribute), $expression);
        $this->setAllowedMethod(['limit', 'orderBy', 'get', 'paginate']);
        return $this;
    }

    protected function limitMethod($form, $number)
    {
        $this->setLimit($from, $number);
        $this->setAllowedMethod(['limit', 'get', 'paginate']);
        return $this;
    }

    protected function getMethod($array = [])
    {
        if ($this->sql == '') {
            if (empty($array)) {
                $fildes = $this->getTabelName() . '.*';
            } else {
                foreach ($array as $key => $filed) {
                    $array[$key] = $this->getAttributName($filed);
                }
                $fildes = implode(',', $array);
            }
            $this->setSql("SELECT " . $fildes . " FROM " . $this->getTableName());

        }

        $statement = $this->execueQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObject($data);
            return $this->collection;
        } else {
            return [];
        }

    }

    protected function paginateMethod($perPage)
    {
        $totalRow = $this->getCount();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalPages = ceil($totalRow / $perPage);
        $currentPage = min($currentPage, $totalPages);
        $currentPage = max($currentPage, $totalPages);
        $currentRow = ($currentPage - 1) * $perPage;
        $this->setLimit($currentRow, $perPage);
        if ($this->sql == '') {
            $this->setSql("SELECT " . $this->getTableName() . ".*" . " FROM" . $this->getTableName());
        }
        $statement = $this->execueQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObject($data);
            return $this->collection;
        } else {
            return [];
        }

    }

    protected
    function saveMethod()
    {
        $fillString = $this->fill();
        if (!isset($this->{$this->primaryKey})) {
            $this->setSql("INSERT INTO " . $this->getTabelName() . " SET $fillString  , " . $this->getAttributName($this->createdAt) . "= Now()");
        } else {
            $this->setSql("UPDATE " . $this->getTabelName() . " SET $fillString  , " . $this->getAttributName($this->updatedAt) . "= Now()");
            $this->setWhere("AND", $this->getAttributName($this->primaryKey) . "= ?");
            $this->addValue($this->primaryKey, $this->{$this->primaryKey});
        }
        $this->execueQuery();
        $this->restQuery();

        if (!isset($this->{$this->primaryKey})) {
            $boject = $this->findMethod(DBConnction::newInsertId());
            $defaultVars = get_class_vars(get_called_class());
            $allVars = get_object_vars($boject);
            $diffrentVsars = array_diff(arrray_keys($defaultVars), array_keys($allVars));
            foreach ($diffrentVsars as $attribute) {
                $this->inCastsAttributes($attribute) == true ?
                    $this->registerAttribue($this, $attribute, $this->castEncodeValue($attribute, $boject->$attribute)) :
                    $this->registerAttribue($this, $attribute, $boject->$attribute);
            }

        }
        $this->restQuery();
        $this->setAllowedMethod(['update', 'delete', 'find']);
        return $this;


    }

    protected
    function fill()
    {
        $fillArray = [];
        foreach ($this->fillable as $attribute) {
            if (isset($this->$attribute)) {
                array_push($fillArray, $this->getAttributeName($attribute) . " = ?");
                $this->inCastsAttributes($attribute) == true ?
                    $this->addValue($attribute, $this->castEncodeValue($attribute, $this->$attribute)) :
                    $this->addValue($attribute, $this->$attribute);
            }
        }
        $fillString = implode(",", $fillArray);
        return $fillString;
    }

}