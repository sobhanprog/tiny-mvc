<?php

namespace System\Router\Web;

class Route
{
    public static function get($url, array $arg, $name = null)
    {
        $class = $arg[0];
        $method = $arg[1];
        global $routes;
        array_push($routes['get'], array('url' => trim($url,"/"), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function post($url, array $arg, $name = null)
    {
        $class = $arg[0];
        $method = $arg[1];
        global $routes;
        array_push($routes['post'], array('url' => trim($url,"/"), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function put($url, array $arg, $name = null)
    {
        $class = $arg[0];
        $method = $arg[1];
        global $routes;
        array_push($routes['put'], array('url' => trim($url,"/"), 'class' => $class, 'method' => $method, 'name' => $name));
    }

    public static function delete($url, array $arg, $name = null)
    {
        $class = $arg[0];
        $method = $arg[1];
        global $routes;
        array_push($routes['delete'], array('url' => trim($url,"/"), 'class' => $class, 'method' => $method, 'name' => $name));
    }
}
