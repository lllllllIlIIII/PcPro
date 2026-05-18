<?php
class sur_mesureDAO {
    private $pdo;

    // On lui passe la connexion PDO quand on le crée
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour récupérer tout le catalogue
    public function getAllsur_msesure() {
        $liste = [];
        try {
            // On interroge PostgreSQL
            $stmt = $this->pdo->query("SELECT * FROM techno2");

            // On transforme chaque ligne SQL en un véritable Objet PHP
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $liste[] = new sur_mesure(
                    $row['id_pc'],
                    $row['nom_modele'],
                    $row['description'],
                    $row['prix_total']
                );
            }
        } catch (PDOException $e) {
            // Si la table n'existe pas encore dans PostgreSQL, ça évite un crash fatal
            // On retourne juste un tableau vide en attendant que tu crées la table
        }

        return $liste;
    }
}
?>