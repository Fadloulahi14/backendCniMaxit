<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5434;dbname=appdaff', 'appdaf_user', 'secretpass');
    echo "Connexion réussie ✅";
} catch (PDOException $e) {
    echo "Erreur ❌ : " . $e->getMessage();
}
