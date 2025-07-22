<?php

require_once __DIR__ . '/../vendor/autoload.php';




function prompt(string $message): string {
    echo $message;
    return trim(fgets(STDIN));
}

function writeEnvIfNotExists(array $config): void {
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) {
      $env = <<<ENV
DB_DRIVER={$config['driver']}
DB_HOST={$config['host']}
DB_PORT={$config['port']}
DB_NAME={$config['dbname']}
DB_USER={$config['user']}
DB_PASSWORD={$config['pass']}
ROUTE_WEB=http://localhost:8000/
DNS="{$config['driver']}:host={$config['host']};dbname={$config['dbname']};port={$config['port']}"
ENV;
        file_put_contents($envPath, $env);
        echo ".env généré avec succès à la racine du projet.\n";
    } else {
        echo "Le fichier .env existe déjà, aucune modification.\n";
    }
}

$driver = strtolower(prompt("Quel SGBD utiliser ? (mysql / pgsql) : "));
$host = prompt("Hôte (default: 127.0.0.1) : ") ?: "127.0.0.1";
$port = prompt("Port (default: 3306 ou 5432) : ") ?: ($driver === 'pgsql' ? "5432" : "3306");
$user = prompt("Utilisateur (default: root) : ") ?: "root";
$pass = prompt("Mot de passe : ");
$dbName = prompt("Nom de la base à créer : ");

try {
    $dsn = "$driver:host=$host;port=$port;dbname=postgres";

    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($driver === 'mysql') {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Base MySQL `$dbName` créée avec succès.\n";
        $pdo->exec("USE `$dbName`");
    } elseif ($driver === 'pgsql') {
        $check = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$dbName'")->fetch();
        if (!$check) {
            $pdo->exec("CREATE DATABASE \"$dbName\"");
            echo "Base PostgreSQL `$dbName` créée.\nRelancez la migration pour créer les tables.\n";
               writeEnvIfNotExists([
                'driver' => $driver,
                'host' => $host,
                'port' => $port,
                'user' => $user,
                'pass' => $pass,
                'dbname' => $dbName
            ]);
            exit;
        } else {
            echo "ℹ Base PostgreSQL `$dbName` déjà existante.\n";
        }
    }

    $dsn = "$driver:host=$host;port=$port;dbname=$dbName";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($driver === 'mysql') {
        $tables = [
                        "CREATE TABLE IF NOT EXISTS citoyen (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nci VARCHAR(100) NOT NULL UNIQUE,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                date_naissance VARCHAR(50),
                lieu_naissance VARCHAR(100),
                copie_url VARCHAR(255)
            );",
            "CREATE TABLE IF NOT EXISTS recherchelog (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nci VARCHAR(100) NOT NULL,
                date_recherche DATETIME DEFAULT CURRENT_TIMESTAMP,
                ip VARCHAR(100),
                localisation VARCHAR(255),
                statut ENUM('Success', 'Échec') NOT NULL
            );"

        ];
    } else {
        // $pdo->exec("DO $$
        // BEGIN
        //     IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'transaction_type') THEN
        //         CREATE TYPE transaction_type AS ENUM ('depot', 'retrait', 'transfert');
        //     END IF;
        // END$$;");

        $tables = [
                "CREATE TABLE IF NOT EXISTS citoyen (
                id SERIAL PRIMARY KEY,
                nci VARCHAR(100) NOT NULL UNIQUE,
                nom VARCHAR(100) NOT NULL,
                prenom VARCHAR(100) NOT NULL,
                date_naissance VARCHAR(50),
                lieu_naissance VARCHAR(100),
                copie_url VARCHAR(255)
            );",
            "CREATE TABLE IF NOT EXISTS recherchelog (
                id SERIAL PRIMARY KEY,
                nci VARCHAR(100) NOT NULL,
                date_recherche TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                ip VARCHAR(100),
                localisation VARCHAR(255),
                statut VARCHAR(20) CHECK (statut IN ('Success', 'Échec')) NOT NULL
            );"

        ];
    }

    foreach ($tables as $sql) {
        $pdo->exec($sql);
    }

    echo "Toutes les tables ont été créées avec succès dans `$dbName`.\n";
    writeEnvIfNotExists([
    'driver' => $driver,
    'host' => $host,
    'port' => $port,
    'user' => $user,
    'pass' => $pass,
    'dbname' => $dbName
]);


} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}