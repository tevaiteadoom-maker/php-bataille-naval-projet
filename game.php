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

        for ($i = 0; $i < $column; $i++) {
            for ($j = 0; $j < $lines; $j++) {
                $grid[] = 0;
            }
        }

        var_dump($grid);
            
        
        ?>
    </main>
</body>

</html>