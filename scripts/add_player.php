<?php
session_start();
include __DIR__ . '/sql-connect.php';

$sql = new SqlConnect();

if (!isset($_SESSION["player_id"])) {
    header("Location: ../index.php");
    exit;
}

$player_id = $_SESSION["player_id"];
$name = $_SESSION["role"];

// Crée ou met à jour le joueur
$stmt = $sql->db->prepare("
    INSERT INTO players (id, name, remaining_cells)
    VALUES (:id, :name, :cells)
    ON DUPLICATE KEY UPDATE
    name = :name, remaining_cells = :cells
");
$stmt->execute([
    'id' => $player_id,
    'name' => $name,
    'cells' => 20
]);

// Vérifie l'état des joueurs
$fichier = "../etat_joueurs.json";
$etat = json_decode(file_get_contents($fichier), true);

// Si les deux joueurs ont choisi leur rôle, redirige vers game.php
if ($etat["j1"] !== null && $etat["j2"] !== null) {
    header("Location: ../views/game.php");
    exit;
} else {
    // Sinon, reste sur la page d'attente
    echo "En attente de l'autre joueur...";
    exit;
}
