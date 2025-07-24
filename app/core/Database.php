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
//                 DB_DRIVER=pgsql
// DB_HOST=shortline.proxy.rlwy.net
// DB_PORT=19227
// DB_NAME=railway
// DB_USER=postgres
// DB_PASSWORD=vUIXcPBjYNpYADpYjOZVdrgVPewbWHiO
// ROUTE_WEB=http://localhost:8000/

                self::$connection = new PDO(
                       'pgsql:host=shortline.proxy.rlwy.net;port=19227;dbname=railway', 'postgres', 'vUIXcPBjYNpYADpYjOZVdrgVPewbWHiO',
                    // 'pgsql:host=postgres_appdaf;port=5432;dbname=appdaf_db', 'appdaf_user', 'secretpass',                     

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