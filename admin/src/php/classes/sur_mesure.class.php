<?php
class sur_mesure {
    private $id_pc;
    private $nom_modele;
    private $description;
    private $prix_total;

    // Le constructeur
    public function __construct($id_pc = 0, $nom_modele = '', $description = '', $prix_total = 0) {
        $this->id_pc = $id_pc;
        $this->nom_modele = $nom_modele;
        $this->description = $description;
        $this->prix_total = $prix_total;
    }

    // Les Getters (pour lire les infos dans la vue)
    public function getIdPc() { return $this->id_pc; }
    public function getNomModele() { return $this->nom_modele; }
    public function getDescription() { return $this->description; }
    public function getPrixTotal() { return $this->prix_total; }
}
?>