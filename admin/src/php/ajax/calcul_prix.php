<?php

try {
    require_once '../db/db_pg_connect.php';
    require_once '../classes/Connexion.class.php';
    require_once '../classes/Composant.class.php';
    require_once '../classes/ComposantDAO.class.php';

    $pdo = Connexion::getInstance($dsn, $user, $pass);

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

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erreur' => $e->getMessage()]);
}
?>