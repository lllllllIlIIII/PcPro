<?php
// admin/src/php/ajax/calcul_prix.php

// 1. On n'utilise PAS all_include.php ici.
// On va chercher directement les fichiers en remontant d'un dossier (../)
require_once '../db/db_pg_connect.php';
require_once '../classes/Composant.class.php';
require_once '../classes/ComposantDAO.class.php';

// 2. On récupère les données envoyées par ton Javascript
$data = json_decode(file_get_contents('php://input'), true);

$total = 0;

if ($data) {
    // 3. On utilise le DAO ($pdo vient de db_pg_connect.php)
    $compDAO = new ComposantDAO($pdo);

    // On boucle sur chaque pièce sélectionnée
    foreach ($data as $id_comp) {
        if ($id_comp > 0) {
            $composant = $compDAO->getComposantById($id_comp);

            if ($composant != null) {
                // On additionne le prix
                $total += $composant->getPrix();
            }
        }
    }
}

// 4. On renvoie proprement la réponse en format JSON
echo json_encode(['total' => number_format($total, 2, '.', '')]);
?>