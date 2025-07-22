<?php
use App\src\controller\CitoyenController;



$uris = [
    "api/citoyen/" => [
        'controller' => CitoyenController::class,
        'method' => 'findByNci'    
    ],
     "" => [
        'controller' => CitoyenController::class,
        'method' => 'findAll'    
    ],
   

];


// ['GET', '/api/citoyen/{nci}', [CitoyenController::class, 'findByNci']],
//     ['GET', '/api/citoyens', [CitoyenController::class, 'findAll']],