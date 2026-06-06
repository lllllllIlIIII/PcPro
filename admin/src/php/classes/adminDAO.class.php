<?php
class AdminDAO {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getCatalogueOrders(): array {
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

    public function getCustomOrders(): array {
        try {
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

    public function saveCustomOrder(int $id_user, string $description, float $prix): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT insert_commande_custom(?, ?, ?)");
            return $stmt->execute([$id_user, $description, $prix]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCustomOrdersByUser(int $id_user): array {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * 
                FROM commande_custom
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