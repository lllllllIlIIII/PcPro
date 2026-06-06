<?php
class sur_mesureDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllsur_msesure() {
        $liste = [];
        try {
            $stmt = $this->pdo->query("SELECT * FROM techno2");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $liste[] = new sur_mesure(
                    $row['id_pc'],
                    $row['nom_modele'],
                    $row['description'],
                    $row['prix_total']
                );
            }
        } catch (PDOException $e) {

        }

        return $liste;
    }
}
?>