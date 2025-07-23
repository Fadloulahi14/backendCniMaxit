<?php

namespace App\src\http;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit; 
    }

    public static function success(array $data = [], string $message = 'Succès', int $statusCode = 200): void
    {
        self::json([
            'data' => $data,
            'statut' => 'success',
            'code' => $statusCode,
            'message' => $message
        ], $statusCode);
    }

    public static function error(string $message = 'Erreur', int $statusCode = 400): void
    {
        self::json([
            'data' => null,
            'statut' => 'error',
            'code' => $statusCode,
            'message' => $message
        ], $statusCode);
    }
}
