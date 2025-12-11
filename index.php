<?php
session_start();

$fichier = "etat_joueurs.json";

if (!file_exists($fichier)) {
    file_put_contents($fichier, json_encode(["j1" => null, "j2" => null]));
}

$etat = json_decode(file_get_contents($fichier), true);

if ($etat["j1"] !== null && $etat["j2"] !== null) {
    header("Location: ./scripts/add_player.php");
    exit;
}

function save_state($file, $data) {
    file_put_contents($file, json_encode($data));
}

if (isset($_POST["joueur1"]) && $etat["j1"] === null) {
    $etat["j1"] = uniqid("j1_", true);
    $_SESSION["player_id"] = 1;
    $_SESSION["role"] = "Joueur 1";
    save_state($fichier, $etat);
}

if (isset($_POST["joueur2"]) && $etat["j2"] === null) {
    $etat["j2"] = uniqid("j2_", true);
    $_SESSION["player_id"] = 2;
    $_SESSION["role"] = "Joueur 2";
    save_state($fichier, $etat);
}


$role = $_SESSION["role"] ?? "Aucun rôle";

header('refresh:4');
include('./views/players_selected.php');
?>