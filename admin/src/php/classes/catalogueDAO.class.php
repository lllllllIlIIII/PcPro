<?php
class CatalogueDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllcatalogue() {
        $liste = [];
        try {
            $stmt = $this->pdo->query("SELECT * FROM pc_catalogue ORDER BY prix ASC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $liste[] = new Catalogue(
                    $row['id_pc'],
                    $row['nom_modele'],
                    $row['description'],
                    $row['processeur'],
                    $row['carte_mere'],
                    $row['carte_graphique'],
                    $row['memoire'],
                    $row['stockage'],
                    $row['prix'],
                    $row['image_url']
                );
            }
        } catch (PDOException $e) {}
        return $liste;
    }

    public function getCatalogueById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM pc_catalogue WHERE id_pc = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new Catalogue(
                    $row['id_pc'],
                    $row['nom_modele'],
                    $row['description'],
                    $row['processeur'],
                    $row['carte_mere'],
                    $row['carte_graphique'],
                    $row['memoire'],
                    $row['stockage'],
                    $row['prix'],
                    $row['image_url']
                );
            }
        } catch (PDOException $e) {}
        return null;
    }

    public function addPC($nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img) {
        try {
            $stmt = $this->pdo->prepare("SELECT insert_catalogue(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePC($id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img) {
        try {
            $prix = str_replace(',', '.', $prix);
            $stmt = $this->pdo->prepare("SELECT update_catalogue(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function updateChamp($id, $champ, $valeur) {
        $champs_autorises = ['nom_modele', 'prix'];
        if (!in_array($champ, $champs_autorises)) {
            return ["success" => false, "message" => "Champ non autorisé"];
        }
        try {
            $stmt = $this->pdo->prepare("UPDATE pc_catalogue SET $champ = ? WHERE id_pc = ?");
            $stmt->execute([$valeur, $id]);
            return ["success" => true, "message" => "Mise à jour réussie"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function deletePC($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT delete_catalogue(?)");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function saveCatalogueOrder($id_user, $id_pc, $quantite, $prix) {
        try {
            $stmt = $this->pdo->prepare("SELECT insert_commande_catalogue(?, ?, ?, ?)");
            return $stmt->execute([$id_user, $id_pc, $quantite, $prix]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCatalogueOrdersByUser($id_user) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.*, p.nom_modele 
                FROM commande_catalogue c 
                JOIN pc_catalogue p ON c.id_pc = p.id_pc
                WHERE c.id_user = ?
                ORDER BY c.date_commande DESC
            ");
            $stmt->execute([$id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>