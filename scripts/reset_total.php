<?php
session_start();

// 1️⃣ Réinitialiser le fichier JSON
$fichier = "../etat_joueurs.json";
$etat = ["j1" => null, "j2" => null];
file_put_contents($fichier, json_encode($etat));

// 2️⃣ Détruire la session
session_unset();
session_destroy();

// 3️⃣ Vider les tables MySQL
include __DIR__ . '/sql-connect.php'; // Assure-toi que SqlConnect fonctionne

$sql = new SqlConnect();

try {
    // Supprime toutes les données
    $sql->db->exec("DELETE FROM ship_cells");
    $sql->db->exec("DELETE FROM ships");
    $sql->db->exec("DELETE FROM players");
} catch (Exception $e) {
    echo "Erreur lors du reset de la base : " . $e->getMessage();
    exit;
}

// 4️⃣ Rediriger vers la page d'accueil
header("Location: ../index.php");
exit;
