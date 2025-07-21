<?php

namespace App\src\service;

use App\src\repository\CitoyenRepository;

class CitoyenService
{
    private CitoyenRepository $repository;

    public function __construct()
    {
        $this->repository = new CitoyenRepository();
    }

    public function rechercherCitoyen(string $nci): array
    {
        $citoyen = $this->repository->findByNci($nci);
        if ($citoyen) {
            return [
                'data' => $citoyen->toArray(),
                'statut' => 'success',
                'code' => 200,
                'message' => 'Le numéro de carte d\'identité a été retrouvé'
            ];
        }

        return [
            'data' => null,
            'statut' => 'error',
            'code' => 404,
            'message' => 'Le numéro de carte d\'identité non retrouvé'
        ];
    }
}
