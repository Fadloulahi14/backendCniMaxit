<?php

use App\src\controller\CitoyenController;

$uri = $_SERVER['REQUEST_URI'];

if (preg_match('#^/api/citoyen$#', $uri) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new CitoyenController())->rechercher();
    exit;
}
