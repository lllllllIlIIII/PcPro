<?php
class Catalogue {
    private $id_pc;
    private $nom_modele;
    private $description;
    private $processeur;
    private $carte_mere;
    private $carte_graphique;
    private $memoire;
    private $stockage;
    private $prix;
    private $image_url;

    public function __construct($id_pc, $nom_modele, $description, $processeur, $carte_mere, $carte_graphique, $memoire, $stockage, $prix, $image_url) {
        $this->id_pc = $id_pc;
        $this->nom_modele = $nom_modele;
        $this->description = $description;
        $this->processeur = $processeur;
        $this->carte_mere = $carte_mere;
        $this->carte_graphique = $carte_graphique;
        $this->memoire = $memoire;
        $this->stockage = $stockage;
        $this->prix = $prix;
        $this->image_url = $image_url;
    }

    public function getIdPc() { return $this->id_pc; }
    public function getNomModele() { return $this->nom_modele; }
    public function getDescription() { return $this->description; }
    public function getProcesseur() { return $this->processeur; }
    public function getCarteMere() { return $this->carte_mere; }
    public function getCarteGraphique() { return $this->carte_graphique; }
    public function getMemoire() { return $this->memoire; }
    public function getStockage() { return $this->stockage; }
    public function getPrix() { return $this->prix; }
    public function getImageUrl() { return $this->image_url; }
}
?>