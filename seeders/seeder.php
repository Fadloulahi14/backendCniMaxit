<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
use App\src\service\ServiceImgBB;

$imgbb = new ServiceImgBB('b33ea484544a58932f50bad3f147bfab');


try {
    $dsn = $_ENV['DNS'] ?? "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
    $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base réussie\n";
} catch (PDOException $e) {
    die("Échec de connexion : " . $e->getMessage());
}

try {
    $pdo->beginTransaction();

    // ✅ Génération de 30 citoyens
    $stmtCitoyen = $pdo->prepare("INSERT INTO citoyen (nci, nom, prenom, date_naissance, lieu_naissance, url_carte_recto, url_carte_verso) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $noms = ['Diop', 'Sow', 'Ba', 'Fall', 'Ndiaye', 'Gueye', 'Diallo', 'Faye', 'Sy', 'Camara'];
    $prenoms = ['Moussa', 'Fatou', 'Awa', 'Oumar', 'Aminata', 'Cheikh', 'Mariama', 'Ibrahima', 'Seynabou', 'Abdou'];

    // for ($i = 1; $i <= 30; $i++) {
    //     $nci = sprintf("199%02d%02d%05d", rand(0, 9), rand(1, 12), $i);
    //     $nom = $noms[array_rand($noms)];
    //     $prenom = $prenoms[array_rand($prenoms)];
    //     $date_naissance = sprintf("199%d-%02d-%02d", rand(0, 9), rand(1, 12), rand(1, 28));
    //     $lieu_naissance = ['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'][array_rand(['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'])];
    //     $url_recto = "https://cloud.maxitsa.sn/cni/{$nci}_recto.png";
    //     $url_verso = "https://cloud.maxitsa.sn/cni/{$nci}_verso.png";

    //     $stmtCitoyen->execute([$nci, $nom, $prenom, $date_naissance, $lieu_naissance, $url_recto, $url_verso]);
    // }

    for ($i = 1; $i <= 5; $i++) {
    $nci = sprintf("199%02d%02d%05d", rand(0, 9), rand(1, 12), $i);
    $nom = $noms[array_rand($noms)];
    $prenom = $prenoms[array_rand($prenoms)];
    $date_naissance = sprintf("199%d-%02d-%02d", rand(0, 9), rand(1, 12), rand(1, 28));
    $lieu_naissance = ['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'][array_rand(['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'])];

    $rectoPath = __DIR__ . "/../storage/cni/{$nci}_recto.png";
    $versoPath = __DIR__ . "/../storage/cni/{$nci}_verso.png";

    $url_recto = $imgbb->uploadImage($rectoPath) ?? 'default_recto.png';
    $url_verso = $imgbb->uploadImage($versoPath) ?? 'default_verso.png';

    $stmtCitoyen->execute([$nci, $nom, $prenom, $date_naissance, $lieu_naissance, $url_recto, $url_verso]);
}


    echo "30 citoyens insérés\n";

    // ✅ Logs de recherche pour 3 d'entre eux
    $recherches = [
        [
            '19901010001', date('Y-m-d H:i:s'), '192.168.1.1', 'Dakar, Sénégal', 'Success', 'Recherche effectuée avec succès.'
        ],
        [
            '19902020002', date('Y-m-d H:i:s'), '192.168.1.45', 'Thiès, Sénégal', 'Échec', 'Aucune correspondance trouvée.'
        ],
        [
            '19903030003', date('Y-m-d H:i:s'), '192.168.1.90', 'Kaolack, Sénégal', 'Success', null
        ],
    ];

    $stmtLog = $pdo->prepare("INSERT INTO recherchelog (nci, date_recherche, ip, localisation, statut, message) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($recherches as $recherche) {
        $stmtLog->execute($recherche);
    }

    echo "Logs de recherche insérés\n";

    $pdo->commit();
    echo "✅ Données de seed insérées avec succès\n";

} catch (PDOException $e) {
    $pdo->rollBack();
    die("❌ Erreur lors du seed : " . $e->getMessage());
}
