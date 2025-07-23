<?php
namespace App\config;
use  App\config\App;
class Routeur{
   
    public static function resolve(array $routes){
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = trim($requestUri, '/');

        foreach ($routes as $routePattern => $routeInfo) {
            // Vérifie si le pattern contient {nci}
            if (strpos($routePattern, '{nci}') !== false) {
                // Remplace {nci} par une regex
                $pattern = str_replace('{nci}', '([a-zA-Z0-9]+)', $routePattern);
                if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                    $controllerClass = $routeInfo['controller'];
                    $method = $routeInfo['method'];
                    $controller = new $controllerClass();
                    $controller->$method($matches[1]);
                    return;
                }
            } elseif ($routePattern === $uri) {
                $controllerClass = $routeInfo['controller'];
                $method = $routeInfo['method'];
                $controller = App::getDependency($controllerClass);
                $controller->$method();
                return;
            }
        }
        echo "404 Not Found";
    }
}


