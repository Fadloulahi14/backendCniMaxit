<?php
namespace App\message;
enum MessageFr:string{
   case TELEPHONE_REQUIRED = "Le numéro de téléphone est obligatoire";
   case TELEPHONE_INVALID = "Numéro de téléphone invalide";
   case COMPTE_NON_TROUVE = "Aucun compte trouvé avec ce numéro de téléphone";
   case ERREUR_CONNEXION = "Erreur de connexion";
   case PHOTO_RECTO_OBLIGATOIRE = "La photo recto de la CNI est obligatoire";
   case PHOTO_VERSO_OBLIGATOIRE = "La photo verso de la CNI est obligatoire";
   case INSCRIPTION_REUSSIE = "Inscription réussie ! Votre compte a été créé avec succès.";
}