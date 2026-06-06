<?php
class user {
    private $id_user;
    private $nom;
    private $email;
    private $role;

    public function __construct($id_user, $nom, $email, $role) {
        $this->id_user = $id_user;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
    }

    public function getIdUser() { return $this->id_user; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }
}
?>