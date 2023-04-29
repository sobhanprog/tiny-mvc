<?php

namespace System\Database\Traits;

trait HasAttributes
{
    private function registerAttribue($object, string $attribute, $value)
    {
        $this->inCastsAttributes($attribute) == true ?
            $object->$attribute = $this->castDecodeValue($attribute, $value) :
            $object->$attribute = $value;
    }

    protected function arrayToAttribute(array $array, $object = null)
    {
        if (!$object) {
            $className = get_called_class();
            $object = new $className;
        }
        foreach ($array as $attribut => $value) {
            if ($this->inHiddenAttributes($attribut))
                continue;
            $this->registerAttribue($object, $attribut, $value);
        }
        return $object;
    }

    protected function arrayToObject(array $array)
    {
        $collection = [];
        foreach ($array as $value) {
            $object = $this->arrayToAttribute($value);
            array_push($collection, $object);
        }
        $this->collection = $collection;

    }

    private function inHiddenAttributes($attribute)
    {
        return in_array($attribute, $this->hidden);
    }

    private function inCastsAttributes($attribute)
    {
        return in_array($attribute, array_keys($this->casts));
    }

    private function castDecodeValue($attributeKey, $value)
    {
        if ($this->casts[$attributeKey] == 'array' or $this->casts[$attributeKey] == 'object') {
            return unserialize($value);
        }

        return $value;
    }

    private function castEncodeValue($attributeKey, $value)
    {
        if ($this->casts[$attributeKey] == 'array' or $this->casts[$attributeKey] == 'object') {
            return serialize($value);
        }

        return $value;
    }

    private function arrayToCastEncodeValue($values)
    {
        $newarray = [];
        foreach ($values as $key => $value) {
            $this->inCastsAttributes($key) == true ? $newarray[$key] = $this->castEncodeValue($key, $value) : $newarray[$key] = $value;
        }
        return $newarray;
    }
}