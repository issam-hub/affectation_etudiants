CREATE DATABASE Affectation_Etudiants;
USE Affectation_Etudiants;
CREATE TABLE Etudiant(
	matricule VARCHAR(255) NOT NULL PRIMARY KEY,
    nom_prenom VARCHAR(255) NOT NULL,
    MGC DECIMAL(4, 2) NOT NULL,
    ordre_GL INT NOT NULL,
    ordre_GI INT NOT NULL,
    ordre_RT INT NOT NULL,
    voeu_affecte VARCHAR(255) DEFAULT NULL,
    satisfaction VARCHAR(255) DEFAULT "non satisfait",
    choisit INT NOT NULL DEFAULT 0,
    mot_de_passe VARCHAR(8) NOT NULL DEFAULT "e_jkZe23",
    CONSTRAINT check_ordre_GL CHECK(ordre_GL IN (0, 1, 2, 3)),
    CONSTRAINT check_ordre_GI CHECK(ordre_GI IN (0, 1, 2, 3)),
    CONSTRAINT check_ordre_RT CHECK(ordre_RT IN (0, 1, 2, 3)),
    CONSTRAINT check_voeu CHECK(voeu_affecte IN ("GL", "GI", "RT")),
    CONSTRAINT check_satisfaction CHECK(satisfaction IN ("satisfait", "non satisfait")),
    CONSTRAINT check_mgc CHECK(mgc <= 20.00),
    CONSTRAINT check_choisit CHECK(choisit IN (0, 1))
);
CREATE TABLE Agent(
	username VARCHAR(255) NOT NULL PRIMARY KEY DEFAULT "admin",
    mot_de_passe VARCHAR(255) NOT NULL DEFAULT "admin"
);

INSERT INTO Agent VALUES("admin", "admin");

CREATE TABLE nombre_places(
    annee VARCHAR(255) PRIMARY KEY NOT NULL,
    gl INT DEFAULT 0,
    gi INT DEFAULT 0,
    rt INT DEFAULT 0
);