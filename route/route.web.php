<?php
use App\src\controller\CitoyenController;



$uris = [
    "api/citoyen/{nci}" => [
        'controller' => CitoyenController::class,
        'method' => 'findByNci'    
    ],
    "api/citoyen" => [
        'controller' => CitoyenController::class,
        'method' => 'findAll'    
    ],
];