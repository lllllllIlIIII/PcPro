<?php
class ComposantDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1. Ancienne méthode (pour afficher les listes déroulantes)
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
            echo "Erreur SQL : " . $e->getMessage();
        }
        return $liste;
    }

    // 2. NOUVELLE MÉTHODE (Pour le calcul du prix en AJAX !)
    public function getComposantById($id_comp) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_comp, nom, prix, id_cat FROM composant WHERE id_comp = ?");
            $stmt->execute([$id_comp]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Composant($row['id_comp'], $row['nom'], $row['prix'], $row['id_cat']);
            }
        } catch (PDOException $e) {
            // Silencieux
        }
        return null;
    }
}
?>