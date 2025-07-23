<?php

namespace App\src\repository;
use  App\config\App;
use App\config\Database;
use App\src\entity\Citoyen;
use PDO;

class CitoyenRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo =Database::getInstance();
    }

   public function findByNci(string $nci): ?Citoyen
    {
        $sql = "SELECT * FROM citoyen WHERE nci = :nci";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nci' => $nci]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? Citoyen::toObject($data) : null;
    }

    public function findAll(): array
    {

       
        $sql = "SELECT * FROM citoyen";
            $stmt = $this->pdo->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($item) => Citoyen::toObject($item), $data);
        }
        

    // public function save(Citoyen $citoyen): bool
    // {
    //     $sql = "INSERT INTO citoyen (nci, nom, prenom, date_naissance, lieu_naissance, url_carte_recto, url_carte_verso)
    //             VALUES (:nci, :nom, :prenom, :date_naissance, :lieu_naissance, :url_carte_recto, :url_carte_verso)";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute([
    //         'nci' => $citoyen->getNci(),
    //         'nom' => $citoyen->getNom(),
    //         'prenom' => $citoyen->getPrenom(),
    //         'date_naissance' => $citoyen->getDateNaissance(),
    //         'lieu_naissance' => $citoyen->getLieuNaissance(),
    //         'url_carte_recto' => $citoyen->getUrlCarteRecto(),
    //         'url_carte_verso' => $citoyen->getUrlCarteVerso()
    //     ]);
    // }
}
