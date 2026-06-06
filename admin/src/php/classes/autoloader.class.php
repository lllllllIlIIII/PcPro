<?php
class Autoloader {

    static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class) {
        $path = 'admin/src/php/classes/' . $class . '.class.php';

        if (file_exists($path)) {
            require_once $path;
        }
    }
}
?>