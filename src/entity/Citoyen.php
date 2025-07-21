<?php

namespace App\src\entity;

use App\core\AbstractEntity;

class Citoyen 
{
    private string $nci;
    private string $nom;
    private string $prenom;
    private string $date_naissance;
    private string $lieu_naissance;
    private string $copie_url;

    public function __construct(
        string $nci, string $nom, string $prenom,
        string $date_naissance, string $lieu_naissance, string $copie_url
    ) {
        $this->nci = $nci;
        $this->nom = $nom; 
        $this->prenom = $prenom;
        $this->date_naissance = $date_naissance;
        $this->lieu_naissance = $lieu_naissance;
        $this->copie_url = $copie_url;
    }

    public function toArray(): array {
        return [
            'nci' => $this->nci,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date' => $this->date_naissance,
            'lieu' => $this->lieu_naissance,
            'copie_url' => $this->copie_url,
        ];
    }
}
