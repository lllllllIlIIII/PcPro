<?php
class UserDAO {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne la ligne avec la colonne 'mdp'
    }

    public function inscrireClient($nom, $email, $mdp_clair) {
        try {
            $hash = password_hash($mdp_clair, PASSWORD_DEFAULT);
            // On appelle la fonction PL/pgSQL avec un SELECT
            $stmt = $this->pdo->prepare("SELECT insert_user(?, ?, ?)");
            return $stmt->execute([$nom, $email, $hash]);
        } catch (PDOException $e) {
            return false;
        }
    }
}