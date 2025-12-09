CREATE DATABASE biblio;
USE biblio;

CREATE TABLE bateaux (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bateau_id INT NOT NULL, -- 1 à 4 selon le type
    ligne INT NOT NULL,
    colonne INT NOT NULL
);

CREATE TABLE hits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ligne INT NOT NULL,
    colonne INT NOT NULL,
    result ENUM('hit','miss') NOT NULL
);

CREATE TABLE scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    joueur VARCHAR(50),
    victories INT DEFAULT 0
);
