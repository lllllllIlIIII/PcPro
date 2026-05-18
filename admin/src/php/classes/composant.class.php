<?php
class Composant {
    private $id_comp;
    private $nom;
    private $prix;
    private $id_cat; // 1=CPU, 2=GPU, 3=RAM (selon ta table categorie)

    public function __construct($id_comp, $nom, $prix, $id_cat) {
        $this->id_comp = $id_comp;
        $this->nom = $nom;
        $this->prix = $prix;
        $this->id_cat = $id_cat;
    }

    // Les Getters
    public function getIdComp() { return $this->id_comp; }
    public function getNom() { return $this->nom; }
    public function getPrix() { return $this->prix; }
    public function getIdCat() { return $this->id_cat; }
}
?>