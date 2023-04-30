<?php

namespace System\Database\Traits;

use System\Database\DBConnction\DBConnction;

trait HasCRUD
{

    protected function saveMethod()
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

    protected function fill()
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