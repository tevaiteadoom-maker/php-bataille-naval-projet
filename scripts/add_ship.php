<?php
header("Content-Type: application/json");
require_once "../config/db.php"; // ce fichier contient ta connexion PDO dans $pdo

$data = json_decode(file_get_contents("php://input"), true);

$player_id = $data["player_id"] ?? null;
$name = $data["name"] ?? null;
$cells = $data["remaining_cells"] ?? null;

if (!$player_id || !$name || !$cells) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO ships (player_id, name, remaining_cells) VALUES (?, ?, ?)");
    $stmt->execute([$player_id, $name, $cells]);

    echo json_encode([
        "success" => true,
        "ship_id" => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
