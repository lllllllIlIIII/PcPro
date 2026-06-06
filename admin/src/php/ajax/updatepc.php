<?php
header('Content-Type: application/json');

require_once 'admin/src/php/utils/all_include.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Accès refusé"]);
    exit;
}


if (isset($_GET['champ']) && isset($_GET['nouveau']) && isset($_GET['id_produit'])) {
    $dao = new CatalogueDAO($pdo);

    $champ = $_GET['champ'];
    $nouveau = $_GET['nouveau'];
    $id_produit = intval($_GET['id_produit']);
    $resultat = $dao->updateChamp($id_produit, $champ, $nouveau);
    echo json_encode($resultat);
} else {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
}
?>