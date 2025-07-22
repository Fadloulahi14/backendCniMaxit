<?php

class AbstractRepository{
     protected PDO $pdo;
    public function __construct()
    {
        $this->pdo = App::getDependency('database');
    }
}