<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

try {
    $dsn = $_ENV['DNS'] ?? "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base réussie\n";
} catch (PDOException $e) {
    die(" Échec de connexion : " . $e->getMessage());
}

try {
    $pdo->beginTransaction();

   
    $citoyens = [
        ['95041221001', 'Ndoye', 'Fatou', '1995-04-12', 'Dakar', 'https://cloud.maxitsa.sn/cni/95041221001.png'],
        ['88092511002', 'Sarr', 'Moussa', '1988-09-25', 'Thiès', 'https://cloud.maxitsa.sn/cni/88092511002.png'],
        ['00010121003', 'Ba', 'Aminata', '2000-01-01', 'Kaolack', 'https://cloud.maxitsa.sn/cni/00010121003.png'],
    ];

    $stmtCitoyen = $pdo->prepare("INSERT INTO citoyen (nci, nom, prenom, date_naissance, lieu_naissance, copie_url) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($citoyens as $citoyen) {
        $stmtCitoyen->execute($citoyen);
    }
    echo "Citoyens insérés\n";

    
    $recherches = [
        ['95041221001', '192.168.1.1', 'Dakar, Sénégal', 'Success'],
        ['88092511002', '192.168.1.45', 'Thiès, Sénégal', 'Échec'],
        ['00010121003', '192.168.1.90', 'Kaolack, Sénégal', 'Success'],
    ];

    $stmtLog = $pdo->prepare("INSERT INTO recherchelog (nci, ip, localisation, statut) VALUES (?, ?, ?, ?)");
    foreach ($recherches as $recherche) {
        $stmtLog->execute($recherche);
    }
    echo "Logs de recherche insérés\n";

    $pdo->commit();
    echo "Données de seed insérées avec succès\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    die(" Erreur lors du seed : " . $e->getMessage());
}
