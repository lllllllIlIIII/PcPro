<?php
class ComposantDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getComposantsByCategorie($id_cat) {
        $liste = [];
        try {
            $stmt = $this->pdo->prepare("SELECT id_comp, nom, prix, id_cat FROM composant WHERE id_cat = :id_cat");
            $stmt->execute(['id_cat' => $id_cat]);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $liste[] = new Composant(
                    $row['id_comp'],
                    $row['nom'],
                    $row['prix'],
                    $row['id_cat']
                );
            }
        } catch (PDOException $e) {
        }
        return $liste;
    }
    public function getComposantById($id_comp) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_comp, nom, prix, id_cat FROM composant WHERE id_comp = ?");
            $stmt->execute([$id_comp]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Composant(
                    $row['id_comp'],
                    $row['nom'],
                    $row['prix'],
                    $row['id_cat']
                );
            }
        } catch (PDOException $e) {
        }
        return null;
    }
}
?>