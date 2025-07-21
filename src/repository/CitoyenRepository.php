<?php

namespace App\src\repository;

use App\app\config\Database;
use App\src\entity\Citoyen;
use PDO;

class CitoyenRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->getConnection();
    }

    public function findByNci(string $nci): ?Citoyen
    {
        $stmt = $this->pdo->prepare("SELECT * FROM citoyens WHERE nci = :nci");
        $stmt->execute(['nci' => $nci]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Citoyen(
                $data['nci'],
                $data['nom'],
                $data['prenom'],
                $data['date_naissance'],
                $data['lieu_naissance'],
                $data['carte_identite_url']
            );
        }
        return null;
    }
}
