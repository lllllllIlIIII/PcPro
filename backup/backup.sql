CREATE TABLE IF NOT EXISTS categorie (
                                         id_cat SERIAL PRIMARY KEY,
                                         nom_cat VARCHAR(100) NOT NULL UNIQUE
    );

CREATE TABLE IF NOT EXISTS composant (
                                         id_comp SERIAL PRIMARY KEY,
                                         nom VARCHAR(200) NOT NULL,
    marque VARCHAR(100),
    prix DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 10,
    id_cat INT NOT NULL,
    CONSTRAINT fk_comp_cat FOREIGN KEY (id_cat) REFERENCES categorie(id_cat)
    );


INSERT INTO categorie (id_cat, nom_cat) OVERRIDING SYSTEM VALUE VALUES
                                                                    (1, 'Processeur (CPU)'),
                                                                    (2, 'Carte Graphique (GPU)'),
                                                                    (3, 'Mémoire (RAM)');

--Insertion des Composants
INSERT INTO composant (nom, marque, prix, stock, id_cat) VALUES
-- Les Processeurs (Catégorie 1)
('AMD Ryzen 5 7600X', 'AMD', 249.99, 15, 1),
('Intel Core i5-13600K', 'Intel', 319.50, 10, 1),
('AMD Ryzen 7 7800X3D', 'AMD', 399.00, 5, 1),

-- Les Cartes Graphiques (Catégorie 2)
('NVIDIA GeForce RTX 4060 8Go', 'MSI', 329.99, 20, 2),
('NVIDIA GeForce RTX 4070 SUPER 12Go', 'Asus', 659.00, 8, 2),
('AMD Radeon RX 7800 XT 16Go', 'Sapphire', 549.90, 12, 2),

-- La Mémoire RAM (Catégorie 3)
('16 Go (2x8Go) DDR5 5200MHz', 'Corsair', 79.99, 30, 3),
('32 Go (2x16Go) DDR5 6000MHz CL30', 'G.Skill', 139.99, 25, 3),
('64 Go (2x32Go) DDR5 6000MHz', 'Corsair', 269.50, 10, 3);