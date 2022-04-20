<?php

namespace App;

use App\Controller\AbstractController;
use App\Controller\ErrorController;
use ReflectionException;
use ReflectionMethod;

class Router
{

    /**
     * Route the request.
     * @return void
     * @throws ReflectionException
     */
    public static function route() {
        $c = self::getParam('c', 'home');
        $action = self::getParam('a');
        $controller = self::controllerExist($c);

        // if error
        if ($controller instanceof ErrorController) {
            $controller->default();
            exit();
        }

        // else
        $action = self::methodExist($controller, $action);

        if($action === null || $action === '') {
            $controller->default();

        } else {

            $parameters = self::argumentsExist($controller, $action);

            if(count($parameters) === 0 ) {

                $controller->$action();

            } else {

                $args = [];

                foreach ($parameters as $value) {
                    if (!isset($_GET[$value['param']])) {
                        $error = new ErrorController();
                        $error->default();
                        exit();
                    }

                    $var = $_GET[$value['param']];
                    settype($var, $value['type']);
                    $args[] = $var;
                }

                $controller->$action(...$args);
            }
        }
    }

    /**
     * Check if the Controller exist, if true return a new one, else return an error controller
     * @param string $controller
     * @return ErrorController|mixed
     **/
    private static function controllerExist(string $controller) {
        $controller = "App\Controller\\" . ucfirst($controller) . 'Controller';

        if (class_exists($controller)) {
            return new $controller();
        }

        return new ErrorController();
    }


    /**
     * Check if the method exist in a given controller
     * @param AbstractController $controller
     * @param string|null $action
     * @return string|null
     */
    private static function methodExist(AbstractController $controller, ?string $action): ?string {

        if (strpos($action, '-') !== -1) {
            $array = [];
            $a = explode('-', $action);

            foreach ($a as $key => $value) {

                if ($key !== 0) {
                    $value = ucfirst($value);
                }

                $array[] = $value;
            }
            $action = implode($array);
        }

        return method_exists($controller, $action) ? $action : null;
    }


    /**
     * return an array with the method argument(s)
     * @param AbstractController $controller
     * @param string $action
     * @return array
     * @throws ReflectionException
     */
    private static function argumentsExist(AbstractController $controller, string $action): array {
        $array = [];
        $reflexion = new ReflectionMethod($controller, $action);
        $parameters = $reflexion->getParameters();

        foreach ($parameters as $value) {
            $array[] = [
                'param' => $value->name,
                'type' => $value->getType()->getName(),
            ];
        }

        return $array;
    }


    /**
     * Get and sanitized param from $_GET
     * @param string $key
     * @param null $default
     * @return string|null
     */
    private static function getParam(string $key, $default = null): ?string {
        if (isset($_GET[$key])) {

            return filter_var($_GET[$key], FILTER_SANITIZE_STRING);
        }

        return $default;
    }
}