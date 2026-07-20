
CREATE DATABASE produits;
USE produits;

DROP TABLE IF EXISTS vente;
DROP TABLE IF EXISTS produit_membre;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS membre;

CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    numero_etu VARCHAR(20) NOT NULL UNIQUE,
    image_profil VARCHAR(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_categorie INT,
    prix_reference DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produit_membre (
    id_produit_membre INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    id_membre INT NOT NULL,
    prix_vente DECIMAL(10, 2) NOT NULL,
    quantite_dispo INT NOT NULL DEFAULT 0,
    date_dispo DATE NOT NULL,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE vente (
    id_vente INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    heure TIME NOT NULL,
    id_produit_membre INT NOT NULL,
    quantite INT NOT NULL,
    FOREIGN KEY (id_produit_membre) REFERENCES produit_membre(id_produit_membre) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO membre (nom, numero_etu, image_profil) VALUES
('Rivo Andriana', 'ETU0001', 'rivo.jpg'),
('Sitraka Rakoto', 'ETU0002', 'sitraka.jpg'),
('Mialy Ranaivo', 'ETU0003', 'mialy.jpg'),
('Feno Heriniaina', 'ETU0004', 'feno.jpg'),
('Tiana Razafy', 'ETU0005', 'tiana.jpg'),
('Niry Toky', 'ETU0006', 'niry.jpg'),
('Lova Hasina', 'ETU0007', 'lova.jpg'),
('Tsiry Lalaina', 'ETU0008', 'tsiry.jpg'),
('Harinaivo Eric', 'ETU0009', 'harinaivo.jpg'),
('Volana Sofia', 'ETU0010', 'volana.jpg');

INSERT INTO categorie (nom_categorie) VALUES
('Plat'),
('Boisson'),
('Snack'),
('Dessert');


INSERT INTO produit (nom, id_categorie, prix_reference) VALUES
('Minesao Poulet', 1, 6000.00),
('Riz Cantonnais', 1, 5500.00),
('Misao Légumes', 1, 4500.00),
('Burger Maison', 1, 7000.00),
('Jus de Mangue Naturel', 2, 2500.00),
('Ice Tea Maison', 2, 2000.00),
('Café Glacé', 2, 3000.00),
('Sambos Boeuf (Lot de 3)', 3, 1500.00),
('Nems Poulet (Lot de 3)', 3, 2000.00),
('Frites Maison', 3, 2500.00),
('Sandwich Jambon Fromage', 3, 3500.00),
('Muffin Chocolat', 4, 2000.00),
('Salade de Fruits', 4, 2500.00),
('Crêpe au Nutella', 4, 3000.00),
('Cookie Pépites de Chocolat', 4, 1500.00);


INSERT INTO produit_membre (id_produit, id_membre, prix_vente, quantite_dispo, date_dispo) VALUES
(1, 1, 5800.00, 5, '2026-07-20'),  
(2, 2, 5500.00, 4, '2026-07-20'),  
(4, 3, 7000.00, 3, '2026-07-20'),  
(5, 4, 2500.00, 10, '2026-07-20'), 
(8, 5, 1500.00, 15, '2026-07-20'), 
(12, 6, 1800.00, 6, '2026-07-20'), 
(14, 7, 3000.00, 5, '2026-07-20'), 
(3, 8, 4500.00, 4, '2026-07-21'),  
(6, 9, 2000.00, 8, '2026-07-21'),  
(9, 10, 2200.00, 12, '2026-07-21'),
(11, 1, 3500.00, 7, '2026-07-21'), 
(13, 2, 2400.00, 5, '2026-07-21'), 
(15, 3, 1500.00, 20, '2026-07-21'), 
(1, 4, 6000.00, 3, '2026-07-22'),  
(7, 5, 3000.00, 6, '2026-07-22'),  
(10, 6, 2500.00, 10, '2026-07-22'), 
(2, 7, 5600.00, 4, '2026-07-22'),  
(5, 8, 2500.00, 8, '2026-07-22'),  
(8, 9, 1400.00, 10, '2026-07-22'), 
(12, 10, 2000.00, 5, '2026-07-22');

ALTER TABLE produit_membre ADD COLUMN photo_offre VARCHAR(255) DEFAULT NULL;

-- 🛒 MISE À JOUR CORRECTE DES PHOTOS (Basée sur tes ID réels)

-- id_produit 1 : minesao.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'minesao.jpeg' WHERE `id_produit` = 1;

-- id_produit 2 : Riz_Cantonnais.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Riz_Cantonnais.jpeg' WHERE `id_produit` = 2;

-- id_produit 3 : misao_legumes.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'misao_legumes.jpeg' WHERE `id_produit` = 3;

-- id_produit 4 : burger.webp
UPDATE `produit_membre` SET `photo_offre` = 'burger.webp' WHERE `id_produit` = 4;

-- id_produit 5 : jus_mangue.jpg
UPDATE `produit_membre` SET `photo_offre` = 'jus_mangue.jpg' WHERE `id_produit` = 5;

-- id_produit 6 : Ice_Tea.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Ice_Tea.jpeg' WHERE `id_produit` = 6;

-- id_produit 7 : Café_Glacé.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Café_Glacé.jpeg' WHERE `id_produit` = 7;

-- id_produit 8 : Sambos_Boeuf.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Sambos_Boeuf.jpeg' WHERE `id_produit` = 8;

-- id_produit 9 : Nems_Poulet.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Nems_Poulet.jpeg' WHERE `id_produit` = 9;

-- id_produit 10 : Frites.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Frites.jpeg' WHERE `id_produit` = 10;

-- id_produit 11 : Sandwich.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Sandwich.jpeg' WHERE `id_produit` = 11;

-- id_produit 12 : Muffin.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Muffin.jpeg' WHERE `id_produit` = 12;

-- id_produit 13 : Salade_de_Fruits.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Salade_de_Fruits.jpeg' WHERE `id_produit` = 13;

-- id_produit 14 : Crêpe_au_Nutella.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Crêpe_au_Nutella.jpeg' WHERE `id_produit` = 14;

-- id_produit 15 : Cookie.jpeg
UPDATE `produit_membre` SET `photo_offre` = 'Cookie.jpeg' WHERE `id_produit` = 15;