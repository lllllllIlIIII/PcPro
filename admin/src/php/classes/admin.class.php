<?php
declare(strict_types=1);

class Admin {
    public int $id_admin;
    public string $nom_admin;
    public int $statut;

    public function __construct(int $id_admin, string $nom_admin, int $statut) {
        $this->id_admin = $id_admin;
        $this->nom_admin = $nom_admin;
        $this->statut = $statut;
    }
}
?>