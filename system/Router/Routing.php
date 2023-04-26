<?php

namespace System\Router;

use ReflectionMethod;

class Routing
{
    private $currentroute;
    private $method_fild;
    private $routes;
    private $values = [];

    public function __construct()
    {
        global $routes;
        $this->currentroute = explode("/", CURRENT_ROUTE); // compire and mach whit array routes
        $this->routes = $routes; // all route for compaire and maches
        $this->method_fild = $this->methodFild(); // this method for say that this route requset what type method
    }

    public function methodFild(): string
    {
        $method_Fild = strtolower($_SERVER['REQUEST_METHOD']);
        if ($method_Fild == "post") {
            if (isset($_POST['_method'])) {
                if ($_POST['_method'] == 'put') {
                    $method_Fild = 'put';
                } elseif ($_POST['_method'] == 'delete') {
                    $method_Fild = 'delete';
                }
            }
        }
        return $method_Fild;

    }

    public
    function match()
    {
        $resevedRoute = $this->routes[$this->method_fild];
        foreach ($resevedRoute as $resverRouteElement) {
            if ($this->compaire($resverRouteElement['url'])) {
                return ['class' => $resverRouteElement['class'], 'method' => $resverRouteElement['method']];
            } else {
                $this->values = [];
            }
        }
        return [];
    }

    public
    function compaire($reservedroutUrl)
    {
//        if (trim($reservedroutUrl, "/") == '') {
//            return trim($this->currentroute[0], "/") == " " ? true : false;
//        }

        if (trim($this->currentroute[0], "/") === " ") {
            return trim($reservedroutUrl, "/") === '' ? true : false;
        }

        $arrayreserurlRoute = explode('/', $reservedroutUrl);

        if (sizeof($arrayreserurlRoute) != sizeof($this->currentroute)) {
            return false;
        }

        foreach ($this->currentroute as $key => $currentRouteElment) {
            $reserveRouteElement = $arrayreserurlRoute[$key];
            if (substr($reserveRouteElement, 0, 1) == "{" and substr($reserveRouteElement, -1) == "}") {
                array_push($this->values, $currentRouteElment);
            } elseif ($reserveRouteElement != $currentRouteElment) {
                return false;
            }
        }
        return true;

    }

    public
    function error404()
    {
        http_response_code('404');
        include __DIR__ . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "index.php";
        exit();
    }

    public
    function run()
    {
        $match = $this->match();

        if (empty($match)) {

            $this->error404();
        }
        $method = $match['method'];
        $class = $match['class'];


        $patchClass = str_replace("\\", "/", $class);
        $path = BASE_DIRE ."/".$patchClass.".php";
        if (!file_exists($path)) {
            $this->error404();
        }
        $useClass = $class;

        $object = new $useClass();
        if (method_exists($object, $method)) {
            $reflection = new ReflectionMethod($useClass, $method);
            $prameterCount = $reflection->getNumberOfParameters();
            if ($prameterCount <= count($this->values)) {
                call_user_func_array(array($object, $method), $this->values);
            }
        } else {
            $this->error404();
        }


    }


}