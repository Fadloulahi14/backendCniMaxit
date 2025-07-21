<?php

namespace App\app\config;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'appdaf_db';
        $user = 'user';
        $pass = 'pass';
        $this->pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
