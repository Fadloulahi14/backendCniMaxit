<?php

namespace App\core;

use App\core\middlewares\AuthMiddleware;

class Middleware {
    private static array $middlewares = [
        'auth' => AuthMiddleware::class,
    ];
    
    public static function execute(string $middlewareName): void {
        if (isset(self::$middlewares[$middlewareName])) {
            $middlewareClass = self::$middlewares[$middlewareName];
            $middleware = new $middlewareClass();
            $middleware();
        }
    }
}