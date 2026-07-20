-- ==========================================
-- SCRIPT DE CRÉATION DE LA BASE DE DONNÉES
-- Projet Final S2 - ITU (Juillet 2026)
-- ==========================================

-- Suppression des tables si elles existent déjà (ordre respectant les clés étrangères)
DROP TABLE IF EXISTS vente;
DROP TABLE IF EXISTS produit_membre;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS membre;

-- 1. Table membre
CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    numero_etu VARCHAR(20) NOT NULL UNIQUE,
    image_profil VARCHAR(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table categorie
CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table produit
CREATE TABLE produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    id_categorie INT,
    prix_reference DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id_categorie) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table produit_membre (Offres publiées par les étudiants)
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

-- 5. Table vente (Commandes passées)
CREATE TABLE vente (
    id_vente INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    heure TIME NOT NULL,
    id_produit_membre INT NOT NULL,
    quantite INT NOT NULL,
    FOREIGN KEY (id_produit_membre) REFERENCES produit_membre(id_produit_membre) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ==========================================
-- INSERTION DES DONNÉES DE TEST
-- ==========================================

-- Insertion des 10 membres (Étudiants ITU)
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

-- Insertion des 4 catégories imposées
INSERT INTO categorie (nom_categorie) VALUES
('Plat'),
('Boisson'),
('Snack'),
('Dessert');

-- Insertion de 15 produits répartis sur les catégories
-- Catégorie 1 (Plat), 2 (Boisson), 3 (Snack), 4 (Dessert)
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

-- Insertion de 20 produits mis en vente par les membres (Offres disponibles)
-- Les prix de vente peuvent légèrement différer du prix de référence (concurrence étudiante !)
INSERT INTO produit_membre (id_produit, id_membre, prix_vente, quantite_dispo, date_dispo) VALUES
(1, 1, 5800.00, 5, '2026-07-20'),  -- Rivo vend du Minesao
(2, 2, 5500.00, 4, '2026-07-20'),  -- Sitraka vend du Riz Cantonnais
(4, 3, 7000.00, 3, '2026-07-20'),  -- Mialy vend un Burger
(5, 4, 2500.00, 10, '2026-07-20'), -- Feno vend du Jus de Mangue
(8, 5, 1500.00, 15, '2026-07-20'), -- Tiana vend des Sambos
(12, 6, 1800.00, 6, '2026-07-20'), -- Niry vend des Muffins
(14, 7, 3000.00, 5, '2026-07-20'),  -- Lova vend des Crêpes
(3, 8, 4500.00, 4, '2026-07-21'),  -- Tsiry vend du Misao Légumes
(6, 9, 2000.00, 8, '2026-07-21'),  -- Harinaivo vend de l'Ice Tea
(9, 10, 2200.00, 12, '2026-07-21'),-- Volana vend des Nems
(11, 1, 3500.00, 7, '2026-07-21'), -- Rivo vend un Sandwich
(13, 2, 2400.00, 5, '2026-07-21'), -- Sitraka vend une Salade de fruits
(15, 3, 1500.00, 20, '2026-07-21'), -- Mialy vend des Cookies
(1, 4, 6000.00, 3, '2026-07-22'),  -- Feno vend aussi du Minesao
(7, 5, 3000.00, 6, '2026-07-22'),  -- Tiana vend du Café Glacé
(10, 6, 2500.00, 10, '2026-07-22'), -- Niry vend des Frites
(2, 7, 5600.00, 4, '2026-07-22'),  -- Lova vend du Riz Cantonnais
(5, 8, 2500.00, 8, '2026-07-22'),  -- Tsiry vend du Jus de Mangue
(8, 9, 1400.00, 10, '2026-07-22'), -- Harinaivo vend des Sambos
(12, 10, 2000.00, 5, '2026-07-22');-- Volana vend des Muffins