<?php
namespace App\public;
class Uploader {
    private $uploadDir;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/../../uploads/cni/';
    }

    public function gererUploadPhoto($nomChamp): ?string {
        if (!isset($_FILES[$nomChamp]) || $_FILES[$nomChamp]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $file = $_FILES[$nomChamp];
        
        
        $typesAutorises = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file['type'], $typesAutorises)) {
            throw new \RuntimeException("Type de fichier non autorisé pour $nomChamp. Utilisez JPG, JPEG ou PNG.");
        }
        
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \RuntimeException("Le fichier $nomChamp est trop volumineux (max 5MB)");
        }
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nomFichier = uniqid() . '_' . $nomChamp . '.' . $extension;
        $cheminComplet = $this->uploadDir . $nomFichier;
        
        if (move_uploaded_file($file['tmp_name'], $cheminComplet)) {
            return 'uploads/cni/' . $nomFichier;
        } else {
            throw new \RuntimeException("Erreur lors de l'upload de $nomChamp");
        }
    }
}
