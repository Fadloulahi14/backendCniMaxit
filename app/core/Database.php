<?php

namespace App\config;
use Dotenv\Dotenv;

use PDO;
use PDOException;





class Database
{
    private static ?PDO $connection = null;

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                     'pgsql:host=postgres_appdaf;port=5432;dbname=appdaff', 'appdaf_user', 'secretpass',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}