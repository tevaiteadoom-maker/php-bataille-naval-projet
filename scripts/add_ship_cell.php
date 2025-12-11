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
    // --- Validation des positions ---
    $prev = null;
    $direction = 'none';

    foreach ($positions as $pos) {
        $x = $pos['x'];
        $y = $pos['y'];

        if (!$prev) {
            // première case
            $prev = ['x' => $x, 'y' => $y];
            continue;
        }

        $dx = $x - $prev['x'];
        $dy = $y - $prev['y'];

        if ($direction === 'none') {
            // déterminer la direction à partir de la 2e case
            if ($dx === 0 && abs($dy) === 1) $direction = 'vertical';
            elseif ($dy === 0 && abs($dx) === 1) $direction = 'horizontal';
            else {
                echo json_encode([
                    "success" => false,
                    "error" => "Mauvaise position : cases non adjacentes"
                ]);
                exit;
            }
        } else {
            // vérifier que la direction est respectée
            if (($direction === 'horizontal' && $dy !== 0) ||
                ($direction === 'vertical' && $dx !== 0)) {
                echo json_encode([
                    "success" => false,
                    "error" => "Mauvaise direction : toutes les cases doivent être alignées"
                ]);
                exit;
            }
        }

        $prev = ['x' => $x, 'y' => $y];
    }
    if ($direction === 'none') {
    if ($dx === 0 && abs($dy) === 1) $direction = 'vertical';
    elseif ($dy === 0 && abs($dx) === 1) $direction = 'horizontal';
    else {
        echo json_encode([
            "success" => false,
            "error" => "Mauvaise position : cases non adjacentes",
            "navire_name" => $data['navire_name'] ?? null
        ]);
        exit;
    }
}

// Vérification de la direction
if (($direction === 'horizontal' && $dy !== 0) ||
    ($direction === 'vertical' && $dx !== 0)) {
    echo json_encode([
        "success" => false,
        "error" => "Mauvaise direction : toutes les cases doivent être alignées",
        "navire_name" => $data['navire_name'] ?? null
    ]);
    exit;
}

    // --- Insertion dans la base ---
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

