-- Base de données KakémonoViz
-- Jeu de caractères : utf8mb4

CREATE DATABASE IF NOT EXISTS kakemono
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE kakemono;

-- ─────────────────────────────────────────────
-- Table : devis
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS devis (
    id           INT          UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(100) NOT NULL,
    email        VARCHAR(150) NOT NULL,
    telephone    VARCHAR(20)  NOT NULL,
    type_support VARCHAR(80)  NOT NULL,
    largeur      DECIMAL(6,2) NOT NULL,
    hauteur      DECIMAL(6,2) NOT NULL,
    quantite     INT          UNSIGNED NOT NULL DEFAULT 1,
    description  TEXT,
    fichier      VARCHAR(255) DEFAULT NULL,
    statut       ENUM('nouveau','en_cours','termine','annule') NOT NULL DEFAULT 'nouveau',
    cree_le      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────
-- Table : portfolio
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS portfolio (
    id          INT          UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(150) NOT NULL,
    description TEXT,
    image       VARCHAR(255) NOT NULL,
    categorie   VARCHAR(80)  NOT NULL,
    cree_le     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────
-- Table : admins
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admins (
    id       INT          UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login    VARCHAR(80)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL   -- hash bcrypt
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────
-- Données de test — devis
-- ─────────────────────────────────────────────
INSERT INTO devis (nom, email, telephone, type_support, largeur, hauteur, quantite, description, statut) VALUES
('Sophie Martin',  'sophie.martin@email.fr',  '0612345678', 'Kakémono',   0.85, 2.00, 2, 'Kakémono pour salon professionnel, fond blanc logo couleur.',       'nouveau'),
('Thomas Dupont',  'thomas.dupont@email.fr',  '0698765432', 'Banderole',  3.00, 0.80, 1, 'Banderole inauguration magasin. Texte + adresse à fournir.',        'en_cours'),
('Claire Leclerc', 'claire.leclerc@email.fr', '0754321098', 'Roll-up',    0.85, 2.00, 5, 'Roll-up identique x5 pour nos 5 agences. Logo HD fourni.',         'termine'),
('Marc Lefebvre',  'marc.lefebvre@email.fr',  '0623456789', 'Affiche',    0.60, 0.80, 50,'Affiches A1 promotion été. Fichier Illustrator disponible.',        'nouveau'),
('Lucie Bernard',  'lucie.bernard@email.fr',  '0787654321', 'Toile tendue',2.00, 1.50, 1, 'Photo panoramique salle de réunion, finition baguette alu.',       'annule');

-- ─────────────────────────────────────────────
-- Données de test — portfolio
-- ─────────────────────────────────────────────
INSERT INTO portfolio (titre, description, image, categorie) VALUES
('Salon Batimat 2024',          'Stand 9 m² avec kakémonos et bannières à l\'identité de la marque.',          'placeholder_1.jpg', 'Kakémono'),
('Inauguration Boutique Mode',   'Banderole extérieure 4 m + vitrophanie complète de la devanture.',             'placeholder_2.jpg', 'Banderole'),
('Tournée Commerciale BTP',      'Lot de 12 roll-ups identiques pour équipe commerciale terrain.',               'placeholder_3.jpg', 'Roll-up'),
('Festival Lumières Lyon 2024',  'Impression grand format scénographie — 6 toiles tendues 3×2 m.',              'placeholder_4.jpg', 'Toile tendue'),
('Campagne Immobilier',          'Affiches A0 en tirage 200 ex. pour agence immobilière Grand Lyon.',           'placeholder_5.jpg', 'Affiche'),
('Soirée Gala Entreprise',       'Kakémono photocall 2×2 m avec fond mosaïque logos partenaires.',              'placeholder_6.jpg', 'Kakémono');

-- ─────────────────────────────────────────────
-- Données de test — admins
-- Identifiants : admin / Admin@2024
-- ─────────────────────────────────────────────
INSERT INTO admins (login, password) VALUES
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Note : le hash ci-dessus correspond au mot de passe "password"
-- Pour la production, régénérer avec password_hash('VotreMotDePasse', PASSWORD_BCRYPT, ['cost'=>12])
