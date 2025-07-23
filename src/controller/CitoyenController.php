<?php

namespace App\src\controller;

use App\src\service\CitoyenService;
use App\src\http\Response;

class CitoyenController
{
    private CitoyenService $citoyenService;

    public function __construct()
    {
        $this->citoyenService = new CitoyenService();
    }

    public function findByNci(string $nci): void
    {
        $citoyen = $this->citoyenService->findByNci($nci);

        if ($citoyen) {
            Response::success([
                'nci' => $citoyen->getNci(),
                'nom' => $citoyen->getNom(),
                'prenom' => $citoyen->getPrenom(),
                'date' => $citoyen->getDateNaissance(),
                'lieu' => $citoyen->getLieuNaissance(),
                'url_carte_recto' => $citoyen->getUrlCarteRecto(),
                'url_carte_verso' => $citoyen->getUrlCarteVerso(),
            ], "Le numéro de carte d'identité a été retrouvé", 200);
        } else {
            Response::error("Le numéro de carte d'identité non retrouvé", 404);
        }
    }

    public function findAll(): void
    {
        $citoyens = $this->citoyenService->findAll();

        if ($citoyens) {
            $data = array_map(fn($citoyen) => $citoyen->toArray(), $citoyens);
            Response::success($data, "Liste des citoyens récupérée avec succès", 200);
        } else {
            Response::error("Aucun citoyen trouvé", 404);
        }
    }
}
