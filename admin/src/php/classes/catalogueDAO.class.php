<?php
class CatalogueDAO { // <-- Le nom finit bien par DAO
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllcatalogue() {
        $liste = [];
        try {
            $stmt = $this->pdo->query("SELECT * FROM pc_catalogue ORDER BY prix ASC");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Ici, on crée l'objet "Catalogue" (l'entité), pas le DAO !
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
        } catch (PDOException $e) {
            // Optionnel : echo $e->getMessage(); pour voir les erreurs SQL
        }
        return $liste;
    }
    // 2. Récupérer UN SEUL PC par son ID (pour la page détail)
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
        } catch (PDOException $e) {
            // Erreur SQL
        }
        return null;
    }
    // --- PARTIE CRUD ADMIN (Appel des fonctions PL/pgSQL) ---

    // CREATE
    public function addPC($nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img) {
        $stmt = $this->pdo->prepare("SELECT insert_catalogue(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img]);
    }

    // UPDATE
    public function updatePC($id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img) {
        $stmt = $this->pdo->prepare("SELECT update_catalogue(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img]);
    }

    // DELETE
    public function deletePC($id) {
        $stmt = $this->pdo->prepare("SELECT delete_catalogue(?)");
        return $stmt->execute([$id]);
    }
    // --- PARTIE COMMANDES SUR MESURE ---

    // Sauvegarder une commande custom via PL/pgSQL
    public function saveCustomOrder($id_user, $description, $prix) {
        $stmt = $this->pdo->prepare("SELECT insert_commande_custom(?, ?, ?)");
        return $stmt->execute([$id_user, $description, $prix]);
    }

    // Récupérer tout l'historique pour le Dashboard Admin
    public function getCustomOrders() {
        try {
            // On fait une JOINTURE (JOIN) pour récupérer le nom et l'email du client grâce à son ID
            $stmt = $this->pdo->query("
                SELECT c.*, u.nom as client_nom, u.email 
                FROM commande_custom c 
                JOIN utilisateur u ON c.id_user = u.id_user 
                ORDER BY c.date_commande DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function saveCatalogueOrder($id_user, $id_pc, $quantite, $prix) {
        $stmt = $this->pdo->prepare("SELECT insert_commande_catalogue(?, ?, ?, ?)");
        return $stmt->execute([$id_user, $id_pc, $quantite, $prix]);
    }

    public function getCatalogueOrders() {
        try {
            $stmt = $this->pdo->query("
                SELECT c.*, u.nom as client_nom, u.email, p.nom_modele 
                FROM commande_catalogue c 
                JOIN utilisateur u ON c.id_user = u.id_user 
                JOIN pc_catalogue p ON c.id_pc = p.id_pc
                ORDER BY c.date_commande DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    // --- NOUVELLES MÉTHODES POUR L'ESPACE CLIENT ---

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

    public function getCustomOrdersByUser($id_user) {
        try {
            // Remplace "commande_sur_mesure" par le vrai nom de ta table si besoin
            $stmt = $this->pdo->prepare("
                SELECT * 
                FROM commande_sur_mesure 
                WHERE id_user = ?
                ORDER BY date_commande DESC
            ");
            $stmt->execute([$id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>