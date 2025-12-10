<?php
header("Content-Type: application/json");
require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$ship_id = $data["ship_id"] ?? null;
$positions = $data["positions"] ?? [];

if (!$ship_id || empty($positions)) {
    echo json_encode(["success" => false, "error" => "Missing data"]);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO ship_cells (ship_id, x, y) VALUES (?, ?, ?)");
    foreach ($positions as $p) {
        $stmt->execute([$ship_id, $p["x"], $p["y"]]);
    }
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "count" => count($positions)
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
