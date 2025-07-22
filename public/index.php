<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// use Dotenv\Dotenv;


// require_once '../config/core/Routeur.php';




require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\config\Routeur;


// $url = $_ENV['ROUTE_WEB'];
$route = require_once '../route/route.web.php';

Routeur::resolve($uris);




