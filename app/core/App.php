<?php
namespace App\config; // Déclare le namespace de la classe

class App
{
    // Tableau contenant les dépendances extraites du fichier YAML
    private static array $dependencies = [];

    /**
     * Charge les dépendances depuis le fichier YAML `service.yml`
     */
    public static function loadDependencies(): void {
        // Chemin vers le fichier service.yml
        $servicesFile = __DIR__ . '/../config/service.yml';

        // Lit le contenu du fichier
        $yamlContent = file_get_contents($servicesFile);

        // Parse le contenu YAML en tableau associatif
        $services = self::parseYaml($yamlContent);

        // Stocke uniquement la section "services"
        if (isset($services['services'])) {
            self::$dependencies = $services['services'];
        }
    }

    /**
     * Parse un contenu YAML très simple ligne par ligne
     * et retourne un tableau associatif
     */
    private static function parseYaml(string $yamlContent): array {
        $lines = explode("\n", $yamlContent); // Sépare chaque ligne
        $result = []; // Résultat final
        $currentSection = null; // Nom de la section actuelle (ex: services)

        foreach ($lines as $line) {
            $line = trim($line); // Nettoie les espaces

            // Ignore les lignes vides ou commentaires
            if ($line === '' || str_starts_with($line, '#')) continue;

            // Vérifie s'il y a un ':' dans la ligne
            if (strpos($line, ':') !== false) {
                // Sépare la clé et la valeur
                [$key, $value] = array_map('trim', explode(':', $line, 2));

                // Cas où c’est une nouvelle section
                if ($value === '') {
                    $currentSection = $key;
                    $result[$currentSection] = [];
                } else {
                    // Si on est dans une section, ajoute à l’intérieur
                    if ($currentSection) {
                        $result[$currentSection][$key] = $value;
                    } else {
                        // Sinon, ajoute en racine
                        $result[$key] = $value;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Retourne une instance de la dépendance associée à la clé du YAML
     */
    public static function getDependency(string $key): object {
        // Charge les dépendances si ce n’est pas encore fait
        if (empty(self::$dependencies)) {
            self::loadDependencies();
        }

        // Vérifie que la clé existe dans le tableau
        if (!array_key_exists($key, self::$dependencies)) {
            throw new \Exception("Dépendance '$key' introuvable");
        }

        // Résout la classe liée à cette clé
        return self::resolve(self::$dependencies[$key]);
    }

    /**
     * Résout une classe en injectant automatiquement ses dépendances
     */
    public static function resolve(string $class): object {
        // Vérifie que la classe existe
        if (!class_exists($class)) {
            throw new \Exception("Classe '$class' introuvable");
        }

        // Récupère les infos de réflexion sur la classe
        $reflection = new \ReflectionClass($class);

        // Si la classe n’a pas de constructeur, on l’instancie simplement
        if (!$reflection->getConstructor()) {
            return new $class();
        }

        // Récupère les paramètres du constructeur
        $params = $reflection->getConstructor()->getParameters();

        // Pour chaque paramètre, on cherche à résoudre le type
        $dependencies = array_map(function ($param) {
            $type = $param->getType(); // Type attendu (ex: App\Database)

            // Si le type est manquant ou primitif (int, string...), erreur
            if (!$type || $type->isBuiltin()) {
                throw new \Exception("Type invalide pour le paramètre '{$param->getName()}'");
            }

            $typeName = $type->getName(); // Nom du type (ex: App\Database)

            // On recherche ce type dans le YAML (via sa valeur)
            $foundKey = array_search($typeName, self::$dependencies);

            // Si non trouvé, erreur
            if (!$foundKey) {
                throw new \Exception("Dépendance de type '$typeName' non déclarée dans le YAML");
            }

            // Appel récursif : on résout cette dépendance
            return self::resolve($typeName);
        }, $params);

        // Instancie la classe avec les dépendances résolues
        return $reflection->newInstanceArgs($dependencies);
    }
}
