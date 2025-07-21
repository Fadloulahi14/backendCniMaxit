<?php

namespace App\src\controller;

use App\src\service\CitoyenService;

class CitoyenController
{
    private CitoyenService $service;

    public function __construct()
    {
        $this->service = new CitoyenService();
    }

    public function rechercher(): void
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $nci = $_GET['nci'] ?? null;

        if (!$nci) {
            http_response_code(400);
            echo json_encode([
                'data' => null,
                'statut' => 'error',
                'code' => 400,
                'message' => 'Le NCI est requis'
            ]);
            return;
        }

        $result = $this->service->rechercherCitoyen($nci);
        http_response_code($result['code']);
        echo json_encode($result);
    }
}
