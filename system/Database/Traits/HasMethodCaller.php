<?php

namespace System\Database\Traits;

trait HasMethodCaller
{
    private $allMethods = ['create', 'update', 'delete', 'find', 'all', 'save', 'where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate'];
    private $allowedMethods = ['create', 'update', 'delete', 'find', 'all', 'save', 'where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate'];

    public function __call($method, $args)
    {
        return $this->methodCaller($this, $method, $args);
    }

    public function __callStatic($method, $args)
    {
        $ClassName = get_called_class();
        $instance = new $ClassName;
        return $this->methodCaller($instance, $method, $args);
    }

    private function methodCaller($object,$method, $args)
    {
        $suffix = 'Method';
        $methodName = $method . $suffix;
        if (in_array($method, $this->allMethods)) {
            return call_user_func_array(array($object, $methodName), $args);
        }
    }

    protected function setAllowedMethod(array $array)
    {
        $this->allowedMethods = $array;
    }

}