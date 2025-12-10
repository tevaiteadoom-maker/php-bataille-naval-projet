-- Table des joueurs
CREATE TABLE players (
    id INT PRIMARY KEY,        -- 1 = joueur 1, 2 = joueur 2
    name VARCHAR(50),
    remaining_cells INT NOT NULL  -- nombre total de cases de bateaux restantes
);

-- Table des bateaux
CREATE TABLE ships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,      -- à quel joueur appartient le bateau
    name VARCHAR(50),            -- nom du bateau (porte-avion, croiseur...)
    remaining_cells INT NOT NULL, -- nombre de cases du bateau encore intactes
    FOREIGN KEY (player_id) REFERENCES players(id)
);

-- Table des cases de bateau
CREATE TABLE ship_cells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ship_id INT NOT NULL,       -- à quel bateau appartient cette case
    x INT NOT NULL,             -- position X sur la grille
    y INT NOT NULL,             -- position Y sur la grille
    status INT NOT NULL DEFAULT 1,   -- 1 = vivant, 0 = touché
    FOREIGN KEY (ship_id) REFERENCES ships(id)
);

-- Table optionnelle pour enregistrer les tirs
CREATE TABLE shots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shooter_id INT NOT NULL,    -- joueur qui tire
    target_id INT NOT NULL,     -- joueur ciblé
    x INT NOT NULL,
    y INT NOT NULL,
    hit BOOLEAN NOT NULL DEFAULT 0,  -- 1 = touche, 0 = raté
    FOREIGN KEY (shooter_id) REFERENCES players(id),
    FOREIGN KEY (target_id) REFERENCES players(id)
);
