<?php
require_once '../db/db_pg_connect.php';
require_once '../classes/Composant.class.php';
require_once '../classes/ComposantDAO.class.php';

$data = json_decode(file_get_contents('php://input'), true);

$total = 0;

if ($data) {

    $compDAO = new ComposantDAO($pdo);

    foreach ($data as $id_comp) {
        if ($id_comp > 0) {
            $composant = $compDAO->getComposantById($id_comp);

            if ($composant != null) {
                $total += $composant->getPrix();
            }
        }
    }
}

echo json_encode(['total' => number_format($total, 2, '.', '')]);
?>