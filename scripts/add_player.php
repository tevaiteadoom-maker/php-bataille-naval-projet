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

$stmt = $sql->db->prepare("INSERT INTO players (name, remaining_cells) VALUES (:name, :cells)");
$stmt->execute([
    'name' => $_SESSION["role"],
    'cells' => 20
]);

$player_id = $sql->db->lastInsertId(); // <- récupère l'ID auto-incrémenté
$_SESSION["player_id"] = $player_id;   // stocke le vrai ID dans la session


$fichier = "../etat_joueurs.json";
$etat = json_decode(file_get_contents($fichier), true);

if ($etat["j1"] !== null && $etat["j2"] !== null) {
    header("Location: ../views/game.php");
    exit;
} else {
    echo "En attente de l'autre joueur...";
    exit;
}
