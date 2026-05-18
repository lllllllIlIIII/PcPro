<?php
$dsn = 'pgsql:host=localhost;dbname=techno2;port=5432';

$user = 'anonyme';

$pass = 'anonyme';
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion rate, on affiche l'erreur au lieu d'avoir un écran blanc !
    die("<div style='color:red; font-weight:bold; padding:20px;'>Erreur de Base de données : " . $e->getMessage() . "</div>");
}
?>
