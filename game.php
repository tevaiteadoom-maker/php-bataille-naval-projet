<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="description" content="Utilisation de grid areas">
	<title>Bataille Navale</title>
	<link rel="stylesheet" href="style.css">
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
                <h2><?php echo $grid[$i][$j]; ?></h2>
              
            <?php
            }
        }

        
            
        
        ?>
    </main>
</body>

</html>