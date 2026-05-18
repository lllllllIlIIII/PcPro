<?php
class Autoloader {

    // Méthode statique pour lancer l'autoloader
    static function register() {
        // On dit à PHP d'utiliser la fonction 'autoload' de CETTE classe (__CLASS__)
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    // La méthode qui va chercher le fichier
    static function autoload($class) {
        // Le chemin vers tes classes (depuis la racine index.php)
        $path = 'admin/src/php/classes/' . $class . '.class.php';

        if (file_exists($path)) {
            require_once $path;
        }
    }
}
?>