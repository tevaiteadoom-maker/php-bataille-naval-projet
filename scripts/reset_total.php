<?php
session_start();

$fichier = "../etat_joueurs.json";

$etat = ["j1" => null, "j2" => null];
file_put_contents($fichier, json_encode($etat));

session_unset();
session_destroy();

header("Location: ../index.php");
exit;
