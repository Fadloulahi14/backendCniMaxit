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
                     'pgsql:host=dpg-d1v73m6r433s73fe0a3g-a;port=5432;dbname=postgres_biin', 'postgres_biin_user', 'f3cOaIs9MHpE5F9YG0oVNV98YgMpyuWk',
                    //  'pgsql:host=postgres_appdaf;port=5432;dbname=appdaff', 'appdaf_user', 'secretpass',                     'pgsql:host=postgres_appdaf;port=5432;dbname=appdaff', 'appdaf_user', 'secretpass',

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