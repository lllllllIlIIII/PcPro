<?php
// 1. On charge la connexion à la base de données ($pdo)
require_once 'admin/src/php/db/db_pg_connect.php';

// 2. On charge manuellement le fichier de l'Autoloader (car lui ne peut pas se charger tout seul !)
require_once 'admin/src/php/classes/autoloader.class.php';

// 3. On "allume" l'autoloader magique
Autoloader::register();
?>