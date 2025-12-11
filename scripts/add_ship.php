<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$player_id = $data["player_id"] ?? null;
$name = $data["name"] ?? null;
$cells = $data["remaining_cells"] ?? null;
$positions = $data["positions"] ?? [];

if (!$player_id || !$name || !$cells || empty($positions)) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1️⃣ Créer le navire
    $stmt = $pdo->prepare("INSERT INTO ships (player_id, name, remaining_cells) VALUES (?, ?, ?)");
    $stmt->execute([$player_id, $name, $cells]);
    $ship_id = $pdo->lastInsertId();

    // 2️⃣ Créer les cases du navire
    $stmt2 = $pdo->prepare("INSERT INTO ship_cells (ship_id, x, y) VALUES (?, ?, ?)");
    foreach ($positions as $pos) {
        $stmt2->execute([$ship_id, $pos['x'], $pos['y']]);
    }

    $pdo->commit();

    echo json_encode(["success" => true, "ship_id" => $ship_id]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
