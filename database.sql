-- AL AMIINE LOGISTIQUE - Base de données
-- Importer dans phpMyAdmin ou via: mysql -u root -p < database.sql

CREATE DATABASE IF NOT EXISTS al_amiine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE al_amiine;

-- Table des devis
CREATE TABLE IF NOT EXISTS devis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(30) NOT NULL,
    service VARCHAR(100),
    message TEXT NOT NULL,
    statut ENUM('nouveau','en_cours','traite') DEFAULT 'nouveau',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des produits
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    categorie ENUM('fer','bois','autre') NOT NULL,
    description TEXT,
    prix DECIMAL(10,2),
    unite VARCHAR(30),
    image VARCHAR(255),
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des témoignages
CREATE TABLE IF NOT EXISTS temoignages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    poste VARCHAR(100),
    message TEXT NOT NULL,
    note TINYINT DEFAULT 5,
    approuve TINYINT(1) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table galerie
CREATE TABLE IF NOT EXISTS galerie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150),
    description TEXT,
    image VARCHAR(255) NOT NULL,
    categorie VARCHAR(50),
    actif TINYINT(1) DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table admin
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admin par défaut: admin / admin123
INSERT INTO admins (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@alaamine.com');
-- Mot de passe: password (hash bcrypt)

-- Données de démonstration - Produits
INSERT INTO produits (nom, categorie, description, prix, unite, actif) VALUES
('Fer rond 10mm', 'fer', 'Fer à béton rond, haute résistance, norme NF', 850, 'tonne', 1),
('Fer rond 12mm', 'fer', 'Fer à béton rond 12mm, idéal pour dalles et poteaux', 920, 'tonne', 1),
('Fer rond 16mm', 'fer', 'Fer à béton haute résistance 16mm', 980, 'tonne', 1),
('Profilé IPE 100', 'fer', 'Poutrelle en I acier laminé à chaud', 1200, 'tonne', 1),
('Tôle ondulée', 'fer', 'Tôle galvanisée pour toiture, épaisseur 0.5mm', 4500, 'paquet 10 feuilles', 1),
('Bois de charpente 8x8', 'bois', 'Bois traité classe 4, idéal charpente et ossature', 3200, 'm³', 1),
('Planche de coffrage', 'bois', 'Planche résineux pour coffrage béton, 27mm', 1800, 'm³', 1),
('Contreplaqué 18mm', 'bois', 'Contreplaqué filmé pour coffrage, format 250x122cm', 45000, 'feuille', 1),
('Madrier 6x15', 'bois', 'Madrier sapin, sec, raboté 4 faces', 2800, 'm³', 1);

-- Témoignages de démonstration
INSERT INTO temoignages (nom, poste, message, note, approuve) VALUES
('Ibrahima Diallo', 'Chef de chantier, Dakar', 'Service impeccable ! Livraison ponctuelle et matériaux de très bonne qualité. Je recommande AL AMIINE pour tous vos projets de construction.', 5, 1),
('Mariama Sow', 'Promotrice immobilière', 'Excellente collaboration pour notre projet de 20 villas. Les prix sont compétitifs et l''équipe très professionnelle.', 5, 1),
('Moussa Ndiaye', 'Entrepreneur BTP', 'AL AMIINE Logistique est mon fournisseur de confiance depuis 3 ans. Qualité constante et service après-vente réactif.', 5, 1);
