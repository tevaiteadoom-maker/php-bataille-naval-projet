<?php

session_start();

$role = $_SESSION["role"] ?? null;
$id   = $_SESSION["id"] ?? null;

echo "Votre rôle : $role<br>";
echo "Votre ID de session : $id";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="description" content="Utilisation de grid areas">
	<title>Bataille Navale</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<main>
        <?php 

        $lines = 10;
        $column = 10;
        $grid = [];

        for ($i = 0; $i < $column; $i++) {
            for ($j = 0; $j < $lines; $j++) {
                $grid[$i][$j] = 0;
                ?>
            <form method = "post" action="../scripts/click_case.php">
                <button type="submit" name="click_case">
                    <?php echo $grid[$i][$j]; ?>
                </button>
                
            </form>
            <?php
            }
        }
            
        
        ?>

    </main>

    <form method = "post" action="../scripts/reset_total.php">
            <button type="submit" name="reset_total">
                ❌ Fin de partie (RESET)
            </button>
    </form>
</body>

</html>